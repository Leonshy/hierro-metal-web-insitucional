<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['size' => 16]));

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

foreach (array_filter((['size' => 16]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<svg xmlns="http://www.w3.org/2000/svg" width="<?php echo e($size); ?>" height="<?php echo e($size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" <?php echo e($attributes); ?>>
    <path d="M3 21l1.65-4.95A9 9 0 1 1 8.05 19.35L3 21z"></path>
    <path d="M8.5 9.5c0 3.5 2.5 6 6 6 .5 0 1-.5 1-1.2 0-.3-.1-.5-.3-.6l-1.7-1a.7.7 0 0 0-.8.1l-.4.5a5 5 0 0 1-2.6-2.6l.5-.4a.7.7 0 0 0 .1-.8l-1-1.7a.7.7 0 0 0-.6-.3c-.7 0-1.2.5-1.2 1z"></path>
</svg>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/icon/whatsapp.blade.php ENDPATH**/ ?>