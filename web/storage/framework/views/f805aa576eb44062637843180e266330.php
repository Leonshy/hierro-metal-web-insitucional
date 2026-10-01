<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['items']));

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

foreach (array_filter((['items']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?> 
<div <?php echo e($attributes->merge(['class' => 'accordion'])); ?>>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="accordion-item" x-data="{ open: <?php echo e($index === 0 ? 'true' : 'false'); ?> }">
            <button type="button" class="accordion-trigger" :aria-expanded="open.toString()"
                    aria-controls="accordion-panel-<?php echo e($attributes->get('id', 'a')); ?>-<?php echo e($index); ?>"
                    @click="open = !open">
                <span><?php echo e($item['question']); ?></span>
                <span class="chev" aria-hidden="true"><?php if (isset($component)) { $__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa = $component; } ?>
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
<?php endif; ?></span>
            </button>
            <div class="accordion-panel" :class="{ 'is-open': open }" :inert="!open"
                 id="accordion-panel-<?php echo e($attributes->get('id', 'a')); ?>-<?php echo e($index); ?>">
                <div class="accordion-panel-inner"><?php echo $item['answer']; ?></div>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/accordion.blade.php ENDPATH**/ ?>