<?php

namespace App\Filament\Secciones;

use App\Models\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page as PaginaDelPanel;
use Filament\Panel;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * Pantalla de una sección del sitio (Inicio, Productos, Servicios…): arriba el contenido de su página (titular,
 * bajada, botón, SEO) y abajo las tablas de lo que le pertenece (familias, pasos, compromisos…), que son los
 * widgets de pie. Lo que se edita acá se guarda en la página estructural correspondiente (`Page::SECCIONES`).
 *
 * @property-read Schema $form
 */
abstract class SeccionPage extends PaginaDelPanel
{
    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected string $view = 'filament.pages.seccion';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** Dirección (slug) de la página estructural que edita esta sección. */
    abstract protected static function paginaSlug(): string;

    /** ¿El encabezado tiene botón? Sólo donde la página pública lo muestra. */
    protected static function conBoton(): bool
    {
        return false;
    }

    /** ¿Se puede decidir si Google indexa la página? La portada siempre se indexa. */
    protected static function permiteIndexacion(): bool
    {
        return true;
    }

    /**
     * Campos propios de la sección, entre el encabezado y el SEO.
     *
     * @return array<int, mixed>
     */
    protected function camposDeLaSeccion(): array
    {
        return [];
    }

    /**
     * Lo que se guarda de esos campos propios, ya en el formato de la página.
     *
     * @param  array<string, mixed>  $estado
     * @param  array<int, array<string, mixed>>  $bloques  los bloques actuales de la página, sin el hero
     * @return array<int, array<string, mixed>>
     */
    protected function bloquesDeLaSeccion(array $estado, array $bloques): array
    {
        return $bloques;
    }

    /**
     * Campos extra del encabezado (van dentro del bloque hero), con su valor actual.
     *
     * @return array<int, string>
     */
    protected function clavesExtraDelHero(): array
    {
        return [];
    }

    /** @return array<string, mixed> */
    protected function estadoExtra(Page $pagina): array
    {
        return [];
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('viewAny', Page::class);
    }

    public static function getSlug(?Panel $panel = null): string
    {
        // Prefijo propio: «servicios» a secas chocaría con la dirección del módulo de servicios.
        return 'secciones/'.static::paginaSlug();
    }

    public function mount(): void
    {
        $this->form->fill($this->estadoInicial());
    }

    protected function pagina(): Page
    {
        return Page::query()->where('slug', static::paginaSlug())->firstOrFail();
    }

    /** @return array<string, mixed> */
    protected function estadoInicial(): array
    {
        $pagina = $this->pagina();
        $hero = collect($pagina->blocks ?? [])->firstWhere('type', 'hero')['data'] ?? [];

        $estado = [
            'titulo' => self::es($hero, 'title') ?? $pagina->getTranslation('title', 'es'),
            'bajada' => self::es($hero, 'subtitle'),
            'boton_texto' => self::es($hero, 'cta_label'),
            'boton_url' => $hero['cta_url'] ?? null,
            'seo_titulo' => $pagina->getTranslation('seo_title', 'es', false) ?: null,
            'seo_descripcion' => $pagina->getTranslation('seo_description', 'es', false) ?: null,
            'indexable' => (bool) $pagina->is_indexable,
        ];

        foreach ($this->clavesExtraDelHero() as $clave) {
            $estado[$clave] = $clave === 'media_id' ? ($hero['media_id'] ?? null) : self::es($hero, $clave);
        }

        return $estado + $this->estadoExtra($pagina);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Encabezado de la página')
                        ->description('El título y la bajada que se ven arriba de todo en la página pública.')
                        ->schema([
                            TextInput::make('titulo')->label('Título')->required()->maxLength(255),
                            Textarea::make('bajada')->label('Bajada')->rows(3)->maxLength(500),
                            ...(static::conBoton() ? [
                                TextInput::make('boton_texto')->label('Texto del botón')->maxLength(60),
                                TextInput::make('boton_url')->label('Enlace del botón')->helperText('Una ruta del sitio (ej: /contacto) o una dirección completa.')->maxLength(255),
                            ] : []),
                        ]),
                    ...$this->camposDeLaSeccion(),
                    Section::make('Buscadores (SEO)')
                        ->description('Cómo se ve esta página en Google. Si lo dejás vacío, se usa el título de la página.')
                        ->collapsed()
                        ->schema([
                            TextInput::make('seo_titulo')->label('Título para buscadores')->maxLength(60),
                            Textarea::make('seo_descripcion')->label('Descripción para buscadores')->rows(2)->maxLength(160),
                            ...(static::permiteIndexacion() ? [
                                Toggle::make('indexable')->label('Permitir que Google indexe esta página'),
                            ] : []),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')->label('Guardar')->submit('save')->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $estado = $this->form->getState();
        $pagina = $this->pagina();
        $bloques = collect($pagina->blocks ?? []);
        $hero = $bloques->firstWhere('type', 'hero') ?? ['type' => 'hero', 'data' => []];
        $resto = $bloques->reject(fn (array $b): bool => ($b['type'] ?? null) === 'hero')->values()->all();

        $datos = $hero['data'] ?? [];
        $datos['title'] = ['es' => (string) $estado['titulo']];
        $datos['subtitle'] = filled($estado['bajada'] ?? null) ? ['es' => (string) $estado['bajada']] : null;

        if (static::conBoton()) {
            $datos['cta_label'] = filled($estado['boton_texto'] ?? null) ? ['es' => (string) $estado['boton_texto']] : null;
            $datos['cta_url'] = $estado['boton_url'] ?? null;
        }

        foreach ($this->clavesExtraDelHero() as $clave) {
            $datos[$clave] = $clave === 'media_id'
                ? ($estado[$clave] ?: null)
                : (filled($estado[$clave] ?? null) ? ['es' => (string) $estado[$clave]] : null);
        }

        $pagina->update([
            'title' => ['es' => (string) $estado['titulo']],
            'seo_title' => ['es' => (string) ($estado['seo_titulo'] ?? '')],
            'seo_description' => ['es' => (string) ($estado['seo_descripcion'] ?? '')],
            'is_indexable' => static::permiteIndexacion() ? (bool) ($estado['indexable'] ?? true) : $pagina->is_indexable,
            'blocks' => [['type' => 'hero', 'data' => array_filter($datos, fn ($v) => $v !== null)], ...$this->bloquesDeLaSeccion($estado, $resto)],
            'updated_by' => auth()->id(),
        ]);

        Notification::make()->success()->title('Guardado')->send();
    }

    /** @return array<string, mixed>|string|null */
    private static function es(array $datos, string $clave): ?string
    {
        $valor = $datos[$clave] ?? null;

        return is_array($valor) ? ($valor['es'] ?? null) : (is_string($valor) ? $valor : null);
    }
}
