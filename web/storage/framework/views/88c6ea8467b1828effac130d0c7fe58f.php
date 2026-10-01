<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'description' => null,
    'indexable' => true,
    'canonical' => null,
    'ogType' => 'website',
    'ogImage' => null,
    // Fase 7 (rendimiento, docs/09-rendimiento.md §5): el runtime de Livewire
    // (~126 KB comprimidos, medido en la línea base) solo lo usa de verdad
    // el buscador interno (`App\Livewire\SearchPage`) — home, páginas
    // institucionales y noticias no tienen ni un solo `wire:` ni
    // `@livewire()`. Cargarlo en todas las plantillas competía por ancho de
    // banda con la imagen del LCP bajo 4G simulada sin ningún beneficio en
    // esas páginas. Por defecto no se incluye; la vista que sí lo necesita
    // pasa `:livewire="true"`.
    'livewire' => false,
]));

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

foreach (array_filter(([
    'title' => null,
    'description' => null,
    'indexable' => true,
    'canonical' => null,
    'ogType' => 'website',
    'ogImage' => null,
    // Fase 7 (rendimiento, docs/09-rendimiento.md §5): el runtime de Livewire
    // (~126 KB comprimidos, medido en la línea base) solo lo usa de verdad
    // el buscador interno (`App\Livewire\SearchPage`) — home, páginas
    // institucionales y noticias no tienen ni un solo `wire:` ni
    // `@livewire()`. Cargarlo en todas las plantillas competía por ancho de
    // banda con la imagen del LCP bajo 4G simulada sin ningún beneficio en
    // esas páginas. Por defecto no se incluye; la vista que sí lo necesita
    // pasa `:livewire="true"`.
    'livewire' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $resolvedTitle = $title ?? 'Hierro Metal S.R.L.';
    $resolvedDescription = $description ?? 'Colegio bilingüe español-italiano en Asunción, afiliado a la Società Dante Alighieri.';
    $resolvedCanonical = $canonical ?: url()->current();
    // "staging"/"local" bloquean indexación siempre, sin importar el toggle de
    // la página — así el robots.txt/meta robots nunca dependen de que alguien
    // se acuerde de "sacar" el bloqueo a mano antes de salir a producción
    // (CLAUDE.md, docs/08-seo.md §4).
    $blockedByEnvironment = (bool) config('sitio.seo.block_indexing');
    $resolvedIndexable = ($indexable ?? true) && ! $blockedByEnvironment;
    $resolvedOgImage = $ogImage ?: config('sitio.seo.default_og_image');
    $siteName = \App\Models\SiteSetting::get('site_name', 'Hierro Metal S.R.L.');
    // Los interruptores de activo/inactivo de cada integración viven acá:
    // si están apagados desde el panel, el atributo sale vacío y
    // resources/js/consent.js no carga el script, aunque el ID siga guardado.
    $integrations = \App\Models\IntegrationSetting::current();
?>
<!DOCTYPE html>
<html
    lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"
    data-gtm-id="<?php echo e($integrations->googleTagManagerActive() ? $integrations->google_tag_manager_id : ''); ?>"
    data-meta-pixel-id="<?php echo e($integrations->metaPixelActive() ? $integrations->meta_pixel_id : ''); ?>"
>
<head>
    
    <script>document.documentElement.classList.add('js')</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($resolvedTitle); ?></title>
    <meta name="description" content="<?php echo e($resolvedDescription); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$resolvedIndexable): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <link rel="canonical" href="<?php echo e($resolvedCanonical); ?>">

    
    <meta property="og:title" content="<?php echo e($resolvedTitle); ?>">
    <meta property="og:description" content="<?php echo e($resolvedDescription); ?>">
    <meta property="og:type" content="<?php echo e($ogType); ?>">
    <meta property="og:url" content="<?php echo e($resolvedCanonical); ?>">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">
    <meta property="og:locale" content="<?php echo e(app()->getLocale() === 'it' ? 'it_IT' : 'es_PY'); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($resolvedOgImage): ?>
        <meta property="og:image" content="<?php echo e($resolvedOgImage); ?>">
        <meta name="twitter:card" content="summary_large_image">
    <?php else: ?>
        <meta name="twitter:card" content="summary">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal8a0e7b5344e13d5b4deb77912d07987e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a0e7b5344e13d5b4deb77912d07987e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema.organization','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema.organization'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a0e7b5344e13d5b4deb77912d07987e)): ?>
<?php $attributes = $__attributesOriginal8a0e7b5344e13d5b4deb77912d07987e; ?>
<?php unset($__attributesOriginal8a0e7b5344e13d5b4deb77912d07987e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a0e7b5344e13d5b4deb77912d07987e)): ?>
<?php $component = $__componentOriginal8a0e7b5344e13d5b4deb77912d07987e; ?>
<?php unset($__componentOriginal8a0e7b5344e13d5b4deb77912d07987e); ?>
<?php endif; ?>

    
    <?php echo app('Illuminate\Foundation\Vite')->fonts(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livewire): ?>
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</head>
<body>
<a class="skip-link" href="#contenido">Saltar al contenido principal</a>


<?php if (isset($component)) { $__componentOriginal929715dcacade4e957f0bc5aff0c8a6d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal929715dcacade4e957f0bc5aff0c8a6d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cookie-consent','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cookie-consent'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal929715dcacade4e957f0bc5aff0c8a6d)): ?>
<?php $attributes = $__attributesOriginal929715dcacade4e957f0bc5aff0c8a6d; ?>
<?php unset($__attributesOriginal929715dcacade4e957f0bc5aff0c8a6d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal929715dcacade4e957f0bc5aff0c8a6d)): ?>
<?php $component = $__componentOriginal929715dcacade4e957f0bc5aff0c8a6d; ?>
<?php unset($__componentOriginal929715dcacade4e957f0bc5aff0c8a6d); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal52d409fc1fa687ab461fb439e195b906 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52d409fc1fa687ab461fb439e195b906 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.whatsapp-float','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('whatsapp-float'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52d409fc1fa687ab461fb439e195b906)): ?>
<?php $attributes = $__attributesOriginal52d409fc1fa687ab461fb439e195b906; ?>
<?php unset($__attributesOriginal52d409fc1fa687ab461fb439e195b906); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52d409fc1fa687ab461fb439e195b906)): ?>
<?php $component = $__componentOriginal52d409fc1fa687ab461fb439e195b906; ?>
<?php unset($__componentOriginal52d409fc1fa687ab461fb439e195b906); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginalfdc8967a87956c0a7185abbef03fae20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfdc8967a87956c0a7185abbef03fae20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfdc8967a87956c0a7185abbef03fae20)): ?>
<?php $attributes = $__attributesOriginalfdc8967a87956c0a7185abbef03fae20; ?>
<?php unset($__attributesOriginalfdc8967a87956c0a7185abbef03fae20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfdc8967a87956c0a7185abbef03fae20)): ?>
<?php $component = $__componentOriginalfdc8967a87956c0a7185abbef03fae20; ?>
<?php unset($__componentOriginalfdc8967a87956c0a7185abbef03fae20); ?>
<?php endif; ?>

<?php echo e($slot); ?>


<?php if (isset($component)) { $__componentOriginal222c87a019257fb1d70ae0ff46ab02e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal222c87a019257fb1d70ae0ff46ab02e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal222c87a019257fb1d70ae0ff46ab02e1)): ?>
<?php $attributes = $__attributesOriginal222c87a019257fb1d70ae0ff46ab02e1; ?>
<?php unset($__attributesOriginal222c87a019257fb1d70ae0ff46ab02e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal222c87a019257fb1d70ae0ff46ab02e1)): ?>
<?php $component = $__componentOriginal222c87a019257fb1d70ae0ff46ab02e1; ?>
<?php unset($__componentOriginal222c87a019257fb1d70ae0ff46ab02e1); ?>
<?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livewire): ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('meta_event_id')): ?>
    
    <script>window.sitioQueuedLeadEventId = <?php echo json_encode(session('meta_event_id'), 15, 512) ?>;</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/layouts/app.blade.php ENDPATH**/ ?>