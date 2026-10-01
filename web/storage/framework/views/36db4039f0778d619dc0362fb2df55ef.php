<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => $post->title.' — Colegio Dante Alighieri','description' => $post->excerpt,'indexable' => $post->is_indexable,'ogType' => 'article','ogImage' => $post->featuredMedia?->conversionUrl('w1200') ?? $post->featuredMedia?->url()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->title.' — Colegio Dante Alighieri'),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->excerpt),'indexable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->is_indexable),'og-type' => 'article','og-image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->featuredMedia?->conversionUrl('w1200') ?? $post->featuredMedia?->url())]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php if (isset($component)) { $__componentOriginal360d002b1b676b6f84d43220f22129e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal360d002b1b676b6f84d43220f22129e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumbs','data' => ['items' => [['label' => 'Noticias', 'url' => route('posts.index')], ['label' => $post->title, 'url' => null]]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Noticias', 'url' => route('posts.index')], ['label' => $post->title, 'url' => null]])]); ?>
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
    <?php
        // JSON-LD NewsArticle (docs/08-seo.md §3).
        $articleSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'datePublished' => optional($post->published_at)->toIso8601String(),
            'dateModified' => optional($post->updated_at)->toIso8601String(),
            'image' => $post->featuredMedia?->conversionUrl('w1200') ?? $post->featuredMedia?->url(),
            'mainEntityOfPage' => route('posts.show', $post->slug),
            'publisher' => [
                '@type' => 'EducationalOrganization',
                'name' => config('sitio.seo.organization_name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo-dante.svg'),
                ],
            ],
        ]);
    ?>
    <script type="application/ld+json"><?php echo json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <main id="contenido" tabindex="-1" class="container section">
        
        <div class="article-layout">
            <article class="article-content">
                <h1><?php echo e($post->title); ?></h1>
                <div class="article-meta">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->category): ?>
                        <span class="chip"><?php echo e($post->category->name); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <span>Publicado el <?php echo e(optional($post->published_at)->translatedFormat('d \d\e F \d\e Y')); ?></span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($media = $post->featuredMedia): ?>
                    <div class="article-media">
                        
                        <img
                            src="<?php echo e($media->conversionUrl('large') ?? $media->url()); ?>"
                            <?php if($srcset = $media->srcset()): ?>
                                srcset="<?php echo e($srcset); ?>"
                                sizes="(min-width: 1024px) 800px, 100vw"
                            <?php endif; ?>
                            alt="<?php echo e($media->alt ?? ''); ?>" width="1200" height="675"
                            loading="eager" fetchpriority="high"
                        >
                    </div>
                <?php else: ?>
                    <div class="article-media" role="img" aria-label="Fotografía de la noticia pendiente de carga">
                        <span class="seal-xl" aria-hidden="true"></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="body"><?php echo $post->content; ?></div>

                <?php if (isset($component)) { $__componentOriginal2d462c81f3138fe0c9e670d3393a58ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2d462c81f3138fe0c9e670d3393a58ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.share-links','data' => ['title' => $post->title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('share-links'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->title)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2d462c81f3138fe0c9e670d3393a58ce)): ?>
<?php $attributes = $__attributesOriginal2d462c81f3138fe0c9e670d3393a58ce; ?>
<?php unset($__attributesOriginal2d462c81f3138fe0c9e670d3393a58ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2d462c81f3138fe0c9e670d3393a58ce)): ?>
<?php $component = $__componentOriginal2d462c81f3138fe0c9e670d3393a58ce; ?>
<?php unset($__componentOriginal2d462c81f3138fe0c9e670d3393a58ce); ?>
<?php endif; ?>

                <p style="margin-top:var(--spacing-6)"><a class="btn-link" href="<?php echo e(route('posts.index')); ?>">← Volver a Noticias</a></p>
            </article>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($related->isNotEmpty()): ?>
                <aside class="related-sidebar" aria-label="Noticias relacionadas">
                    <h2>Noticias relacionadas</h2>
                    <div class="related-grid">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal99a2efcce29a424df335610c8d81d86a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99a2efcce29a424df335610c8d81d86a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card.news','data' => ['url' => route('posts.show', $item->slug),'title' => $item->title,'category' => $item->category?->name,'date' => optional($item->published_at)->translatedFormat('d \d\e F \d\e Y'),'image' => $item->featuredMedia?->conversionUrl('medium') ?? $item->featuredMedia?->url(),'imageSrcset' => $item->featuredMedia?->srcset()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card.news'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('posts.show', $item->slug)),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->title),'category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->category?->name),'date' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(optional($item->published_at)->translatedFormat('d \d\e F \d\e Y')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->featuredMedia?->conversionUrl('medium') ?? $item->featuredMedia?->url()),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->featuredMedia?->srcset())]); ?>
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
                </aside>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
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
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/posts/show.blade.php ENDPATH**/ ?>