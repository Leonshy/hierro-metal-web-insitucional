<div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'manage-menu-items-'.e($record->id).''; ?>wire:key="manage-menu-items-<?php echo e($record->id); ?>">
    <style>
        .menu-tree-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .menu-tree-header h2 { font-size: 1rem; font-weight: 700; }
        .menu-tree-header button { border: 1px solid currentColor; border-radius: .5rem; padding: .375rem .75rem; font-size: .8125rem; }
        .menu-tree-root, .menu-tree-children { list-style: none; margin: 0; padding: 0; }
        .menu-tree-children { margin-top: .5rem; margin-left: 2rem; min-height: .5rem; }
        .menu-tree-item { border: 1px solid rgba(120, 120, 120, .25); border-radius: .5rem; margin-bottom: .5rem; background: rgba(120, 120, 120, .03); }
        .menu-tree-row { display: flex; align-items: center; gap: .625rem; padding: .5rem .75rem; flex-wrap: wrap; }
        .menu-item-handle { cursor: grab; opacity: .5; display: inline-flex; }
        .menu-item-handle:hover { opacity: 1; }
        .menu-tree-label { font-weight: 600; }
        .menu-tree-url { opacity: .6; font-size: .8125rem; }
        .menu-tree-actions { display: flex; align-items: center; gap: .75rem; margin-left: auto; }
        .menu-tree-actions button { font-size: .75rem; text-decoration: underline; background: none; border: none; padding: 0; }
        .menu-tree-toggle { font-size: .75rem; border-radius: 999px; padding: .125rem .625rem; border: 1px solid rgba(120, 120, 120, .35); }
        .menu-tree-toggle.is-active { background: #dcfce7; border-color: #86efac; color: #166534; }
        .menu-tree-children:empty::before { content: 'Soltá acá un enlace para convertirlo en submenú'; display: block; padding: .5rem; font-size: .75rem; opacity: .5; }
        .fi-sortable-ghost { opacity: .4; }
    </style>

    <div class="menu-tree-header">
        <h2>Enlaces del menú</h2>
        <button type="button" wire:click="mountAction('create')">+ Agregar enlace</button>
    </div>

    <p style="font-size:.8125rem;opacity:.7;margin-bottom:.75rem">
        Arrastrá del ícono de puntos para reordenar. Soltá un enlace dentro de otro para que aparezca como su submenú.
    </p>

    <ul class="menu-tree-root" data-menu-sortable data-parent-id="">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->items->get(null, collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php echo $__env->make('livewire.partials.menu-item-node', ['item' => $item, 'children' => $this->items->get($item->id, collect())], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </ul>

    <?php if (isset($component)) { $__componentOriginal028e05680f6c5b1e293abd7fbe5f9758 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal028e05680f6c5b1e293abd7fbe5f9758 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-actions::components.modals','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-actions::modals'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal028e05680f6c5b1e293abd7fbe5f9758)): ?>
<?php $attributes = $__attributesOriginal028e05680f6c5b1e293abd7fbe5f9758; ?>
<?php unset($__attributesOriginal028e05680f6c5b1e293abd7fbe5f9758); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal028e05680f6c5b1e293abd7fbe5f9758)): ?>
<?php $component = $__componentOriginal028e05680f6c5b1e293abd7fbe5f9758; ?>
<?php unset($__componentOriginal028e05680f6c5b1e293abd7fbe5f9758); ?>
<?php endif; ?>
</div>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/livewire/manage-menu-items.blade.php ENDPATH**/ ?>