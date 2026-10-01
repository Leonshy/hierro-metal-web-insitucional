<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'url' => null]));

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

foreach (array_filter((['title', 'url' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    // Enlaces de intención de compartir (share intent), sin SDK de terceros
    // ni pixel de tracking — nada que cargar hasta que la persona haga clic
    // en un enlace real hacia la red social.
    $shareUrl = $url ?? request()->url();
    $encodedTitle = rawurlencode($title);
    $encodedUrl = rawurlencode($shareUrl);
?>
<div class="share-links" role="group" aria-label="Compartir esta noticia">
    <span class="share-links-label">Compartir</span>
    <a class="btn btn-secondary share-link" href="https://wa.me/?text=<?php echo e($encodedTitle); ?>%20<?php echo e($encodedUrl); ?>" target="_blank" rel="noopener noreferrer">
        <?php if (isset($component)) { $__componentOriginal934a6fed68095f5c15b2a798e8efa6f7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal934a6fed68095f5c15b2a798e8efa6f7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.whatsapp','data' => ['size' => '18']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.whatsapp'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => '18']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal934a6fed68095f5c15b2a798e8efa6f7)): ?>
<?php $attributes = $__attributesOriginal934a6fed68095f5c15b2a798e8efa6f7; ?>
<?php unset($__attributesOriginal934a6fed68095f5c15b2a798e8efa6f7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal934a6fed68095f5c15b2a798e8efa6f7)): ?>
<?php $component = $__componentOriginal934a6fed68095f5c15b2a798e8efa6f7; ?>
<?php unset($__componentOriginal934a6fed68095f5c15b2a798e8efa6f7); ?>
<?php endif; ?> WhatsApp
    </a>
    <a class="btn btn-secondary share-link" href="https://twitter.com/intent/tweet?text=<?php echo e($encodedTitle); ?>&url=<?php echo e($encodedUrl); ?>" target="_blank" rel="noopener noreferrer">
        <?php if (isset($component)) { $__componentOriginal3c95d8bd39ce9f1060c99d6827415e70 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c95d8bd39ce9f1060c99d6827415e70 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.x-twitter','data' => ['size' => '18']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.x-twitter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => '18']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c95d8bd39ce9f1060c99d6827415e70)): ?>
<?php $attributes = $__attributesOriginal3c95d8bd39ce9f1060c99d6827415e70; ?>
<?php unset($__attributesOriginal3c95d8bd39ce9f1060c99d6827415e70); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c95d8bd39ce9f1060c99d6827415e70)): ?>
<?php $component = $__componentOriginal3c95d8bd39ce9f1060c99d6827415e70; ?>
<?php unset($__componentOriginal3c95d8bd39ce9f1060c99d6827415e70); ?>
<?php endif; ?> X
    </a>
    <a class="btn btn-secondary share-link" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e($encodedUrl); ?>" target="_blank" rel="noopener noreferrer">
        <?php if (isset($component)) { $__componentOriginalbddf0dc1bd3254a1e4f24175436383a6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbddf0dc1bd3254a1e4f24175436383a6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.facebook','data' => ['size' => '18']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.facebook'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => '18']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbddf0dc1bd3254a1e4f24175436383a6)): ?>
<?php $attributes = $__attributesOriginalbddf0dc1bd3254a1e4f24175436383a6; ?>
<?php unset($__attributesOriginalbddf0dc1bd3254a1e4f24175436383a6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbddf0dc1bd3254a1e4f24175436383a6)): ?>
<?php $component = $__componentOriginalbddf0dc1bd3254a1e4f24175436383a6; ?>
<?php unset($__componentOriginalbddf0dc1bd3254a1e4f24175436383a6); ?>
<?php endif; ?> Facebook
    </a>
</div>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/share-links.blade.php ENDPATH**/ ?>