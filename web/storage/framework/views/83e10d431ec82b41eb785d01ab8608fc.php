<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['size']));

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

foreach (array_filter((['size']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php if (isset($component)) { $__componentOriginaldd0978db3281e769965f3de7382f5715 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldd0978db3281e769965f3de7382f5715 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.megaphone','data' => ['size' => $size]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.megaphone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($size)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldd0978db3281e769965f3de7382f5715)): ?>
<?php $attributes = $__attributesOriginaldd0978db3281e769965f3de7382f5715; ?>
<?php unset($__attributesOriginaldd0978db3281e769965f3de7382f5715); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldd0978db3281e769965f3de7382f5715)): ?>
<?php $component = $__componentOriginaldd0978db3281e769965f3de7382f5715; ?>
<?php unset($__componentOriginaldd0978db3281e769965f3de7382f5715); ?>
<?php endif; ?><?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/storage/framework/views/8bcff0951824184550458d50a9176347.blade.php ENDPATH**/ ?>