<?php
    $primaryNav = \App\Models\Menu::renderTree('primary');
?>

<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?php echo e(url('/')); ?>" aria-label="Hierro Metal S.R.L. — inicio">
            <img src="<?php echo e(asset('images/logo-hierro-metal.svg')); ?>" alt="" width="96" height="64">
        </a>
        <nav aria-label="Navegación principal">
            <ul>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $primaryNav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li><a href="<?php echo e(url($item['url'])); ?>"><?php echo e($item['label']); ?></a></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </nav>
        <a class="btn btn-primary" href="<?php echo e(url('/contacto')); ?>">Pedir cotización</a>
    </div>
</header>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/site-header.blade.php ENDPATH**/ ?>