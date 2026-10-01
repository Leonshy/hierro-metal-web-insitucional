<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['data']));

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

foreach (array_filter((['data']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $items = collect($data['items'] ?? [])->map(fn ($i) => ['question' => $i['question'] ?? '', 'answer' => $i['answer'] ?? ''])->all();
    // JSON-LD FAQPage (docs/08-seo.md §3) — solo si hay preguntas con
    // respuesta real, evita advertencias del validador con bloques vacíos.
    $faqSchema = collect($items)
        ->filter(fn ($item) => trim((string) $item['question']) !== '' && trim((string) strip_tags($item['answer'])) !== '')
        ->map(fn ($item) => [
            '@type' => 'Question',
            'name' => strip_tags($item['question']),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags($item['answer']),
            ],
        ])
        ->values();
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($faqSchema->isNotEmpty()): ?>
    <script type="application/ld+json"><?php echo json_encode([
        '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqSchema->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<div class="section">
    <div class="container" style="max-width:760px">
        <?php if (isset($component)) { $__componentOriginalf37c7fa867bbb37ca7b59380c8fa1d1e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf37c7fa867bbb37ca7b59380c8fa1d1e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion','data' => ['items' => $items]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($items)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf37c7fa867bbb37ca7b59380c8fa1d1e)): ?>
<?php $attributes = $__attributesOriginalf37c7fa867bbb37ca7b59380c8fa1d1e; ?>
<?php unset($__attributesOriginalf37c7fa867bbb37ca7b59380c8fa1d1e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf37c7fa867bbb37ca7b59380c8fa1d1e)): ?>
<?php $component = $__componentOriginalf37c7fa867bbb37ca7b59380c8fa1d1e; ?>
<?php unset($__componentOriginalf37c7fa867bbb37ca7b59380c8fa1d1e); ?>
<?php endif; ?>
    </div>
</div>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/blocks/faq.blade.php ENDPATH**/ ?>