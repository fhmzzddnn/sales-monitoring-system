@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[#49454F] mb-2 px-1']) }}>
    {{ $value ?? $slot }}
</label>
