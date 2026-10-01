<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enabled): ?>
    <div id="<?php echo e($nameFieldName); ?>_wrap" <?php if($withCsp): ?> @cspNonce <?php endif; ?> style="display: none" aria-hidden="true">
        <input id="<?php echo e($nameFieldName); ?>"
               name="<?php echo e($nameFieldName); ?>"
               type="text"
               value=""
               <?php if($livewireModel ?? false): ?> wire:model.defer="<?php echo e($livewireModel); ?>.<?php echo e($unrandomizedNameFieldName); ?>" <?php endif; ?>
               autocomplete="nope"
               tabindex="-1">
        <input name="<?php echo e($validFromFieldName); ?>"
               type="text"
               value="<?php echo e($encryptedValidFrom); ?>"
               <?php if($livewireModel ?? false): ?> wire:model.defer="<?php echo e($livewireModel); ?>.<?php echo e($validFromFieldName); ?>" <?php endif; ?>
               autocomplete="off"
               tabindex="-1">
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/vendor/honeypot/honeypotFormFields.blade.php ENDPATH**/ ?>