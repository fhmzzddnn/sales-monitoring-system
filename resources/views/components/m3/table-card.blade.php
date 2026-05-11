@props(['padding' => 'p-4 sm:p-8'])

<div {{ $attributes->merge(['class' => 'bg-[#F3EDF7] rounded-[28px] overflow-hidden']) }}>
    <div class="{{ $padding }}">
        <div class="overflow-x-auto">
            {{ $slot }}
        </div>
    </div>
</div>
