@props([
    'label' => null,
    'type' => 'text',
    'model' => null,
    'error' => null,
    'placeholder' => null,
    'prefix' => null,
])

<div {{ $attributes->merge(['class' => 'group relative']) }}>
    @if($label)
        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">{{ $label }}</label>
    @endif
    <div class="relative">
        @if($prefix)
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-[#49454F] font-medium text-sm">{{ $prefix }}</span>
        @endif
        <input type="{{ $type }}" 
               @if($model) x-model="{{ $model }}" @endif
               placeholder="{{ $placeholder }}" 
               class="block w-full border border-[#79747E] rounded-xl {{ $prefix ? 'pl-10' : 'px-4' }} py-3 bg-transparent text-main placeholder:text-[#79747E]/50 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
    </div>
    @if($error)
        <template x-if="{{ $error }}">
            <p class="text-[#B3261E] text-xs mt-1 px-1 font-medium" x-text="{{ $error }}[0]"></p>
        </template>
    @endif
</div>
