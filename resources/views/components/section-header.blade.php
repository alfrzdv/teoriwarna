@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'mb-8']) }}>
    @if($subtitle)
        <p class="text-brand-400 font-medium text-sm uppercase tracking-wide mb-2">{{ $subtitle }}</p>
    @endif
    <h2 class="text-3xl md:text-4xl font-black font-heading text-white">
        {{ $title }}
    </h2>
    @if($slot->isNotEmpty())
        <div class="mt-3 text-dark-300">
            {{ $slot }}
        </div>
    @endif
</div>
