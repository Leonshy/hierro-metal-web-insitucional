<section class="section reveal">
    <div class="container">
        <div class="section-head"><h2>Noticias del colegio</h2></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($posts->isEmpty()): ?>
            <?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['icon' => 'newspaper']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'newspaper']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Todavía no hay noticias publicadas. <?php echo $__env->renderComponent(); ?>
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
            <div class="cards-grid">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal99a2efcce29a424df335610c8d81d86a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99a2efcce29a424df335610c8d81d86a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card.news','data' => ['url' => route('posts.show', $post->slug),'title' => $post->title,'excerpt' => $post->excerpt,'category' => $post->category?->name,'date' => optional($post->published_at)->translatedFormat('d \d\e F \d\e Y'),'image' => $post->featuredMedia?->conversionUrl('medium') ?? $post->featuredMedia?->url(),'imageSrcset' => $post->featuredMedia?->srcset()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card.news'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('posts.show', $post->slug)),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->title),'excerpt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->excerpt),'category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->category?->name),'date' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(optional($post->published_at)->translatedFormat('d \d\e F \d\e Y')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->featuredMedia?->conversionUrl('medium') ?? $post->featuredMedia?->url()),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->featuredMedia?->srcset())]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99a2efcce29a424df335610c8d81d86a)): ?>
<?php $attributes = $__attributesOriginal99a2efcce29a424df335610c8d81d86a; ?>
<?php unset($__attributesOriginal99a2efcce29a424df335610c8d81d86a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99a2efcce29a424df335610c8d81d86a)): ?>
<?php $component = $__componentOriginal99a2efcce29a424df335610c8d81d86a; ?>
<?php unset($__componentOriginal99a2efcce29a424df335610c8d81d86a); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <p style="margin-top:var(--spacing-6)"><a class="btn btn-secondary" href="<?php echo e(route('posts.index')); ?>">Ver todas las noticias</a></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/home/sections/news.blade.php ENDPATH**/ ?>