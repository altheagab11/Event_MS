<header class="sticky top-0 z-30 flex h-[78px] items-center justify-between border-b border-[#1E3357] bg-[#10213A]/90 px-9 backdrop-blur-md">
    <h2 class="text-sm font-black uppercase tracking-widest text-[#F8FAFC]">
        {{ $topbarTitle ?? 'Admin' }}
    </h2>

    <div class="flex items-center gap-4">
        <button type="button" class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-[#1E3357] bg-[#13284A]/70 text-[#CBD5E1] transition hover:border-[#60A5FA]/40 hover:text-[#60A5FA]">
            <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#60A5FA]"></span>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0"/>
            </svg>
        </button>

        <div class="flex items-center gap-2 rounded-xl border border-[#1E3357] bg-[#13284A]/70 px-3 py-2">
            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-[#3B82F6] text-xs font-black text-white">
                A
            </div>
            <span class="text-sm font-bold text-[#F8FAFC]">Admin</span>
        </div>
    </div>
</header>
