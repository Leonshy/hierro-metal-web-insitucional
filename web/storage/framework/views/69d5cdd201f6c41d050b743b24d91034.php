<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['url' => null, 'title', 'text' => null, 'image' => null, 'imageSrcset' => null, 'imageAlt' => '', 'ctaLabel' => 'Ver más']));

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

foreach (array_filter((['url' => null, 'title', 'text' => null, 'image' => null, 'imageSrcset' => null, 'imageAlt' => '', 'ctaLabel' => 'Ver más']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php $tag = $url ? 'a' : 'div'; ?>
<<?php echo e($tag); ?> class="card" <?php if($url): ?> href="<?php echo e($url); ?>" <?php endif; ?> <?php echo e($attributes); ?>>
    <div class="card-media">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image): ?>
            <img
                src="<?php echo e($image); ?>"
                <?php if($imageSrcset): ?>
                    srcset="<?php echo e($imageSrcset); ?>"
                    sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                <?php endif; ?>
                alt="<?php echo e($imageAlt); ?>" width="480" height="270" loading="lazy"
            >
        <?php else: ?>
            <span class="seal-lg" aria-hidden="true"></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div class="card-body">
        <h3><?php echo e($title); ?></h3>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($text): ?>
            <p><?php echo e($text); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($url): ?>
            <span class="btn-link"><?php echo e($ctaLabel); ?> →</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</<?php echo e($tag); ?>>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/card/section.blade.php ENDPATH**/ ?>