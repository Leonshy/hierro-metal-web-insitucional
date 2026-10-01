<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['slides' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['slides' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $slides = collect($slides)->filter(fn (array $slide) => filled($slide['title'] ?? null))->values();
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($slides->isEmpty()): ?>
    
<?php elseif($slides->count() === 1): ?>
    <?php $slide = $slides->first(); ?>
    <?php if (isset($component)) { $__componentOriginal04f02f1e0f152287a127192de01fe241 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal04f02f1e0f152287a127192de01fe241 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.hero','data' => ['display' => true,'title' => $slide['title'],'subtitle' => $slide['subtitle'] ?? null,'ctaLabel' => $slide['cta_label'] ?? null,'ctaUrl' => $slide['cta_url'] ?? null,'image' => $slide['image_url'] ?? null,'imageAlt' => $slide['image_alt'] ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['display' => true,'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($slide['title']),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($slide['subtitle'] ?? null),'cta-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($slide['cta_label'] ?? null),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($slide['cta_url'] ?? null),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($slide['image_url'] ?? null),'image-alt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($slide['image_alt'] ?? '')]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal04f02f1e0f152287a127192de01fe241)): ?>
<?php $attributes = $__attributesOriginal04f02f1e0f152287a127192de01fe241; ?>
<?php unset($__attributesOriginal04f02f1e0f152287a127192de01fe241); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal04f02f1e0f152287a127192de01fe241)): ?>
<?php $component = $__componentOriginal04f02f1e0f152287a127192de01fe241; ?>
<?php unset($__componentOriginal04f02f1e0f152287a127192de01fe241); ?>
<?php endif; ?>
<?php else: ?>
    <section
        class="hero hero-slider"
        x-data="{
            active: 0,
            count: <?php echo e($slides->count()); ?>,
            timer: null,
            reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            next() { this.active = (this.active + 1) % this.count; },
            prev() { this.active = (this.active - 1 + this.count) % this.count; },
            go(i) { this.active = i; this.restart(); },
            restart() {
                clearInterval(this.timer);
                if (this.reducedMotion) return;
                this.timer = setInterval(() => this.next(), 6000);
            },
        }"
        x-init="restart()"
        @mouseenter="clearInterval(timer)"
        @mouseleave="restart()"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            
            <div class="hero-slide" x-show="active === <?php echo e($i); ?>" x-transition:enter.opacity.duration.600ms x-transition:leave.opacity.duration.600ms <?php if($i !== 0): ?> style="display:none" <?php endif; ?>>
                <div class="hero-bg">
                    <img
                        src="<?php echo e($slide['image_url'] ?? ''); ?>"
                        <?php if(!empty($slide['image_srcset'])): ?>
                            srcset="<?php echo e($slide['image_srcset']); ?>"
                            sizes="100vw"
                        <?php endif; ?>
                        alt="<?php echo e($slide['image_alt'] ?? ''); ?>"
                        width="1600" height="900"
                        loading="<?php echo e($i === 0 ? 'eager' : 'lazy'); ?>"
                        <?php if($i === 0): ?> fetchpriority="high" <?php endif; ?>
                    >
                    <div class="hero-scrim" aria-hidden="true"></div>
                </div>
                <div class="container">
                    <div class="hero-content">
                        <h1 class="display"><?php echo e($slide['title']); ?></h1>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($slide['subtitle'])): ?>
                            <p class="body-lg hero-subtitle"><?php echo e($slide['subtitle']); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($slide['cta_label']) && !empty($slide['cta_url'])): ?>
                            <a class="btn btn-primary hero-cta" href="<?php echo e($slide['cta_url']); ?>"><?php echo e($slide['cta_label']); ?></a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <div class="hero-slider-controls">
            <button type="button" class="hero-slider-arrow hero-slider-arrow--prev" @click="prev(); restart()" aria-label="Slide anterior">
                <?php if (isset($component)) { $__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.chevron-down','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa)): ?>
<?php $attributes = $__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa; ?>
<?php unset($__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa)): ?>
<?php $component = $__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa; ?>
<?php unset($__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa); ?>
<?php endif; ?>
            </button>
            <div class="hero-slider-dots" role="tablist" aria-label="Slides del hero">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button
                        type="button" role="tab" class="hero-slider-dot"
                        :class="{ 'is-active': active === <?php echo e($i); ?> }"
                        :aria-selected="(active === <?php echo e($i); ?>).toString()"
                        @click="go(<?php echo e($i); ?>)"
                        aria-label="Ir al slide <?php echo e($i + 1); ?>"
                    ></button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <button type="button" class="hero-slider-arrow hero-slider-arrow--next" @click="next(); restart()" aria-label="Siguiente slide">
                <?php if (isset($component)) { $__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.chevron-down','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa)): ?>
<?php $attributes = $__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa; ?>
<?php unset($__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa)): ?>
<?php $component = $__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa; ?>
<?php unset($__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa); ?>
<?php endif; ?>
            </button>
        </div>
    </section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/hero-slider.blade.php ENDPATH**/ ?>