<?php
    $integrations = \App\Models\IntegrationSetting::current();
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($integrations->turnstileActive()): ?>
    <div class="cf-turnstile" data-sitekey="<?php echo e($integrations->turnstile_site_key); ?>" data-theme="light"></div>
    <?php if (! $__env->hasRenderedOnce('4998e412-1132-4397-bbde-2b63eeb32e34')): $__env->markAsRenderedOnce('4998e412-1132-4397-bbde-2b63eeb32e34'); ?>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/turnstile-widget.blade.php ENDPATH**/ ?>