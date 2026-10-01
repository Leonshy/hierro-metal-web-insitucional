<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;

/**
 * Pedido de cotización: el lead del sitio. Se guarda siempre antes de intentar el aviso
 * por correo, así un problema de SMTP no cuesta un pedido (CLAUDE.md regla 8).
 *
 * @property string $uuid
 * @property string $nombre
 * @property string|null $empresa
 * @property string $telefono
 * @property string $email
 * @property string|null $rubro
 * @property string $mensaje
 * @property string|null $origen
 * @property string $estado
 * @property Carbon $created_at
 * @property Carbon|null $mail_enviado_at
 * @property int $mail_intentos
 * @property-read Collection<int, CotizacionAdjunto> $adjuntos
 * @property-read User|null $asignado
 */
#[Fillable(['uuid', 'nombre', 'empresa', 'telefono', 'email', 'rubro', 'mensaje', 'origen', 'utm', 'estado', 'asignado_a', 'notas_internas', 'ip', 'user_agent', 'mail_enviado_at', 'mail_intentos', 'alertada_at'])]
class Cotizacion extends Model
{
    use HasAuditing, HasFactory, SoftDeletes;

    protected $table = 'cotizaciones';

    public const ESTADOS = [
        'nueva' => 'Nueva',
        'en_curso' => 'En curso',
        'cotizada' => 'Cotizada',
        'ganada' => 'Ganada',
        'perdida' => 'Perdida',
        'spam' => 'Spam',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $cotizacion): void {
            $cotizacion->uuid ??= (string) Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'utm' => 'array',
            'mail_enviado_at' => 'datetime',
            'alertada_at' => 'datetime',
        ];
    }

    /** La auditoría sólo registra el seguimiento: los datos personales del pedido no se copian al historial. */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['estado', 'asignado_a', 'notas_internas'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function adjuntos(): HasMany
    {
        return $this->hasMany(CotizacionAdjunto::class);
    }

    public function asignado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    /**
     * @param  Builder<Cotizacion>  $query
     * @return Builder<Cotizacion>
     */
    public function scopeReales(Builder $query): Builder
    {
        return $query->where('estado', '!=', 'spam');
    }

    /**
     * Pedidos reales cuyo aviso por correo todavía no salió.
     *
     * @param  Builder<Cotizacion>  $query
     * @return Builder<Cotizacion>
     */
    public function scopeConAvisoPendiente(Builder $query): Builder
    {
        return $query->reales()->whereNull('mail_enviado_at');
    }

    public function esSpam(): bool
    {
        return $this->estado === 'spam';
    }

    /** Teléfono en formato internacional para wa.me (Paraguay: 0981… pasa a 595981…). */
    public function telefonoInternacional(): string
    {
        $digitos = preg_replace('/\D+/', '', $this->telefono) ?? '';

        if (str_starts_with($digitos, '00')) {
            $digitos = substr($digitos, 2);
        }

        if (str_starts_with($digitos, '0')) {
            $digitos = '595'.substr($digitos, 1);
        }

        return $digitos;
    }

    /** «Responder por WhatsApp» con un saludo precargado. */
    public function whatsappUrl(): string
    {
        $primerNombre = Str::before(trim($this->nombre), ' ');
        $texto = "Hola {$primerNombre}, te escribimos de Hierro Metal por tu pedido de cotización.";

        return 'https://wa.me/'.$this->telefonoInternacional().'?text='.rawurlencode($texto);
    }
}
