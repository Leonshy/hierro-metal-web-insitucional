<div>
    <div class="searchbar" style="margin-top:var(--spacing-6)">
        <label for="search-q">Buscar en el sitio</label>
        <input id="search-q" type="search" wire:model.live.debounce.400ms="query" autofocus>
        <button type="button" aria-label="Buscar en el sitio"><?php if (isset($component)) { $__componentOriginal60b104b2fde947186a9c15caab3ac427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60b104b2fde947186a9c15caab3ac427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.search','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60b104b2fde947186a9c15caab3ac427)): ?>
<?php $attributes = $__attributesOriginal60b104b2fde947186a9c15caab3ac427; ?>
<?php unset($__attributesOriginal60b104b2fde947186a9c15caab3ac427); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60b104b2fde947186a9c15caab3ac427)): ?>
<?php $component = $__componentOriginal60b104b2fde947186a9c15caab3ac427; ?>
<?php unset($__componentOriginal60b104b2fde947186a9c15caab3ac427); ?>
<?php endif; ?></button>
    </div>

    <div class="filter-tabs" role="group" aria-label="Filtrar por tipo de contenido">
        <button type="button" wire:click="setType('todo')" aria-pressed="<?php echo e($type === 'todo' ? 'true' : 'false'); ?>">Todo</button>
        <button type="button" wire:click="setType('page')" aria-pressed="<?php echo e($type === 'page' ? 'true' : 'false'); ?>">Páginas</button>
        <button type="button" wire:click="setType('post')" aria-pressed="<?php echo e($type === 'post' ? 'true' : 'false'); ?>">Noticias</button>
        <button type="button" wire:click="setType('document')" aria-pressed="<?php echo e($type === 'document' ? 'true' : 'false'); ?>">Documentos</button>
        <button type="button" wire:click="setType('announcement')" aria-pressed="<?php echo e($type === 'announcement' ? 'true' : 'false'); ?>">Comunicados</button>
    </div>

    <div wire:loading.delay class="demo-block" aria-hidden="true">
        <div class="skeleton w-60"></div>
        <div class="skeleton w-40"></div>
        <div class="skeleton w-90"></div>
    </div>

    <div wire:loading.remove.delay>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(mb_strlen(trim($query)) < 2): ?>
            <p class="result-count">Escriba al menos 2 caracteres para buscar.</p>
        <?php elseif($this->results->isEmpty()): ?>
            <?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['icon' => 'search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'search']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                No encontramos resultados para «<?php echo e($query); ?>». Pruebe con otra palabra.
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $attributes = $__attributesOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__attributesOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $component = $__componentOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__componentOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
        <?php else: ?>
            <p class="result-count"><?php echo e($this->results->count()); ?> <?php echo e($this->results->count() === 1 ? 'resultado' : 'resultados'); ?> para «<?php echo e($query); ?>»</p>
            <div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="result">
                        <span class="chip"><?php echo e($result->typeLabel); ?></span><br>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($result->url): ?>
                            <a href="<?php echo e($result->url); ?>"><?php echo e($result->title); ?></a>
                        <?php else: ?>
                            <span style="font-weight:700;font-size:18px"><?php echo e($result->title); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($result->excerpt): ?>
                            <p><?php echo e($result->excerpt); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/livewire/search-page.blade.php ENDPATH**/ ?>