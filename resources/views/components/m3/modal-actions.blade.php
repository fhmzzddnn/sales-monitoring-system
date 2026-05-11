@props([
    'cancelAction',
    'saveText' => 'Save',
    'saveType' => 'submit',
])

<div {{ $attributes->merge(['class' => 'flex justify-end gap-3 mt-8']) }}>
    <button type="button" @click="{{ $cancelAction }}" 
            class="text-[#6750A4] hover:bg-[#ECE6F0] font-semibold px-6 py-2.5 rounded-full transition-all">
        Cancel
    </button>
    <button type="{{ $saveType }}" 
            class="bg-[#6750A4] hover:bg-[#4F378B] text-white font-semibold px-8 py-2.5 rounded-full shadow-sm hover:shadow-md transition-all">
        {{ $saveText }}
    </button>
</div>
