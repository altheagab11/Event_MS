@extends('layouts.app')

@section('content')
@php
    $activeEvents = $activeEvents ?? 0;
    $totalParticipants = $totalParticipants ?? 0;
    $pendingParticipants = $pendingParticipants ?? 0;
    $approvedPapers = $approvedPapers ?? 0;
    $pendingPapers = $pendingPapers ?? 0;
@endphp
<style>
    .dashboard-stat-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1.5rem;
    }
    .dashboard-stat-grid > div {
        min-width: 0;
    }

    .qr-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.65);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 24px;
    }

    .qr-modal-overlay.hidden {
        display: none;
    }

    .qr-modal {
        width: 90%;
        max-width: 720px;
        max-height: 85vh;
        background: #0b1b31;
        border: 1px solid rgba(96, 165, 250, 0.25);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
        display: flex;
        flex-direction: column;
    }

    .qr-modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        background: #0d1b31;
    }

    .qr-modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
        min-height: 0;
    }

    .qr-tab-btn {
        flex: 1;
        border-radius: 14px;
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        background: transparent;
        border: 1px solid transparent;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
    }

    .qr-tab-btn.active {
        color: #f8fafc;
        background: rgba(59, 130, 246, 0.2);
        border-color: rgba(96, 165, 250, 0.35);
    }

    .qr-tab-panel.hidden {
        display: none;
    }

    .qr-camera-box {
        width: 100%;
        max-height: 420px;
        aspect-ratio: 4 / 3;
        background: #020617;
        border: 1px solid rgba(96, 165, 250, 0.25);
        border-radius: 18px;
        overflow: hidden;
        position: relative;
    }

    .qr-camera-box #qrReader,
    .qr-camera-box #qrReader > div {
        width: 100% !important;
        height: 100% !important;
        max-width: 100% !important;
        max-height: 100% !important;
        min-height: 0 !important;
    }

    .qr-camera-box video,
    .qr-camera-box canvas,
    .qr-camera-box img {
        width: 100% !important;
        height: 100% !important;
        max-width: 100% !important;
        max-height: 100% !important;
        object-fit: cover !important;
        display: block;
    }

    .qr-scan-frame {
        position: absolute;
        width: 180px;
        height: 180px;
        border: 3px solid white;
        border-radius: 12px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 2;
        box-shadow: 0 0 0 9999px rgba(2, 6, 23, 0.45);
    }

    @media (max-width: 768px) {
        .qr-modal-overlay {
            padding: 16px;
        }

        .qr-modal {
            width: 95%;
            max-height: 90vh;
        }

        .qr-camera-box {
            max-height: 340px;
        }

        .qr-scan-frame {
            width: 140px;
            height: 140px;
        }
    }
</style>
<div class="min-h-screen bg-gradient-to-br from-[#0F1E36] via-[#132B4A] to-[#0F1E36] font-sans text-[#F8FAFC]">
    <div class="flex">

        @include('admin.partials.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="ml-[270px] min-h-screen w-full">

            @include('admin.partials.topbar', ['topbarTitle' => 'Overview'])

            <section class="px-9 py-10">

                {{-- Page Header --}}
                <div class="flex flex-col items-start gap-6 md:flex-row md:items-start md:justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="h-7 w-1 rounded-full bg-[#60A5FA]"></div>
                            <h1 class="text-[28px] font-black tracking-tight text-[#F8FAFC]">
                                DASHBOARD OVERVIEW
                            </h1>
                        </div>
                        <p class="mt-2 text-sm text-[#CBD5E1]">
                            Real-time summary of all events and participant activity.
                        </p>
                    </div>

                    <button
                        type="button"
                        id="openQrScannerButton"
                        class="inline-flex items-center gap-3 rounded-2xl bg-[#3B82F6] px-7 py-4 text-sm font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-[#2563EB]"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>
                        </svg>
                        QR Scanner
                    </button>
                </div>

                {{-- Stat Cards --}}
                <div class="dashboard-stat-grid mt-12">

                    {{-- Active Events --}}
                    <div class="group relative min-w-0 overflow-hidden rounded-2xl border border-[#60A5FA]/30 bg-gradient-to-br from-[#10213A] via-[#13284A] to-[#0D1B31] p-7 text-[#F8FAFC] shadow-sm transition hover:border-[#60A5FA]/45">
                        <div class="pointer-events-none absolute -right-6 -top-6 h-28 w-28 rounded-full bg-[#60A5FA]/10 blur-2xl"></div>
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#60A5FA]/40 bg-[#60A5FA]/10 text-[#60A5FA]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                </svg>
                            </div>
                            <span class="text-[#60A5FA]">?</span>
                        </div>
                        <p class="mt-5 text-xs font-black uppercase tracking-widest text-[#94A3B8]">Active Events</p>
                        <h3 class="mt-2 text-3xl font-black text-[#F8FAFC]">{{ number_format($activeEvents) }}</h3>
                        <p class="mt-1 text-xs text-[#CBD5E1]">School &amp; Conference</p>
                    </div>

                    {{-- Total Participants --}}
                    <div class="group min-w-0 rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 p-7 shadow-lg shadow-black/20 backdrop-blur-md transition hover:border-[#60A5FA]/40">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#60A5FA]/25 bg-[#10213A] text-[#60A5FA]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0m8 0a4 4 0 01-8 0"/>
                                </svg>
                            </div>
                            <span class="text-[#60A5FA]">?</span>
                        </div>

                        <p class="mt-5 text-xs font-black uppercase tracking-widest text-[#94A3B8]">
                            Total Participants
                        </p>
                        <h3 class="mt-2 text-3xl font-black text-[#F8FAFC]">{{ number_format($totalParticipants) }}</h3>
                        <p class="mt-1 text-xs text-[#94A3B8]">Across all events</p>
                    </div>


                    {{-- Pending Participants --}}
                    <div class="group min-w-0 rounded-2xl border border-[#F97316]/25 bg-[#1E375A]/65 p-7 shadow-lg shadow-black/20 backdrop-blur-md transition hover:border-[#F97316]/45">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#F97316]/30 bg-[#10213A] text-[#F97316]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-[#F97316]">?</span>
                        </div>
                        <p class="mt-5 text-xs font-black uppercase tracking-widest text-[#94A3B8]">Pending Participants</p>
                        <h3 class="mt-2 text-3xl font-black text-[#F8FAFC]">{{ number_format($pendingParticipants) }}</h3>
                        <p class="mt-1 text-xs text-[#F97316]">Awaiting approval</p>
                    </div>

                    {{-- Approved Papers --}}
                    <div class="group min-w-0 rounded-2xl border border-[#22C55E]/25 bg-[#1E375A]/65 p-7 shadow-lg shadow-black/20 backdrop-blur-md transition hover:border-[#22C55E]/45">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#22C55E]/30 bg-[#10213A] text-[#22C55E]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-[#22C55E]">?</span>
                        </div>
                        <p class="mt-5 text-xs font-black uppercase tracking-widest text-[#94A3B8]">Approved Papers</p>
                        <h3 class="mt-2 text-3xl font-black text-[#F8FAFC]">{{ number_format($approvedPapers) }}</h3>
                        <p class="mt-1 text-xs text-[#22C55E]">Accepted submissions</p>
                    </div>

                    {{-- Pending Papers --}}
                    <div class="group min-w-0 rounded-2xl border border-[#FACC15]/25 bg-[#1E375A]/65 p-7 shadow-lg shadow-black/20 backdrop-blur-md transition hover:border-[#FACC15]/45">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#FACC15]/30 bg-[#10213A] text-[#FACC15]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h7l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                </svg>
                            </div>
                            <span class="text-[#FACC15]">?</span>
                        </div>
                        <p class="mt-5 text-xs font-black uppercase tracking-widest text-[#94A3B8]">Pending Papers</p>
                        <h3 class="mt-2 text-3xl font-black text-[#F8FAFC]">{{ number_format($pendingPapers) }}</h3>
                        <p class="mt-1 text-xs text-[#FACC15]">Awaiting review</p>
                    </div>
                </div>

                {{-- Charts Section --}}
                <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-[1fr_540px]">

                    {{-- Demographics --}}
                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 p-7 shadow-lg shadow-black/20 backdrop-blur-md">
                        <div class="mb-8 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#60A5FA]/15 text-[#60A5FA]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 17v-7m4 7v-4m4 4v-9m4 9v-5m4 5v-2"/>
                                    </svg>
                                </span>
                                <h2 class="text-xl font-black uppercase tracking-wide text-[#F8FAFC]">
                                    Participant Demographics
                                </h2>
                            </div>

                            <span class="rounded-xl border border-[#60A5FA]/20 bg-[#10213A] px-4 py-2 text-sm font-bold text-[#CBD5E1]">
                                {{ date('Y') }}
                            </span>
                        </div>

                        @php
                            $demographics = [
                                ['label' => 'Undergraduate', 'value' => 65, 'bar' => 'bg-[#3B82F6]'],
                                ['label' => 'Senior High',   'value' => 20, 'bar' => 'bg-[#3B82F6]'],
                                ['label' => 'Graduate',      'value' => 10, 'bar' => 'bg-[#0284C7]'],
                                ['label' => 'Professional',  'value' => 5,  'bar' => 'bg-[#0369A1]'],
                            ];
                        @endphp

                        <div class="space-y-6">
                            @foreach ($demographics as $item)
                                <div>
                                    <div class="mb-3 flex items-center justify-between">
                                        <p class="text-sm font-extrabold text-[#F8FAFC]">{{ $item['label'] }}</p>
                                        <p class="text-sm font-black text-[#60A5FA]">{{ $item['value'] }}%</p>
                                    </div>

                                    <div class="h-3 overflow-hidden rounded-full bg-[#0D1B31] ring-1 ring-inset ring-[#60A5FA]/10">
                                        <div class="h-full rounded-full {{ $item['bar'] }}" style="width: {{ $item['value'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Event Types --}}
                    <div class="rounded-2xl border border-[#60A5FA]/20 bg-[#1E375A]/65 p-7 shadow-lg shadow-black/20 backdrop-blur-md">
                        <div class="mb-8 flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#60A5FA]/15 text-[#60A5FA]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.05A9 9 0 1020.95 13H11V3.05z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0015 3.51V9h5.49z"/>
                                </svg>
                            </span>
                            <h2 class="text-xl font-black uppercase tracking-wide text-[#F8FAFC]">
                                Event Types
                            </h2>
                        </div>

                        <div class="flex justify-center">
                            <div class="relative h-40 w-40 rounded-full"
                                style="background: conic-gradient(#60A5FA 0deg 270deg, #3B82F6 270deg 360deg);">
                                <div class="absolute inset-7 flex flex-col items-center justify-center rounded-full border border-[#60A5FA]/25 bg-[#0D1B31]">
                                    <span class="text-2xl font-black text-[#F8FAFC]">12</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-[#94A3B8]">Total</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-7 space-y-3">
                            <div class="flex items-center justify-between rounded-2xl border border-[#60A5FA]/20 bg-[#10213A] px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-3 w-3 rounded-full bg-[#60A5FA]"></span>
                                    <p class="text-sm font-extrabold text-[#F8FAFC]">School Events</p>
                                </div>
                                <p class="text-sm font-black text-[#60A5FA]">75%</p>
                            </div>

                            <div class="flex items-center justify-between rounded-2xl border border-[#60A5FA]/20 bg-[#10213A] px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-3 w-3 rounded-full bg-[#3B82F6]"></span>
                                    <p class="text-sm font-extrabold text-[#F8FAFC]">Conference</p>
                                </div>
                                <p class="text-sm font-black text-[#3B82F6]">25%</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Welcome Banner --}}
                <div class="relative mt-8 overflow-hidden rounded-2xl border border-[#60A5FA]/25 bg-gradient-to-br from-[#10213A] via-[#13284A] to-[#0D1B31] p-8 text-[#F8FAFC] shadow-md">
                    <div class="pointer-events-none absolute -left-10 -top-10 h-44 w-44 rounded-full bg-[#60A5FA]/10 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-12 right-1/3 h-36 w-36 rounded-full bg-[#3B82F6]/10 blur-3xl"></div>

                    <div class="relative flex flex-col items-start gap-6 md:flex-row md:items-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#3B82F6] text-white shadow-sm">
                            <svg class="h-9 w-9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15l-3.5 2 1-4L6 10h4l2-4 2 4h4l-3.5 3 1 4L12 15z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-2xl font-black uppercase tracking-wide text-[#F8FAFC]">
                                Welcome Back, Administrator!
                            </h2>
                            <p class="mt-2 max-w-3xl text-sm text-[#CBD5E1]">
                                Use the sidebar to navigate through events and participants. Review the pending research papers for the upcoming conferences and keep an eye on participant activity.
                            </p>
                        </div>
                    </div>

                    <div class="pointer-events-none absolute -bottom-10 right-10 text-[170px] font-black text-[#60A5FA]/10">
                        {{ date('Y') }}
                    </div>
                </div>

            </section>
        </main>
    </div>
</div>


@include('admin.partials.qr-scanner-modal')
@endsection
