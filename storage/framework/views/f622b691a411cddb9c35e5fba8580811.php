<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Colegio Dante Alighieri — bilingüe español-italiano, Asunción','description' => 'Colegio bilingüe afiliado a la Società Dante Alighieri, con más de un siglo de historia y certificación internacional PLIDA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Colegio Dante Alighieri — bilingüe español-italiano, Asunción','description' => 'Colegio bilingüe afiliado a la Società Dante Alighieri, con más de un siglo de historia y certificación internacional PLIDA.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    
    <script type="application/ld+json"><?php echo json_encode([
        '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => config('sitio.seo.organization_name'),
        'url' => url('/'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => url('/buscar').'?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <main id="contenido" tabindex="-1">
        <?php if (isset($component)) { $__componentOriginale74ef38c4f718abe5610e24f5e2f3fa8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale74ef38c4f718abe5610e24f5e2f3fa8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.hero-slider','data' => ['slides' => $heroSlides]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('hero-slider'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['slides' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($heroSlides)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale74ef38c4f718abe5610e24f5e2f3fa8)): ?>
<?php $attributes = $__attributesOriginale74ef38c4f718abe5610e24f5e2f3fa8; ?>
<?php unset($__attributesOriginale74ef38c4f718abe5610e24f5e2f3fa8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale74ef38c4f718abe5610e24f5e2f3fa8)): ?>
<?php $component = $__componentOriginale74ef38c4f718abe5610e24f5e2f3fa8; ?>
<?php unset($__componentOriginale74ef38c4f718abe5610e24f5e2f3fa8); ?>
<?php endif; ?>

        <section class="section reveal">
            <div class="container">
                <div class="section-head">
                    <h2>Nuestra propuesta educativa</h2>
                    <p class="body-lg" style="max-width:720px;color:var(--color-neutral-700)">Instituto de Lengua y Cultura, Cursos de Italiano y certificación internacional PLIDA, obligatoria en ciertos grados.</p>
                </div>
                <div class="cards-grid">
                    <?php if (isset($component)) { $__componentOriginal2868af5daba95d636c4104f0e74e4dd1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2868af5daba95d636c4104f0e74e4dd1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card.section','data' => ['title' => 'Instituto de Lengua y Cultura','text' => 'Cursos de italiano para niños, jóvenes y adultos, dentro y fuera del colegio.','url' => ''.e(url('/oferta-educativa/instituto-de-lengua-y-cultura')).'','image' => $languageInstituteImage?->conversionUrl('medium') ?? $languageInstituteImage?->url(),'imageSrcset' => $languageInstituteImage?->srcset()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Instituto de Lengua y Cultura','text' => 'Cursos de italiano para niños, jóvenes y adultos, dentro y fuera del colegio.','url' => ''.e(url('/oferta-educativa/instituto-de-lengua-y-cultura')).'','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($languageInstituteImage?->conversionUrl('medium') ?? $languageInstituteImage?->url()),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($languageInstituteImage?->srcset())]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2868af5daba95d636c4104f0e74e4dd1)): ?>
<?php $attributes = $__attributesOriginal2868af5daba95d636c4104f0e74e4dd1; ?>
<?php unset($__attributesOriginal2868af5daba95d636c4104f0e74e4dd1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2868af5daba95d636c4104f0e74e4dd1)): ?>
<?php $component = $__componentOriginal2868af5daba95d636c4104f0e74e4dd1; ?>
<?php unset($__componentOriginal2868af5daba95d636c4104f0e74e4dd1); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal2868af5daba95d636c4104f0e74e4dd1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2868af5daba95d636c4104f0e74e4dd1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card.section','data' => ['title' => 'Cursos de Italiano','text' => 'Niveles y certificación PLIDA (Proyecto Lengua Italiana Dante Alighieri).','url' => ''.e(url('/oferta-educativa/cursos-de-italiano')).'','image' => $italianCoursesImage?->conversionUrl('medium') ?? $italianCoursesImage?->url(),'imageSrcset' => $italianCoursesImage?->srcset()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Cursos de Italiano','text' => 'Niveles y certificación PLIDA (Proyecto Lengua Italiana Dante Alighieri).','url' => ''.e(url('/oferta-educativa/cursos-de-italiano')).'','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($italianCoursesImage?->conversionUrl('medium') ?? $italianCoursesImage?->url()),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($italianCoursesImage?->srcset())]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2868af5daba95d636c4104f0e74e4dd1)): ?>
<?php $attributes = $__attributesOriginal2868af5daba95d636c4104f0e74e4dd1; ?>
<?php unset($__attributesOriginal2868af5daba95d636c4104f0e74e4dd1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2868af5daba95d636c4104f0e74e4dd1)): ?>
<?php $component = $__componentOriginal2868af5daba95d636c4104f0e74e4dd1; ?>
<?php unset($__componentOriginal2868af5daba95d636c4104f0e74e4dd1); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal2868af5daba95d636c4104f0e74e4dd1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2868af5daba95d636c4104f0e74e4dd1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card.section','data' => ['title' => 'Oferta educativa completa','text' => 'Educación bilingüe español-italiano, desde el nivel inicial hasta la certificación internacional.','url' => ''.e(url('/institucion/quienes-somos')).'','image' => $offeringImage?->conversionUrl('medium') ?? $offeringImage?->url(),'imageSrcset' => $offeringImage?->srcset()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Oferta educativa completa','text' => 'Educación bilingüe español-italiano, desde el nivel inicial hasta la certificación internacional.','url' => ''.e(url('/institucion/quienes-somos')).'','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($offeringImage?->conversionUrl('medium') ?? $offeringImage?->url()),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($offeringImage?->srcset())]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2868af5daba95d636c4104f0e74e4dd1)): ?>
<?php $attributes = $__attributesOriginal2868af5daba95d636c4104f0e74e4dd1; ?>
<?php unset($__attributesOriginal2868af5daba95d636c4104f0e74e4dd1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2868af5daba95d636c4104f0e74e4dd1)): ?>
<?php $component = $__componentOriginal2868af5daba95d636c4104f0e74e4dd1; ?>
<?php unset($__componentOriginal2868af5daba95d636c4104f0e74e4dd1); ?>
<?php endif; ?>
                </div>
            </div>
        </section>

        <?php echo $__env->make('home.sections.stats', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $enabledSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php if($section === 'stats'): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php continue; ?><?php endif; ?>
            <?php echo $__env->make('home.sections.'.str_replace('_', '-', $section), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <section class="section reveal">
            <div class="container">
                <div class="cta-block">
                    <h2><?php echo e($homeSettings->cta_title ?? '¿Quiere conocer el colegio?'); ?></h2>
                    <p><?php echo e($homeSettings->cta_text ?? 'Complete la pre-inscripción y lo contactamos.'); ?></p>
                    <a class="btn btn-primary" href="<?php echo e($homeSettings->cta_button_url ?? url('/admisiones')); ?>"><?php echo e($homeSettings->cta_button_label ?? 'Quiero inscribir a mi hijo/a'); ?></a>
                </div>
            </div>
        </section>
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
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/home.blade.php ENDPATH**/ ?>