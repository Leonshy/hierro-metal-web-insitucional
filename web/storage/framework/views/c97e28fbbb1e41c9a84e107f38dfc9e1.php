<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Página no encontrada — Colegio Dante Alighieri','indexable' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Página no encontrada — Colegio Dante Alighieri','indexable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <main id="contenido" tabindex="-1">
        <div class="not-found">
            <p class="code" aria-hidden="true">404</p>
            <h1>Esta página no existe</h1>
            <p class="body-lg">La dirección que buscó no está disponible. Puede haber cambiado de nombre o ya no existir.</p>
            <form class="searchbar" role="search" action="<?php echo e(route('search.index')); ?>" method="GET" style="margin:var(--spacing-6) auto 0">
                <label for="q">Buscar en el sitio</label>
                <input id="q" name="q" type="search" placeholder="Buscar en el sitio">
                <button type="submit" aria-label="Buscar en el sitio"><?php if (isset($component)) { $__componentOriginal60b104b2fde947186a9c15caab3ac427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60b104b2fde947186a9c15caab3ac427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.search','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60b104b2fde947186a9c15caab3ac427)): ?>
<?php $attributes = $__attributesOriginal60b104b2fde947186a9c15caab3ac427; ?>
<?php unset($__attributesOriginal60b104b2fde947186a9c15caab3ac427); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60b104b2fde947186a9c15caab3ac427)): ?>
<?php $component = $__componentOriginal60b104b2fde947186a9c15caab3ac427; ?>
<?php unset($__componentOriginal60b104b2fde947186a9c15caab3ac427); ?>
<?php endif; ?></button>
            </form>
            <div class="cta-row">
                <a class="btn btn-primary" href="<?php echo e(url('/')); ?>">Ir al inicio</a>
                <a class="btn btn-secondary" href="<?php echo e(route('search.index')); ?>">Buscar en el sitio</a>
            </div>
        </div>
    </main>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/errors/404.blade.php ENDPATH**/ ?>