<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['blocks']));

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

foreach (array_filter((['blocks']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?> 
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $blocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($block['type']):
        case ('hero'): ?>
            <?php if (isset($component)) { $__componentOriginal2c53295d5adb7735a8be84b3ef108ed2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2c53295d5adb7735a8be84b3ef108ed2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blocks.hero','data' => ['data' => $block['data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blocks.hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block['data'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2c53295d5adb7735a8be84b3ef108ed2)): ?>
<?php $attributes = $__attributesOriginal2c53295d5adb7735a8be84b3ef108ed2; ?>
<?php unset($__attributesOriginal2c53295d5adb7735a8be84b3ef108ed2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2c53295d5adb7735a8be84b3ef108ed2)): ?>
<?php $component = $__componentOriginal2c53295d5adb7735a8be84b3ef108ed2; ?>
<?php unset($__componentOriginal2c53295d5adb7735a8be84b3ef108ed2); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
        <?php case ('texto'): ?>
            <?php if (isset($component)) { $__componentOriginal6fba765789d025bc8fe7e600966a237d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6fba765789d025bc8fe7e600966a237d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blocks.texto','data' => ['data' => $block['data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blocks.texto'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block['data'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6fba765789d025bc8fe7e600966a237d)): ?>
<?php $attributes = $__attributesOriginal6fba765789d025bc8fe7e600966a237d; ?>
<?php unset($__attributesOriginal6fba765789d025bc8fe7e600966a237d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6fba765789d025bc8fe7e600966a237d)): ?>
<?php $component = $__componentOriginal6fba765789d025bc8fe7e600966a237d; ?>
<?php unset($__componentOriginal6fba765789d025bc8fe7e600966a237d); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
        <?php case ('imagen_texto'): ?>
            <?php if (isset($component)) { $__componentOriginalafeb8a819b72e0707aeaf6fa7b09dab7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalafeb8a819b72e0707aeaf6fa7b09dab7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blocks.imagen-texto','data' => ['data' => $block['data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blocks.imagen-texto'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block['data'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalafeb8a819b72e0707aeaf6fa7b09dab7)): ?>
<?php $attributes = $__attributesOriginalafeb8a819b72e0707aeaf6fa7b09dab7; ?>
<?php unset($__attributesOriginalafeb8a819b72e0707aeaf6fa7b09dab7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalafeb8a819b72e0707aeaf6fa7b09dab7)): ?>
<?php $component = $__componentOriginalafeb8a819b72e0707aeaf6fa7b09dab7; ?>
<?php unset($__componentOriginalafeb8a819b72e0707aeaf6fa7b09dab7); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
        <?php case ('tarjetas'): ?>
            <?php if (isset($component)) { $__componentOriginal3b6dc231b06d4e74318807d1650f5faf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b6dc231b06d4e74318807d1650f5faf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blocks.tarjetas','data' => ['data' => $block['data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blocks.tarjetas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block['data'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b6dc231b06d4e74318807d1650f5faf)): ?>
<?php $attributes = $__attributesOriginal3b6dc231b06d4e74318807d1650f5faf; ?>
<?php unset($__attributesOriginal3b6dc231b06d4e74318807d1650f5faf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b6dc231b06d4e74318807d1650f5faf)): ?>
<?php $component = $__componentOriginal3b6dc231b06d4e74318807d1650f5faf; ?>
<?php unset($__componentOriginal3b6dc231b06d4e74318807d1650f5faf); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
        <?php case ('cta'): ?>
            <?php if (isset($component)) { $__componentOriginal10fdda506a9200187a95fe540e75f8db = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal10fdda506a9200187a95fe540e75f8db = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blocks.cta','data' => ['data' => $block['data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blocks.cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block['data'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal10fdda506a9200187a95fe540e75f8db)): ?>
<?php $attributes = $__attributesOriginal10fdda506a9200187a95fe540e75f8db; ?>
<?php unset($__attributesOriginal10fdda506a9200187a95fe540e75f8db); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal10fdda506a9200187a95fe540e75f8db)): ?>
<?php $component = $__componentOriginal10fdda506a9200187a95fe540e75f8db; ?>
<?php unset($__componentOriginal10fdda506a9200187a95fe540e75f8db); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
        <?php case ('cifras'): ?>
            <?php if (isset($component)) { $__componentOriginal9ce424ed5c3652911177b733f1c49e33 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ce424ed5c3652911177b733f1c49e33 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blocks.cifras','data' => ['data' => $block['data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blocks.cifras'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block['data'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ce424ed5c3652911177b733f1c49e33)): ?>
<?php $attributes = $__attributesOriginal9ce424ed5c3652911177b733f1c49e33; ?>
<?php unset($__attributesOriginal9ce424ed5c3652911177b733f1c49e33); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ce424ed5c3652911177b733f1c49e33)): ?>
<?php $component = $__componentOriginal9ce424ed5c3652911177b733f1c49e33; ?>
<?php unset($__componentOriginal9ce424ed5c3652911177b733f1c49e33); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
        <?php case ('faq'): ?>
            <?php if (isset($component)) { $__componentOriginal64596914d7163a4bbd3912f8ee3b845d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal64596914d7163a4bbd3912f8ee3b845d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blocks.faq','data' => ['data' => $block['data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blocks.faq'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block['data'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal64596914d7163a4bbd3912f8ee3b845d)): ?>
<?php $attributes = $__attributesOriginal64596914d7163a4bbd3912f8ee3b845d; ?>
<?php unset($__attributesOriginal64596914d7163a4bbd3912f8ee3b845d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal64596914d7163a4bbd3912f8ee3b845d)): ?>
<?php $component = $__componentOriginal64596914d7163a4bbd3912f8ee3b845d; ?>
<?php unset($__componentOriginal64596914d7163a4bbd3912f8ee3b845d); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
        <?php case ('video'): ?>
            <?php if (isset($component)) { $__componentOriginalba1b418f0521c79a05b9690ae7aa9317 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalba1b418f0521c79a05b9690ae7aa9317 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blocks.video','data' => ['data' => $block['data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blocks.video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block['data'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalba1b418f0521c79a05b9690ae7aa9317)): ?>
<?php $attributes = $__attributesOriginalba1b418f0521c79a05b9690ae7aa9317; ?>
<?php unset($__attributesOriginalba1b418f0521c79a05b9690ae7aa9317); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalba1b418f0521c79a05b9690ae7aa9317)): ?>
<?php $component = $__componentOriginalba1b418f0521c79a05b9690ae7aa9317; ?>
<?php unset($__componentOriginalba1b418f0521c79a05b9690ae7aa9317); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
        <?php case ('mapa'): ?>
            <?php if (isset($component)) { $__componentOriginal36bbed59c4dd98223cc80f178faa334a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal36bbed59c4dd98223cc80f178faa334a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.blocks.mapa','data' => ['data' => $block['data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('blocks.mapa'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block['data'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal36bbed59c4dd98223cc80f178faa334a)): ?>
<?php $attributes = $__attributesOriginal36bbed59c4dd98223cc80f178faa334a; ?>
<?php unset($__attributesOriginal36bbed59c4dd98223cc80f178faa334a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal36bbed59c4dd98223cc80f178faa334a)): ?>
<?php $component = $__componentOriginal36bbed59c4dd98223cc80f178faa334a; ?>
<?php unset($__componentOriginal36bbed59c4dd98223cc80f178faa334a); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
    <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/page-blocks.blade.php ENDPATH**/ ?>