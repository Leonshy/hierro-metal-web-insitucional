<?php
    $isRoot = is_null($item->parent_id);
?>
<li data-item-id="<?php echo e($item->id); ?>" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'menu-item-'.e($item->id).''; ?>wire:key="menu-item-<?php echo e($item->id); ?>" class="menu-tree-item">
    <div class="menu-tree-row">
        <span class="menu-item-handle" title="Arrastrar para reordenar / anidar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <circle cx="9" cy="6" r="1.5" /><circle cx="15" cy="6" r="1.5" />
                <circle cx="9" cy="12" r="1.5" /><circle cx="15" cy="12" r="1.5" />
                <circle cx="9" cy="18" r="1.5" /><circle cx="15" cy="18" r="1.5" />
            </svg>
        </span>

        <span class="menu-tree-label"><?php echo e($item->label); ?></span>
        <span class="menu-tree-url"><?php echo e($item->resolvedUrl()); ?></span>

        <button
            type="button"
            wire:click="toggleActive(<?php echo e($item->id); ?>)"
            class="menu-tree-toggle<?php echo e($item->is_active ? ' is-active' : ''); ?>"
        >
            <?php echo e($item->is_active ? 'Activo' : 'Inactivo'); ?>

        </button>

        <span class="menu-tree-actions">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isRoot): ?>
                <button type="button" wire:click="mountAction('create', { parent_id: <?php echo e($item->id); ?> })">
                    + Submenú
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <button type="button" wire:click="mountAction('edit', { item: <?php echo e($item->id); ?> })">Editar</button>
            <button type="button" wire:click="mountAction('delete', { item: <?php echo e($item->id); ?> })">Borrar</button>
        </span>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isRoot): ?>
        <ul class="menu-tree-children" data-menu-sortable data-parent-id="<?php echo e($item->id); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php echo $__env->make('livewire.partials.menu-item-node', ['item' => $child, 'children' => collect()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</li>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/livewire/partials/menu-item-node.blade.php ENDPATH**/ ?>