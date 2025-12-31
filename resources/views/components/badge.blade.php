@props(['variant' => 'default', 'size' => 'md'])

@php
    $variants = [
        'default' => 'bg-dark-700 text-dark-300',
        'primary' => 'bg-brand-600 text-white',
        'success' => 'bg-green-600 text-white',
        'warning' => 'bg-yellow-600 text-white',
        'danger' => 'bg-red-600 text-white',
        'info' => 'bg-blue-600 text-white',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
    ];

    $classes = $variants[$variant] ?? $variants['default'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge(['class' => "$classes $sizeClass font-medium rounded-full inline-flex items-center gap-1"]) }}>
    {{ $slot }}
</span>
