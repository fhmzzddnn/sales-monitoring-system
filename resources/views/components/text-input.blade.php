@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-transparent text-[#1C1B1F] placeholder:text-[#79747E]/50 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all']) }}>
