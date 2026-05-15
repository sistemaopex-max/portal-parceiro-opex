<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-marino border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-marino-dark focus:bg-marino-dark active:bg-marino-dark focus:outline-none focus:ring-2 focus:ring-marino focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
