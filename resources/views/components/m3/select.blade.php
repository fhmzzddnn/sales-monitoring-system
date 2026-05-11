@props([
    'label' => null,
    'model' => null,
    'error' => null,
    'options' => null,
    'placeholder' => 'Select an option',
])

<div {{ $attributes->merge(['class' => 'group relative']) }}>
    @if($label)
        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">{{ $label }}</label>
    @endif
    <div class="relative">
        <select @if($model) x-model="{{ $model }}" @endif
                style="background-image: none;"
                class="block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-transparent text-main focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all appearance-none">
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            {{ $slot }}
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-[#49454F]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </div>
    @if($error)
        <template x-if="{{ $error }}">
            <p class="text-[#B3261E] text-xs mt-1 px-1 font-medium" x-text="{{ $error }}[0]"></p>
        </template>
    @endif
</div>
