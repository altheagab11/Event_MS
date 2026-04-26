@extends('layouts.app')

@section('content')
@php
    $totalParticipants = $totalParticipants ?? 0;
    $pendingPapers = $pendingPapers ?? 0;
    $activeEvents = $activeEvents ?? 0;
    $totalCheckedIn = $totalCheckedIn ?? 0;
@endphp
<div class="min-h-screen bg-[#F6F8FB] font-sans text-[#111827]">
    <div class="flex">

        {{-- SIDEBAR --}}
        <aside class="fixed left-0 top-0 z-40 h-screen w-[270px] border-r border-[#E5EAF1] bg-[#FBFAF7]">
            {{-- Logo --}}
            <div class="flex h-[78px] items-center border-b border-[#E5EAF1] px-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#111827] text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-[13px] font-black tracking-[0.18em] text-[#0F172A]">EVENT</h1>
                        <p class="text-[10px] font-bold tracking-[0.16em] text-[#C8A25A]">MANAGEMENT SYSTEM</p>
                    </div>
                </div>
            </div>

            {{-- Admin Card --}}
            <div class="px-6 pt-10">
                <div class="flex items-center gap-3 rounded-2xl border border-[#DDE6F2] bg-[#F4F8FC] p-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#111827] text-sm font-black text-white">
                        A
                    </div>
                    <div>
                        <h2 class="text-sm font-extrabold text-[#111827]">Administrator</h2>
                        <p class="text-xs text-[#7B8AA0]">admin@system.edu.ph</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="mt-6 px-4">
                <p class="px-2 text-[11px] font-black uppercase tracking-widest text-[#D6DEE9]">
                    Main Navigation
                </p>

                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center {{ request()->routeIs('admin.dashboard') ? 'justify-between rounded-2xl border-l-2 border-[#D2A64B] bg-[#FFF8EA] px-4 py-3 text-sm font-black text-[#0F172A]' : 'gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]' }}">
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-[#D2A64B]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>
                            </svg>
                            Dashboard
                        </span>
                        @if (request()->routeIs('admin.dashboard'))
                            <span class="text-[#D2A64B]">›</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.events') }}"
                       class="flex items-center {{ request()->routeIs('admin.events*') ? 'justify-between rounded-2xl border-l-2 border-[#D2A64B] bg-[#FFF8EA] px-4 py-3 text-sm font-black text-[#0F172A]' : 'gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]' }}">
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.events*') ? 'text-[#D2A64B]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                            </svg>
                            Events
                        </span>
                        @if (request()->routeIs('admin.events*'))
                            <span class="text-[#D2A64B]">›</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.participants') }}"
                       class="flex items-center {{ request()->routeIs('admin.participants*') ? 'justify-between rounded-2xl border-l-2 border-[#D2A64B] bg-[#FFF8EA] px-4 py-3 text-sm font-black text-[#0F172A]' : 'gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]' }}">
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.participants*') ? 'text-[#D2A64B]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0m8 0a4 4 0 01-8 0"/>
                            </svg>
                            Participants
                        </span>
                        @if (request()->routeIs('admin.participants*'))
                            <span class="text-[#D2A64B]">›</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.evaluations') }}"
                       class="flex items-center {{ request()->routeIs('admin.evaluations*') ? 'justify-between rounded-2xl border-l-2 border-[#D2A64B] bg-[#FFF8EA] px-4 py-3 text-sm font-black text-[#0F172A]' : 'gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[#53657F] hover:bg-[#F4F7FB]' }}">
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.evaluations*') ? 'text-[#D2A64B]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M5 5h14v12H7l-4 4V7a2 2 0 012-2z"/>
                            </svg>
                            Evaluations
                        </span>
                        @if (request()->routeIs('admin.evaluations*'))
                            <span class="text-[#D2A64B]">›</span>
                        @endif
                    </a>
                </div>
            </nav>

            {{-- Bottom Links --}}
            <div class="absolute bottom-0 left-0 w-full border-t border-[#E5EAF1] px-6 py-6">
                <a href="#" class="mb-5 flex items-center gap-3 text-sm font-bold text-[#7A8BA3]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066 1.724 1.724 0 012.37 2.37 1.724 1.724 0 001.065 2.572 1.724 1.724 0 010 3.35 1.724 1.724 0 00-1.066 2.573 1.724 1.724 0 01-2.37 2.37 1.724 1.724 0 00-2.572 1.065 1.724 1.724 0 01-3.35 0 1.724 1.724 0 00-2.573-1.066 1.724 1.724 0 01-2.37-2.37 1.724 1.724 0 00-1.065-2.572 1.724 1.724 0 010-3.35 1.724 1.724 0 001.066-2.573 1.724 1.724 0 012.37-2.37 1.724 1.724 0 002.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-sm font-bold text-[#FF4D4F]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H5a2 2 0 01-2-2V6a2 2 0 012-2h8"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="ml-[270px] min-h-screen w-full">

            {{-- Topbar --}}
            <header class="flex h-[78px] items-center justify-between border-b border-[#E5EAF1] bg-white px-9">
                <h2 class="text-sm font-black uppercase tracking-widest text-[#111827]">
                    Overview
                </h2>

                <div class="flex items-center gap-4">
                    <button class="relative flex h-10 w-10 items-center justify-center rounded-2xl border border-[#DDE6F2] bg-white text-[#53657F]">
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#D2A64B]"></span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0"/>
                        </svg>
                    </button>

                    <div class="flex items-center gap-2 rounded-2xl border border-[#DDE6F2] bg-white px-3 py-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-[#111827] text-xs font-black text-white">
                            A
                        </div>
                        <span class="text-sm font-bold">Admin</span>
                    </div>
                </div>
            </header>

            <section class="px-9 py-10">

                {{-- Page Header --}}
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="h-7 w-1 rounded-full bg-[#D2A64B]"></div>
                            <h1 class="text-[28px] font-black tracking-tight text-[#111827]">
                                DASHBOARD OVERVIEW
                            </h1>
                        </div>
                        <p class="mt-2 text-sm text-[#53657F]">
                            Real-time summary of all events and participant activity.
                        </p>
                    </div>

                    <button class="flex items-center gap-3 rounded-2xl bg-[#111827] px-7 py-4 text-sm font-black uppercase tracking-wide text-white shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>
                        </svg>
                        QR Scanner
                    </button>
                </div>

                {{-- Stat Cards --}}
                <div class="mt-16 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

                    <div class="rounded-2xl border border-[#DDE6F2] bg-white p-7">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#DDE6F2] bg-[#F8FAFC] text-[#111827]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0m8 0a4 4 0 01-8 0"/>
                                </svg>
                            </div>
                            <span class="text-[#D2A64B]">↗</span>
                        </div>

                        <p class="mt-5 text-xs font-black uppercase tracking-widest text-[#6B7280]">
                            Total Participants
                        </p>
                        <h3 class="mt-2 text-3xl font-black">{{ number_format($totalParticipants) }}</h3>
                        <p class="mt-1 text-xs text-[#7B8AA0]">+12% this month</p>
                    </div>

                    <div class="rounded-2xl border border-[#E7DCC6] bg-[#FFFBF5] p-7">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#DDE6F2] bg-white text-[#111827]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h7l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                </svg>
                            </div>
                            <span class="text-[#D2A64B]">↗</span>
                        </div>

                        <p class="mt-5 text-xs font-black uppercase tracking-widest text-[#6B7280]">
                            Pending Papers
                        </p>
                        <h3 class="mt-2 text-3xl font-black">{{ number_format($pendingPapers) }}</h3>
                        <p class="mt-1 text-xs text-[#7B8AA0]">4 need urgent review</p>
                    </div>

                    <div class="rounded-2xl border border-[#111827] bg-[#172233] p-7 text-white">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/40 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                </svg>
                            </div>
                            <span class="text-[#D2A64B]">↗</span>
                        </div>

                        <p class="mt-5 text-xs font-black uppercase tracking-widest text-white/70">
                            Active Events
                        </p>
                        <h3 class="mt-2 text-3xl font-black">{{ number_format($activeEvents) }}</h3>
                        <p class="mt-1 text-xs text-white/60">School & Conference</p>
                    </div>

                    <div class="rounded-2xl border border-[#CDEFD9] bg-[#F0FFF5] p-7">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#DDE6F2] bg-white text-[#111827]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-[#D2A64B]">↗</span>
                        </div>

                        <p class="mt-5 text-xs font-black uppercase tracking-widest text-[#6B7280]">
                            Total Checked-In
                        </p>
                        <h3 class="mt-2 text-3xl font-black">{{ number_format($totalCheckedIn) }}</h3>
                        <p class="mt-1 text-xs text-[#7B8AA0]">68.6% attendance rate</p>
                    </div>
                </div>

                {{-- Charts Section --}}
                <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-[1fr_540px]">

                    {{-- Demographics --}}
                    <div class="rounded-2xl border border-[#DDE6F2] bg-white p-7">
                        <div class="mb-8 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-[#D2A64B]">▥</span>
                                <h2 class="text-xl font-black uppercase tracking-wide">
                                    Participant Demographics
                                </h2>
                            </div>

                            <span class="rounded-xl border border-[#DDE6F2] bg-[#F4F8FC] px-4 py-2 text-sm font-bold text-[#53657F]">
                                2026
                            </span>
                        </div>

                        @php
                            $demographics = [
                                ['label' => 'Undergraduate', 'value' => 65, 'color' => 'bg-[#111827]'],
                                ['label' => 'Senior High', 'value' => 20, 'color' => 'bg-[#D2B06A]'],
                                ['label' => 'Graduate', 'value' => 10, 'color' => 'bg-[#64748B]'],
                                ['label' => 'Professional', 'value' => 5, 'color' => 'bg-[#CBD5E1]'],
                            ];
                        @endphp

                        <div class="space-y-6">
                            @foreach ($demographics as $item)
                                <div>
                                    <div class="mb-3 flex items-center justify-between">
                                        <p class="text-sm font-extrabold">{{ $item['label'] }}</p>
                                        <p class="text-sm font-black text-[#C8A25A]">{{ $item['value'] }}%</p>
                                    </div>

                                    <div class="h-3 overflow-hidden rounded-full bg-[#EEF2F7]">
                                        <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ $item['value'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Event Types --}}
                    <div class="rounded-2xl border border-[#E7DCC6] bg-[#FFFBF5] p-7">
                        <div class="mb-8 flex items-center gap-3">
                            <span class="text-[#D2A64B]">⌁</span>
                            <h2 class="text-xl font-black uppercase tracking-wide">
                                Event Types
                            </h2>
                        </div>

                        <div class="flex justify-center">
                            <div class="relative h-40 w-40 rounded-full"
                                style="background: conic-gradient(#111827 0deg 270deg, #D2B06A 270deg 360deg);">
                                <div class="absolute inset-7 flex flex-col items-center justify-center rounded-full bg-[#FFFBF5]">
                                    <span class="text-2xl font-black">12</span>
                                    <span class="text-[10px] font-black uppercase text-[#53657F]">Total</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-7 space-y-3">
                            <div class="flex items-center justify-between rounded-2xl border border-[#DDE6F2] bg-white px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-3 w-3 rounded-full bg-[#111827]"></span>
                                    <p class="text-sm font-extrabold">School Events</p>
                                </div>
                                <p class="text-sm font-black text-[#C8A25A]">75%</p>
                            </div>

                            <div class="flex items-center justify-between rounded-2xl border border-[#DDE6F2] bg-white px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-3 w-3 rounded-full bg-[#D2B06A]"></span>
                                    <p class="text-sm font-extrabold">Conference</p>
                                </div>
                                <p class="text-sm font-black text-[#C8A25A]">25%</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Welcome Banner --}}
                <div class="relative mt-8 overflow-hidden rounded-2xl bg-[#172233] p-8 text-white">
                    <div class="flex items-center gap-6">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#D2B06A] text-white">
                            <svg class="h-9 w-9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15l-3.5 2 1-4L6 10h4l2-4 2 4h4l-3.5 3 1 4L12 15z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-2xl font-black uppercase tracking-wide">
                                Welcome Back, Administrator!
                            </h2>
                            <p class="mt-2 max-w-3xl text-sm text-white/70">
                                Use the sidebar to navigate through events and participants. Review the pending research papers for the upcoming Tech Innovations Summit.
                            </p>
                        </div>
                    </div>

                    <div class="pointer-events-none absolute -bottom-10 right-10 text-[170px] font-black text-white/5">
                        2026
                    </div>
                </div>

            </section>
        </main>
    </div>
</div>
@endsection
