<?php
    // JSON-LD `EducationalOrganization` global (docs/08-seo.md §3) — datos de
    // contacto reales desde SiteSetting, no inventados. Los campos vacíos se
    // omiten en vez de escribir cadenas vacías (evita advertencias del
    // validador de schema.org).
    $siteName = \App\Models\SiteSetting::get('site_name', 'Hierro Metal S.R.L.');
    $phone = \App\Models\SiteSetting::get('contact_phone');
    $email = \App\Models\SiteSetting::get('contact_email');
    $address = \App\Models\SiteSetting::get('direccion_larga');
    $facebook = \App\Models\SiteSetting::get('social_facebook_url');
    $instagram = \App\Models\SiteSetting::get('social_instagram_url');

    $schema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'EducationalOrganization',
        'name' => config('sitio.seo.organization_name'),
        'legalName' => config('sitio.seo.organization_legal_name'),
        'url' => url('/'),
        'logo' => asset('images/logo-dante.svg'),
        'telephone' => $phone ?: null,
        'email' => $email ?: null,
        'address' => $address ? [
            '@type' => 'PostalAddress',
            'streetAddress' => $address,
            'addressLocality' => 'Asunción',
            'addressCountry' => 'PY',
        ] : null,
        'sameAs' => array_values(array_filter([$facebook, $instagram])) ?: null,
    ], fn ($value) => $value !== null && $value !== '');
?>
<script type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
<?php /**PATH /Users/leonshy/hierro-metal-web-insitucional/web/resources/views/components/schema/organization.blade.php ENDPATH**/ ?>