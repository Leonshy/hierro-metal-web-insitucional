<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Support\Catalogo;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

/**
 * Catálogo PDF vigente. El cliente sube el archivo acá y el enlace público (`/catalogo.pdf`) no
 * cambia nunca. Sin archivo cargado, los botones de descarga del sitio no se muestran.
 *
 * @property-read Schema $form
 */
class CatalogoPdf extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    protected static ?string $navigationLabel = 'Catálogo PDF';

    protected static string|UnitEnum|null $navigationGroup = 'Configuraciones';

    protected static ?int $navigationSort = 15;

    protected static ?string $title = 'Catálogo PDF';

    protected static ?string $slug = 'catalogo-pdf';

    protected string $view = 'filament.pages.catalogo-pdf';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('viewAny', SiteSetting::class);
    }

    public function mount(): void
    {
        $this->form->fill([
            'archivo' => Catalogo::ruta(),
            'texto_boton' => Catalogo::textoBoton(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Archivo')
                        ->description('Reemplazalo cuando haya un catálogo nuevo: el enlace público no cambia. Si lo quitás, los botones de descarga dejan de mostrarse.')
                        ->schema([
                            FileUpload::make('archivo')
                                ->label('Catálogo en PDF')
                                ->disk(Catalogo::DISCO)
                                ->directory('catalogo')
                                ->visibility('private')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(30 * 1024)
                                ->helperText('Sólo PDF, hasta 30 MB.'),
                        ]),
                    Section::make('Botón')
                        ->schema([
                            TextInput::make('texto_boton')
                                ->label('Texto del botón')
                                ->required()
                                ->maxLength(60),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Guardar')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->can('update', SiteSetting::query()->firstOrNew()), 403);

        $datos = $this->form->getState();
        $anterior = SiteSetting::get('catalogo_path');
        $nuevo = filled($datos['archivo'] ?? null) ? (string) $datos['archivo'] : null;

        SiteSetting::set('catalogo_path', $nuevo, 'text', 'catalogo');
        SiteSetting::set('catalogo_texto_boton', $datos['texto_boton'], 'text', 'catalogo');

        // El archivo reemplazado o quitado no queda huérfano en el disco.
        if (filled($anterior) && $anterior !== $nuevo) {
            Storage::disk(Catalogo::DISCO)->delete($anterior);
        }

        Notification::make()->success()->title('Guardado')->send();
    }
}
