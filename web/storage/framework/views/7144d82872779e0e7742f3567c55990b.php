<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'subtitle' => null, 'image' => null, 'imageAlt' => '', 'ctaLabel' => null, 'ctaUrl' => null, 'display' => false]));

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

foreach (array_filter((['title', 'subtitle' => null, 'image' => null, 'imageAlt' => '', 'ctaLabel' => null, 'ctaUrl' => null, 'display' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<section class="hero <?php echo e($image ? 'hero--media' : 'hero--plain'); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image): ?>
        <div class="hero-bg">
            <img src="<?php echo e($image); ?>" alt="<?php echo e($imageAlt); ?>" width="1600" height="900" loading="eager" fetchpriority="high">
            <div class="hero-scrim" aria-hidden="true"></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="container">
        <div class="hero-content">
            <h1 class="<?php echo \Illuminate\Support\Arr::toCssClasses(['display' => $display]); ?>"><?php echo e($title); ?></h1>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subtitle): ?>
                <p class="body-lg hero-subtitle"><?php echo e($subtitle); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ctaLabel && $ctaUrl): ?>
                <a class="btn btn-primary hero-cta" href="<?php echo e($ctaUrl); ?>"><?php echo e($ctaLabel); ?></a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php echo e($slot ?? ''); ?>

        </div>
    </div>
</section>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/hero.blade.php ENDPATH**/ ?>