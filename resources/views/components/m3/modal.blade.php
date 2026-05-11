@props([
    'id' => null,
    'name' => null,
    'show' => null,
    'maxWidth' => '2xl',
    'title' => null,
    'close' => null,
])

@php
$maxWidth = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
][$maxWidth] ?? $maxWidth;
@endphp

<div x-show="{{ $show }}" 
     x-cloak 
     @keydown.escape.window="{{ $close }}"
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    
    <!-- Scrim (Backdrop) -->
    <div x-show="{{ $show }}" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/30 backdrop-blur-[2px]" 
         @click="{{ $close }}"></div>

    <!-- Modal Surface -->
    <div x-show="{{ $show }}" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-[#FEF7FF] rounded-[28px] shadow-2xl w-full {{ $maxWidth }} p-8 z-10 max-h-[90vh] overflow-y-auto">
        
        @if($title)
            <h3 class="text-2xl font-normal text-main mb-6" x-text="{{ $title }}"></h3>
        @endif

        {{ $slot }}
    </div>
</div>
