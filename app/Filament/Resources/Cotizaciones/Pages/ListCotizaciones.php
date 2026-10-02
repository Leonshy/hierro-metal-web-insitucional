<?php

namespace App\Filament\Resources\Cotizaciones\Pages;

use App\Filament\Resources\Cotizaciones\CotizacionResource;
use App\Models\Cotizacion;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListCotizaciones extends ListRecords
{
    protected static string $resource = CotizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportar')
                ->label('Exportar CSV')
                ->icon(Heroicon::ArrowDownTray)
                ->color('gray')
                ->action(fn (): StreamedResponse => $this->exportarCsv()),
        ];
    }

    /** Exporta lo que se ve en la tabla, con los filtros aplicados. */
    private function exportarCsv(): StreamedResponse
    {
        $consulta = $this->getFilteredSortedTableQuery()->with(['asignado', 'adjuntos']);

        return response()->streamDownload(function () use ($consulta): void {
            $salida = fopen('php://output', 'w');
            fwrite($salida, "\xEF\xBB\xBF"); // para que Excel abra bien las tildes
            fputcsv($salida, ['Recibida', 'Estado', 'Nombre', 'CI o RUC', 'Empresa', 'Teléfono', 'Correo', 'Rubro', 'Pedido', 'Origen', 'Asignado a', 'Adjuntos', 'Aviso enviado']);

            $consulta->chunk(200, function (Collection $cotizaciones) use ($salida): void {
                foreach ($cotizaciones->whereInstanceOf(Cotizacion::class) as $c) {
                    fputcsv($salida, array_map([self::class, 'celdaSegura'], [
                        $c->created_at->format('Y-m-d H:i'),
                        Cotizacion::ESTADOS[$c->estado] ?? $c->estado,
                        $c->nombre, $c->ci_ruc, $c->empresa, $c->telefono, $c->email, $c->rubro,
                        $c->mensaje, $c->origen, $c->asignado?->name,
                        $c->adjuntos->count(),
                        $c->mail_enviado_at ? 'Sí' : 'No',
                    ]));
                }
            });

            fclose($salida);
        }, 'cotizaciones-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Evita la inyección de fórmulas: una celda que empieza con = + - @ se neutraliza para Excel. */
    public static function celdaSegura(mixed $valor): string
    {
        $texto = (string) $valor;

        return $texto !== '' && in_array($texto[0], ['=', '+', '-', '@', "\t", "\r"], true) ? "'".$texto : $texto;
    }
}
