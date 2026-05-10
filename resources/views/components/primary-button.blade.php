<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-[#6750A4] hover:bg-[#4F378B] text-white font-semibold px-8 py-2.5 rounded-full shadow-sm hover:shadow-md transition-all uppercase text-xs tracking-wider']) }}>
    {{ $slot }}
</button>
