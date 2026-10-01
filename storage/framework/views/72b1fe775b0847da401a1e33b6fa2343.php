<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => $page->effectiveSeoTitle().' — Colegio Dante Alighieri','description' => $page->getTranslation('seo_description', app()->getLocale()),'indexable' => $page->is_indexable,'canonical' => $page->canonical_url ?: null,'ogImage' => $page->seoImage?->conversionUrl('w1200') ?? $page->seoImage?->url() ?? $page->coverMedia?->conversionUrl('w1200') ?? $page->coverMedia?->url()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($page->effectiveSeoTitle().' — Colegio Dante Alighieri'),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($page->getTranslation('seo_description', app()->getLocale())),'indexable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($page->is_indexable),'canonical' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($page->canonical_url ?: null),'og-image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($page->seoImage?->conversionUrl('w1200') ?? $page->seoImage?->url() ?? $page->coverMedia?->conversionUrl('w1200') ?? $page->coverMedia?->url())]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php if (isset($component)) { $__componentOriginal360d002b1b676b6f84d43220f22129e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal360d002b1b676b6f84d43220f22129e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumbs','data' => ['items' => $breadcrumbs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($breadcrumbs)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal360d002b1b676b6f84d43220f22129e2)): ?>
<?php $attributes = $__attributesOriginal360d002b1b676b6f84d43220f22129e2; ?>
<?php unset($__attributesOriginal360d002b1b676b6f84d43220f22129e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal360d002b1b676b6f84d43220f22129e2)): ?>
<?php $component = $__componentOriginal360d002b1b676b6f84d43220f22129e2; ?>
<?php unset($__componentOriginal360d002b1b676b6f84d43220f22129e2); ?>
<?php endif; ?>
    <main id="contenido" tabindex="-1">
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($blocks[0]['type'] ?? null) !== 'hero'): ?>
            <div class="container page-title-block">
                <h1><?php echo e($page->title); ?></h1>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page->coverMedia): ?>
                <div class="container">
                    
                    <img src="<?php echo e($page->coverMedia->conversionUrl('w1200') ?? $page->coverMedia->url()); ?>"
                         <?php if($srcset = $page->coverMedia->srcset()): ?>
                             srcset="<?php echo e($srcset); ?>"
                             sizes="(min-width: 1024px) 800px, 100vw"
                         <?php endif; ?>
                         alt="<?php echo e($page->coverMedia->alt ?? ''); ?>" width="1200" height="675"
                         style="width:100%;height:auto;border-radius:var(--radius-md);margin-bottom:var(--spacing-6)"
                         loading="eager" fetchpriority="high">
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($siblings->isNotEmpty()): ?>
            <div class="container">
                <div class="layout-with-aside" style="margin-top:var(--spacing-6)">
                    <aside class="side-nav" aria-label="Páginas relacionadas">
                        <h2>En esta sección</h2>
                        <ul>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $siblings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sibling): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <li>
                                    <a href="<?php echo e(url('/'.$sibling->slug)); ?>" <?php if($sibling->is($page)): ?> aria-current="page" <?php endif; ?>>
                                        <?php echo e($sibling->title); ?>

                                    </a>
                                </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    </aside>
                    <div>
                        <?php if (isset($component)) { $__componentOriginal8ae5eef27a6593d408be483c502c3d89 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ae5eef27a6593d408be483c502c3d89 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-blocks','data' => ['blocks' => $blocks]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-blocks'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blocks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blocks)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ae5eef27a6593d408be483c502c3d89)): ?>
<?php $attributes = $__attributesOriginal8ae5eef27a6593d408be483c502c3d89; ?>
<?php unset($__attributesOriginal8ae5eef27a6593d408be483c502c3d89); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ae5eef27a6593d408be483c502c3d89)): ?>
<?php $component = $__componentOriginal8ae5eef27a6593d408be483c502c3d89; ?>
<?php unset($__componentOriginal8ae5eef27a6593d408be483c502c3d89); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginal8ae5eef27a6593d408be483c502c3d89 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ae5eef27a6593d408be483c502c3d89 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-blocks','data' => ['blocks' => $blocks]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-blocks'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blocks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blocks)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ae5eef27a6593d408be483c502c3d89)): ?>
<?php $attributes = $__attributesOriginal8ae5eef27a6593d408be483c502c3d89; ?>
<?php unset($__attributesOriginal8ae5eef27a6593d408be483c502c3d89); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ae5eef27a6593d408be483c502c3d89)): ?>
<?php $component = $__componentOriginal8ae5eef27a6593d408be483c502c3d89; ?>
<?php unset($__componentOriginal8ae5eef27a6593d408be483c502c3d89); ?>
<?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </main>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/pages/show.blade.php ENDPATH**/ ?>