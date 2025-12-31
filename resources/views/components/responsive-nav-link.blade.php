@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-purple-500 text-start text-base font-medium text-white bg-purple-900/30 focus:outline-none focus:text-white focus:bg-purple-900/40 focus:border-purple-400 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-300 hover:text-white hover:bg-white/5 hover:border-purple-300 focus:outline-none focus:text-white focus:bg-white/5 focus:border-purple-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
