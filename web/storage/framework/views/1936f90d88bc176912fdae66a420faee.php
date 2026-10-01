<section class="section reveal">
    <div class="container">
        <div class="section-head"><h2>Galería</h2></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($galleries->isEmpty()): ?>
            <?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['icon' => 'image']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'image']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Todavía no hay álbumes publicados. <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $attributes = $__attributesOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__attributesOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $component = $__componentOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__componentOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
        <?php else: ?>
            <div class="cards-grid">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $cover = $gallery->media->first(); ?>
                    <?php if (isset($component)) { $__componentOriginal2868af5daba95d636c4104f0e74e4dd1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2868af5daba95d636c4104f0e74e4dd1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card.section','data' => ['title' => $gallery->title,'text' => optional($gallery->event_date)->translatedFormat('d \d\e F \d\e Y'),'url' => route('galleries.index'),'image' => $cover?->conversionUrl('medium') ?? $cover?->url(),'imageSrcset' => $cover?->srcset(),'ctaLabel' => 'Ver galería']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($gallery->title),'text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(optional($gallery->event_date)->translatedFormat('d \d\e F \d\e Y')),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('galleries.index')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cover?->conversionUrl('medium') ?? $cover?->url()),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cover?->srcset()),'cta-label' => 'Ver galería']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2868af5daba95d636c4104f0e74e4dd1)): ?>
<?php $attributes = $__attributesOriginal2868af5daba95d636c4104f0e74e4dd1; ?>
<?php unset($__attributesOriginal2868af5daba95d636c4104f0e74e4dd1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2868af5daba95d636c4104f0e74e4dd1)): ?>
<?php $component = $__componentOriginal2868af5daba95d636c4104f0e74e4dd1; ?>
<?php unset($__componentOriginal2868af5daba95d636c4104f0e74e4dd1); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <p style="margin-top:var(--spacing-6)"><a class="btn btn-secondary" href="<?php echo e(route('galleries.index')); ?>">Ver toda la galería</a></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/home/sections/gallery.blade.php ENDPATH**/ ?>