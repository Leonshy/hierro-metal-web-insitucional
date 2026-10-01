<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'options' => [], 'required' => false, 'selected' => null]));

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

foreach (array_filter((['name', 'label', 'options' => [], 'required' => false, 'selected' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $error = $errors->first($name);
    $current = $selected ?? old($name);
?>
<div class="field <?php if($error): ?> has-error <?php endif; ?>">
    <label for="<?php echo e($name); ?>"><?php echo e($label); ?></label>
    <select
        id="<?php echo e($name); ?>"
        name="<?php echo e($name); ?>"
        <?php if($required): ?> required aria-required="true" <?php endif; ?>
        <?php if($error): ?> aria-invalid="true" aria-describedby="<?php echo e($name); ?>-error" <?php endif; ?>
        <?php echo e($attributes); ?>

    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <option value="<?php echo e($value); ?>" <?php if($current === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </select>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
        <p class="msg-error" id="<?php echo e($name); ?>-error" role="alert"><?php if (isset($component)) { $__componentOriginal6c5c768c12c98a5a9b5743f6477269e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6c5c768c12c98a5a9b5743f6477269e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.alert-circle','data' => ['size' => '14']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.alert-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => '14']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6c5c768c12c98a5a9b5743f6477269e9)): ?>
<?php $attributes = $__attributesOriginal6c5c768c12c98a5a9b5743f6477269e9; ?>
<?php unset($__attributesOriginal6c5c768c12c98a5a9b5743f6477269e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6c5c768c12c98a5a9b5743f6477269e9)): ?>
<?php $component = $__componentOriginal6c5c768c12c98a5a9b5743f6477269e9; ?>
<?php unset($__componentOriginal6c5c768c12c98a5a9b5743f6477269e9); ?>
<?php endif; ?> <?php echo e($error); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/form/select.blade.php ENDPATH**/ ?>