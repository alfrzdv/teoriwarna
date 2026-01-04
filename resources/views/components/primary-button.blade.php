<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-primary-500 hover:to-primary-600 focus:from-primary-500 focus:to-primary-600 active:from-primary-700 active:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-black transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
