@props([
    'title',
    'buttonText' => null,
    'buttonAction' => null,
    'buttonIcon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>',
])

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-medium text-[#1C1B1F]">{{ $title }}</h1>
    
    @if($buttonText && $buttonAction)
        <button @click="{{ $buttonAction }}" 
                class="bg-[#EADDFF] hover:bg-[#D0BCFF] text-[#21005D] font-semibold py-3 px-6 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $buttonIcon !!}</svg>
            <span>{{ $buttonText }}</span>
        </button>
    @endif
</div>
