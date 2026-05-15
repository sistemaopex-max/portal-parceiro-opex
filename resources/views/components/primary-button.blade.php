<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center gap-1.5 px-4 py-2.5 bg-marino border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-marino-700 focus:bg-marino-700 active:bg-marino-dark focus:outline-none focus:ring-2 focus:ring-marino focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm']) }}>
    {{ $slot }}
</button>
