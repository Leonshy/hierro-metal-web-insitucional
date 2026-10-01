{{-- JSON-LD del negocio (docs/08-seo.md §3). Todo sale del panel: App\Support\NegocioLocal. --}}
<script type="application/ld+json">{!! json_encode(\App\Support\NegocioLocal::schema(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
