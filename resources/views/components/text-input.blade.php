@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-marino focus:ring-marino rounded-md shadow-sm']) }}>
