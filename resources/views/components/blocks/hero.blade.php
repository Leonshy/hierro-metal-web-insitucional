@props(['data'])
<x-hero
    :title="$data['title'] ?? ''"
    :subtitle="$data['subtitle'] ?? null"
    :image="! empty($data['media_id']) ? \App\Models\Media::query()->find($data['media_id'])?->url() : null"
    :cta-label="$data['cta_label'] ?? null"
    :cta-url="$data['cta_url'] ?? null"
/>
