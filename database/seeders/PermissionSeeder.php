<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Tres roles del panel de Hierro Metal:
 *  - administrador: todo, incluidas integraciones, usuarios y redirecciones.
 *  - editor: contenido del sitio (páginas, catálogo, servicios, FAQ…) y medios.
 *  - ventas: atiende las cotizaciones; ve los vendedores. No toca contenido.
 */
class PermissionSeeder extends Seeder
{
    /** Contenido que edita el rol `editor`. */
    private const CONTENIDO = [
        'familias', 'servicios', 'pasos', 'compromisos', 'faqs', 'diferenciales', 'horarios',
    ];

    private array $modules = [
        'pages', 'posts', 'media', 'categories', 'menus', 'redirects',
        'settings', 'users', 'form_submissions', 'activity_log',
        'familias', 'servicios', 'pasos', 'compromisos', 'faqs', 'diferenciales',
        'horarios', 'vendedores', 'rubros', 'cotizaciones',
    ];

    private array $actions = ['view', 'create', 'update', 'delete', 'publish'];

    public function run(): void
    {
        foreach ($this->modules as $module) {
            foreach ($this->actions as $action) {
                Permission::findOrCreate("{$module}.{$action}");
            }
        }

        Role::findOrCreate('administrador')->syncPermissions(Permission::all());

        Role::findOrCreate('editor')->syncPermissions([
            ...$this->permissionsFor(['pages'], ['view', 'create', 'update', 'publish']),
            ...$this->permissionsFor(['media'], ['view', 'create', 'update', 'delete', 'publish']),
            ...$this->permissionsFor(self::CONTENIDO, ['view', 'create', 'update', 'delete']),
        ]);

        Role::findOrCreate('ventas')->syncPermissions([
            ...$this->permissionsFor(['cotizaciones', 'form_submissions'], ['view', 'update']),
            ...$this->permissionsFor(['vendedores'], ['view']),
        ]);
    }

    private function permissionsFor(array $modules, array $actions): array
    {
        $names = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $names[] = "{$module}.{$action}";
            }
        }

        return Permission::whereIn('name', $names)->get()->all();
    }
}
