<?php
    $integrations = \App\Models\IntegrationSetting::current();
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($integrations->turnstileActive()): ?>
    <div class="cf-turnstile" data-sitekey="<?php echo e($integrations->turnstile_site_key); ?>" data-theme="light"></div>
    <?php if (! $__env->hasRenderedOnce('a45bfeb0-a01b-4876-a792-5db5433ebeec')): $__env->markAsRenderedOnce('a45bfeb0-a01b-4876-a792-5db5433ebeec'); ?>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/turnstile-widget.blade.php ENDPATH**/ ?>