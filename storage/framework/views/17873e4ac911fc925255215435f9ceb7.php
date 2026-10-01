<?php
    $secondary = \App\Models\Menu::renderTree('footer_secondary');
?>

<footer class="site-footer">
    <div class="container">
        <p>Hierro Metal S.R.L. — importación y venta de materiales de construcción metálicos y metalúrgicos.</p>
        <ul>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $secondary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <li><a href="<?php echo e(url($link['url'])); ?>"><?php echo e($link['label']); ?></a></li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
        <p>&copy; <?php echo e(now()->year); ?> Hierro Metal S.R.L. · Todos los derechos reservados</p>
    </div>
</footer>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/site-footer.blade.php ENDPATH**/ ?>