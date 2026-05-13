@php
    $adminNavItems = [
        [
            'route'   => 'admin.dashboard',
            'label'   => 'Dashboard',
            'pattern' => 'admin.dashboard',
            'icon'    => 'M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z',
        ],
        [
            'route'   => 'admin.events',
            'label'   => 'Events',
            'pattern' => 'admin.events*',
            'icon'    => 'M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z',
        ],
        [
            'route'   => 'admin.participants',
            'label'   => 'Participants',
            'pattern' => 'admin.participants*',
            'icon'    => 'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0m8 0a4 4 0 01-8 0',
        ],
        [
            'route'   => 'admin.evaluations',
            'label'   => 'Evaluations',
            'pattern' => 'admin.evaluations*',
            'icon'    => 'M8 10h8M8 14h5M5 5h14v12H7l-4 4V7a2 2 0 012-2z',
        ],
    ];
@endphp

<aside class="fixed left-0 top-0 z-40 h-screen w-[270px] border-r border-[#1E3357] bg-[#0D1B31]">

    {{-- Logo --}}
    <div class="flex h-[78px] items-center border-b border-[#1E3357] px-6">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#3B82F6] text-white shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                </svg>
            </div>

            <div>
                <h1 class="text-[13px] font-black tracking-[0.18em] text-[#F8FAFC]">EVENT</h1>
                <p class="text-[10px] font-bold tracking-[0.16em] text-[#60A5FA]">MANAGEMENT SYSTEM</p>
            </div>
        </div>
    </div>

    {{-- Admin Card --}}
    <div class="px-6 pt-10">
        <div class="flex items-center gap-3 rounded-2xl border border-[#60A5FA]/20 bg-[#13284A]/70 p-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#3B82F6] text-sm font-black text-white">
                A
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-[#F8FAFC]">Administrator</h2>
                <p class="text-xs text-[#94A3B8]">admin@system.edu.ph</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="mt-6 px-4">
        <p class="px-2 text-[11px] font-black uppercase tracking-widest text-[#64748B]">
            Main Navigation
        </p>

        <div class="mt-4 space-y-2">
            @foreach ($adminNavItems as $item)
                @php $isActive = request()->routeIs($item['pattern']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center {{ $isActive ? 'justify-between rounded-2xl bg-[#3B82F6] px-4 py-3 text-sm font-black text-white shadow-sm' : 'gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#94A3B8] transition hover:bg-[#13284A]/70 hover:text-[#F8FAFC]' }}">
                    <span class="flex items-center gap-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </span>
                    @if ($isActive)
                        <span>›</span>
                    @endif
                </a>
            @endforeach
        </div>
    </nav>

    {{-- Bottom Links --}}
    <div class="absolute bottom-0 left-0 w-full border-t border-[#1E3357] px-6 py-6">
        <a href="#" class="mb-5 flex items-center gap-3 text-sm font-bold text-[#94A3B8] transition hover:text-[#60A5FA]">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066 1.724 1.724 0 012.37 2.37 1.724 1.724 0 001.065 2.572 1.724 1.724 0 010 3.35 1.724 1.724 0 00-1.066 2.573 1.724 1.724 0 01-2.37 2.37 1.724 1.724 0 00-2.572 1.065 1.724 1.724 0 01-3.35 0 1.724 1.724 0 00-2.573-1.066 1.724 1.724 0 01-2.37-2.37 1.724 1.724 0 00-1.065-2.572 1.724 1.724 0 010-3.35 1.724 1.724 0 001.066-2.573 1.724 1.724 0 012.37-2.37 1.724 1.724 0 002.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 text-sm font-bold text-[#EF4444] transition hover:text-[#F87171]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H5a2 2 0 01-2-2V6a2 2 0 012-2h8"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
