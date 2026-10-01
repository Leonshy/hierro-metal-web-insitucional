<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'primary', // primary | secondary | ghost | link | danger
    'href' => null,
    'type' => 'button',
    'loading' => false,
    'disabled' => false,
]));

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

foreach (array_filter(([
    'variant' => 'primary', // primary | secondary | ghost | link | danger
    'href' => null,
    'type' => 'button',
    'loading' => false,
    'disabled' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $class = 'btn btn-'.$variant;
    $tag = $href ? 'a' : 'button';
?>
<<?php echo e($tag); ?>

    <?php echo e($attributes->merge(['class' => $class])); ?>

    <?php if($href): ?> href="<?php echo e($href); ?>" <?php else: ?> type="<?php echo e($type); ?>" <?php endif; ?>
    <?php if($disabled): ?> disabled <?php endif; ?>
    <?php if($loading): ?> aria-busy="true" <?php endif; ?>
>
    <?php echo e($slot); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loading): ?>
        <span class="spinner" aria-hidden="true"></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</<?php echo e($tag); ?>>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/button.blade.php ENDPATH**/ ?>