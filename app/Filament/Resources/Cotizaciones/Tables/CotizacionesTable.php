<?php

namespace App\Filament\Resources\Cotizaciones\Tables;

use App\Models\Cotizacion;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CotizacionesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // El spam no ensucia la bandeja: sólo aparece si se lo pide con el filtro.
            // Ojo: Filament inyecta por NOMBRE (`$query`); con otro nombre recibiría un constructor sin modelo.
            ->modifyQueryUsing(function (Builder $query, $livewire): Builder {
                $filtros = $livewire->tableFilters ?? [];
                $verSpam = ($filtros['incluir_spam']['isActive'] ?? false) || in_array('spam', (array) ($filtros['estado']['values'] ?? []), true);

                return $verSpam ? $query : $query->scopes('reales');
            })
            ->columns([
                TextColumn::make('created_at')->label('Recibida')->dateTime('d/m/Y H:i')->timezone('America/Asuncion')->sortable(),
                TextColumn::make('nombre')->label('Nombre')->searchable()->weight('bold'),
                TextColumn::make('ci_ruc')->label('CI o RUC')->searchable()->placeholder('—')->copyable(),
                TextColumn::make('empresa')->label('Empresa u obra')->searchable()->placeholder('—')->toggleable(),
                TextColumn::make('telefono')->label('Teléfono')->searchable()->copyable(),
                TextColumn::make('rubro')->label('Rubro')->badge()->color('gray')->placeholder('—'),
                TextColumn::make('estado')->label('Estado')->badge()
                    ->formatStateUsing(fn (string $state): string => Cotizacion::ESTADOS[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'nueva' => 'warning', 'en_curso' => 'info', 'cotizada' => 'primary',
                        'ganada' => 'success', 'perdida' => 'gray', default => 'danger',
                    }),
                TextColumn::make('adjuntos_count')->label('Archivos')->counts('adjuntos')->badge()->color('gray'),
                IconColumn::make('aviso')->label('Aviso enviado')
                    ->state(fn (Cotizacion $c): bool => $c->mail_enviado_at !== null)
                    ->boolean()->trueIcon(Heroicon::OutlinedCheckCircle)->falseIcon(Heroicon::OutlinedExclamationTriangle)->falseColor('warning'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('estado')->label('Estado')->multiple()->options(Cotizacion::ESTADOS),
                SelectFilter::make('rubro')->label('Rubro')
                    ->options(fn (): array => Cotizacion::query()->whereNotNull('rubro')->distinct()->orderBy('rubro')->pluck('rubro', 'rubro')->all()),
                Filter::make('fecha')->label('Fecha')
                    ->schema([DatePicker::make('desde')->label('Desde'), DatePicker::make('hasta')->label('Hasta')])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['desde'] ?? null, fn (Builder $query, string $fecha) => $query->whereDate('created_at', '>=', $fecha))
                        ->when($data['hasta'] ?? null, fn (Builder $query, string $fecha) => $query->whereDate('created_at', '<=', $fecha))),
                Filter::make('aviso_pendiente')->label('Aviso sin enviar')->toggle()
                    ->query(fn (Builder $query): Builder => $query->scopes('conAvisoPendiente')),
                Filter::make('incluir_spam')->label('Incluir spam')->toggle()->query(fn (Builder $query): Builder => $query),
            ])
            ->recordActions([
                EditAction::make()->label('Abrir'),
                Action::make('whatsapp')->label('WhatsApp')->icon(Heroicon::OutlinedChatBubbleLeftRight)->color('success')
                    ->url(fn (Cotizacion $c): string => $c->whatsappUrl(), shouldOpenInNewTab: true),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('marcar_en_curso')->label('Marcar en curso')
                        ->action(fn (Collection $registros) => $registros->each->update(['estado' => 'en_curso']))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('marcar_spam')->label('Marcar como spam')->color('danger')->requiresConfirmation()
                        ->action(fn (Collection $registros) => $registros->each->update(['estado' => 'spam']))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make()->label('Eliminar'),
                ]),
            ]);
    }
}
