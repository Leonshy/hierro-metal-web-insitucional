<?php
    $contactEmail = \App\Models\SiteSetting::get('contact_email');
    $contactPhone = \App\Models\SiteSetting::get('contact_phone');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sitio en mantenimiento — Colegio Dante Alighieri</title>
    <?php echo app('Illuminate\Foundation\Vite')->fonts(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
</head>
<body class="maintenance-body">
    <main class="maintenance-page">
        <img src="<?php echo e(asset('images/logo-dante.svg')); ?>" alt="Colegio Dante Alighieri" width="211" height="90" class="maintenance-logo">
        <span class="maintenance-accent" aria-hidden="true"></span>
        <h1>El sitio está en mantenimiento</h1>
        <p class="body-lg">
            Estamos actualizando el contenido del sitio. Vuelva a intentarlo en unos minutos.
        </p>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactEmail || $contactPhone): ?>
            <p class="maintenance-contact">
                Si necesita comunicarse con el colegio mientras tanto:
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactEmail): ?>
                    <a href="mailto:<?php echo e($contactEmail); ?>"><?php echo e($contactEmail); ?></a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactEmail && $contactPhone): ?>
                    ·
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactPhone): ?>
                    <a href="tel:<?php echo e($contactPhone); ?>"><?php echo e($contactPhone); ?></a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </main>
</body>
</html>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/maintenance.blade.php ENDPATH**/ ?>