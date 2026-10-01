<?php
    $primaryNav = \App\Models\Menu::renderTree('primary');
    $italianEnabled = \App\Models\SiteSetting::italianEnabled();
?>
<div
    x-data="{
        mobileOpen: false,
        scrolled: false,
        open() {
            this.mobileOpen = true;
            this.$nextTick(() => this.$refs.mobileNav.querySelector('a, button')?.focus());
        },
        close() {
            this.mobileOpen = false;
            this.$refs.mobileToggle?.focus();
        },
        trapTab(event) {
            const focusables = this.$refs.mobileNav.querySelectorAll('a, button');
            if (!focusables.length) return;
            const first = focusables[0];
            const last = focusables[focusables.length - 1];
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        },
    }"
    @scroll.window="scrolled = window.scrollY > 80"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($italianEnabled): ?>
        <div class="util-bar">
            <div class="container util-bar-inner">
                <div class="lang-toggle" role="group" aria-label="Cambiar idioma del sitio">
                    <a href="<?php echo e(route('locale.switch', 'es')); ?>" aria-current="<?php echo e(app()->getLocale() === 'es' ? 'true' : 'false'); ?>">ES</a>
                    <a href="<?php echo e(route('locale.switch', 'it')); ?>" aria-current="<?php echo e(app()->getLocale() === 'it' ? 'true' : 'false'); ?>">IT</a>
                </div>
                <a class="icon-btn" href="<?php echo e(route('search.index')); ?>" aria-label="Buscar en el sitio">
                    <?php if (isset($component)) { $__componentOriginal60b104b2fde947186a9c15caab3ac427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60b104b2fde947186a9c15caab3ac427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.search','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60b104b2fde947186a9c15caab3ac427)): ?>
<?php $attributes = $__attributesOriginal60b104b2fde947186a9c15caab3ac427; ?>
<?php unset($__attributesOriginal60b104b2fde947186a9c15caab3ac427); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60b104b2fde947186a9c15caab3ac427)): ?>
<?php $component = $__componentOriginal60b104b2fde947186a9c15caab3ac427; ?>
<?php unset($__componentOriginal60b104b2fde947186a9c15caab3ac427); ?>
<?php endif; ?>
                </a>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <header class="site-header" :class="{ 'is-scrolled': scrolled }">
        <div class="container site-header-inner">
            <a class="logo" href="<?php echo e(url('/')); ?>">
                <img src="<?php echo e(asset('images/logo-dante.svg')); ?>" alt="Colegio Dante Alighieri" width="211" height="90" class="logo-mark">
            </a>

            <nav aria-label="Principal">
                <ul class="desktop-nav">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $primaryNav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['linkable'] ?? true): ?>
                                <a href="<?php echo e(url($item['url'])); ?>" <?php if(request()->is(ltrim($item['url'], '/')) || request()->is(ltrim($item['url'], '/').'/*')): ?> aria-current="page" <?php endif; ?>>
                                    <?php echo e($item['label']); ?>

                                </a>
                            <?php else: ?>
                                
                                <button type="button" class="nav-parent-toggle" aria-haspopup="true"><?php echo e($item['label']); ?></button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['children'])): ?>
                                <ul class="submenu">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $item['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <li><a href="<?php echo e(url($child['url'])); ?>"><?php echo e($child['label']); ?></a></li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </ul>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </nav>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($italianEnabled)): ?>
                <a class="icon-btn" href="<?php echo e(route('search.index')); ?>" aria-label="Buscar en el sitio" style="margin-left:auto">
                    <?php if (isset($component)) { $__componentOriginal60b104b2fde947186a9c15caab3ac427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60b104b2fde947186a9c15caab3ac427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.search','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60b104b2fde947186a9c15caab3ac427)): ?>
<?php $attributes = $__attributesOriginal60b104b2fde947186a9c15caab3ac427; ?>
<?php unset($__attributesOriginal60b104b2fde947186a9c15caab3ac427); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60b104b2fde947186a9c15caab3ac427)): ?>
<?php $component = $__componentOriginal60b104b2fde947186a9c15caab3ac427; ?>
<?php unset($__componentOriginal60b104b2fde947186a9c15caab3ac427); ?>
<?php endif; ?>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <button type="button" class="icon-btn mobile-nav-toggle" aria-label="Abrir menú de navegación"
                    x-ref="mobileToggle"
                    :aria-expanded="mobileOpen.toString()" aria-controls="mobile-nav"
                    @click="open()">
                <?php if (isset($component)) { $__componentOriginal6e9941d9b2150b5843452baf6c4b1d05 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6e9941d9b2150b5843452baf6c4b1d05 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6e9941d9b2150b5843452baf6c4b1d05)): ?>
<?php $attributes = $__attributesOriginal6e9941d9b2150b5843452baf6c4b1d05; ?>
<?php unset($__attributesOriginal6e9941d9b2150b5843452baf6c4b1d05); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6e9941d9b2150b5843452baf6c4b1d05)): ?>
<?php $component = $__componentOriginal6e9941d9b2150b5843452baf6c4b1d05; ?>
<?php unset($__componentOriginal6e9941d9b2150b5843452baf6c4b1d05); ?>
<?php endif; ?>
            </button>
        </div>
    </header>

    
    <nav id="mobile-nav" class="mobile-nav" :class="{ 'is-open': mobileOpen }" aria-label="Principal"
         x-ref="mobileNav"
         :inert="!mobileOpen"
         @keydown.escape.window="close()"
         @keydown.tab="trapTab($event)">
        <div class="mobile-nav-header">
            <strong>DANTE</strong>
            <button type="button" class="icon-btn" aria-label="Cerrar menú de navegación" @click="close()">
                <?php if (isset($component)) { $__componentOriginalef2fdc0184b79387088ad139caabd0f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef2fdc0184b79387088ad139caabd0f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.close','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.close'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef2fdc0184b79387088ad139caabd0f5)): ?>
<?php $attributes = $__attributesOriginalef2fdc0184b79387088ad139caabd0f5; ?>
<?php unset($__attributesOriginalef2fdc0184b79387088ad139caabd0f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef2fdc0184b79387088ad139caabd0f5)): ?>
<?php $component = $__componentOriginalef2fdc0184b79387088ad139caabd0f5; ?>
<?php unset($__componentOriginalef2fdc0184b79387088ad139caabd0f5); ?>
<?php endif; ?>
            </button>
        </div>
        <ul class="mobile-nav-list">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $primaryNav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($item['children'])): ?>
                    <li><a href="<?php echo e(url($item['url'])); ?>"><?php echo e($item['label']); ?></a></li>
                <?php else: ?>
                    <li x-data="{ open: false }">
                        <button type="button" class="mobile-submenu-toggle" :aria-expanded="open.toString()"
                                aria-controls="mobile-submenu-<?php echo e($index); ?>" @click="open = !open">
                            <?php echo e($item['label']); ?>

                            <span class="chev" aria-hidden="true">
                                <?php if (isset($component)) { $__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.chevron-down','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa)): ?>
<?php $attributes = $__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa; ?>
<?php unset($__attributesOriginalaf73b0b04d41c4be26aea89fbf545dfa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa)): ?>
<?php $component = $__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa; ?>
<?php unset($__componentOriginalaf73b0b04d41c4be26aea89fbf545dfa); ?>
<?php endif; ?>
                            </span>
                        </button>
                        <ul class="mobile-submenu" :class="{ 'is-open': open }" id="mobile-submenu-<?php echo e($index); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $item['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <li><a href="<?php echo e(url($child['url'])); ?>"><?php echo e($child['label']); ?></a></li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
        <div class="mobile-nav-footer">
            <a class="btn btn-primary" href="<?php echo e(url('/admisiones')); ?>" style="width:100%">Quiero inscribir a mi hijo/a</a>
        </div>
    </nav>

    <div class="sticky-cta">
        <a class="btn btn-primary" href="<?php echo e(url('/admisiones')); ?>">Admisiones →</a>
    </div>
</div>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/site-header.blade.php ENDPATH**/ ?>