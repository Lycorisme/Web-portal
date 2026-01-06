@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    {{-- Page Header - Same as Activity Log --}}
    {{-- Enhanced Page Header --}}
    <div class="relative mb-8 animate-fade-in group">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                {{-- Animated Icon Container --}}
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-theme-500/20 to-theme-300/20 rounded-2xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-3.5 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200/50 dark:border-surface-700/50 shadow-lg shadow-surface-100/50 dark:shadow-surface-900/50 ring-1 ring-white/50 dark:ring-surface-700/50">
                        <i data-lucide="user-circle" class="w-8 h-8 text-theme-600 dark:text-theme-400"></i>
                    </div>
                </div>
                
                {{-- Title & Subtitle --}}
                <div>
                    <h1 class="text-3xl font-bold text-surface-900 dark:text-white tracking-tight mb-2">
                        Profil Saya
                    </h1>
                    <nav class="flex items-center gap-2 text-sm font-medium text-surface-500 dark:text-surface-400">
                        <a href="{{ route('dashboard') }}" class="hover:text-theme-600 transition-colors flex items-center gap-1.5">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        </a>
                        <i data-lucide="chevron-right" class="w-3 h-3 text-surface-300 dark:text-surface-600"></i>
                        <span class="text-theme-600 dark:text-theme-400">Pengaturan Akun</span>
                    </nav>
                </div>
            </div>

            {{-- Modern Server Time Widget --}}
            <div class="hidden lg:flex items-center gap-4 px-5 py-2.5 bg-white/50 dark:bg-surface-800/50 backdrop-blur-md border border-surface-200/60 dark:border-surface-700/60 rounded-2xl shadow-lg shadow-surface-200/10 dark:shadow-surface-900/10 hover:shadow-xl hover:scale-[1.02] hover:bg-white dark:hover:bg-surface-800 hover:border-theme-500/20 dark:hover:border-theme-500/20 transition-all duration-300 group/clock"
                 x-data="{
                    timestamp: {{ now()->timestamp * 1000 }},
                    hours: '00',
                    minutes: '00',
                    seconds: '00',
                    dayName: '',
                    fullDate: '',
                    init() {
                        this.update();
                        setInterval(() => {
                            this.timestamp += 1000;
                            this.update();
                        }, 1000);
                    },
                    update() {
                        const date = new Date(this.timestamp);
                        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        
                        this.dayName = days[date.getDay()];
                        this.fullDate = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
                        this.hours = String(date.getHours()).padStart(2, '0');
                        this.minutes = String(date.getMinutes()).padStart(2, '0');
                        this.seconds = String(date.getSeconds()).padStart(2, '0');
                    }
                 }"
                 x-cloak>
                
                {{-- Animated Icon --}}
                <div class="relative">
                    <div class="absolute inset-0 bg-theme-500 rounded-full blur opacity-0 group-hover/clock:opacity-20 transition-opacity duration-500"></div>
                    <div class="relative p-2.5 bg-gradient-to-br from-theme-50 to-theme-100 dark:from-theme-900/40 dark:to-theme-800/40 rounded-xl text-theme-600 dark:text-theme-400 group-hover/clock:rotate-12 transition-transform duration-500">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="h-8 w-px bg-surface-200 dark:bg-surface-700/50"></div>

                {{-- Time Display --}}
                <div class="flex flex-col">
                    <div class="flex items-baseline gap-0.5">
                         <span class="text-xl font-bold font-space text-surface-900 dark:text-white tracking-tight" x-text="hours"></span>
                         <span class="text-theme-500 font-bold animate-pulse px-0.5">:</span>
                         <span class="text-xl font-bold font-space text-surface-900 dark:text-white tracking-tight" x-text="minutes"></span>
                         <span class="text-surface-400 font-bold px-0.5">:</span>
                         <span class="text-base font-medium font-space text-surface-500 dark:text-surface-400" x-text="seconds"></span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs font-medium text-surface-500 dark:text-surface-400">
                        <span x-text="dayName" class="text-theme-600 dark:text-theme-400"></span>
                        <span class="w-1 h-1 rounded-full bg-surface-300 dark:bg-surface-600"></span>
                        <span x-text="fullDate"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Content --}}
    <div x-data="profilePage()" class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8">
            
            {{-- Left Column - Photo & Quick Stats --}}
            <div class="lg:col-span-4 space-y-4 sm:space-y-6">
                {{-- Profile Photo Card --}}
                @include('profile.partials.photo-card')
                
                {{-- Quick Stats Card --}}
                @include('profile.partials.stats-card')
            </div>

            {{-- Right Column - Profile Details --}}
            <div class="lg:col-span-8 space-y-4 sm:space-y-6">
                {{-- Profile Information --}}
                @include('profile.partials.info-card')
                
                {{-- Security Settings --}}
                @include('profile.partials.security-card')
                
                {{-- Recent Activity --}}
                @include('profile.partials.activity-card')
            </div>
        </div>

        {{-- Bulk Action Bar --}}
        @include('profile.partials.bulk-action-bar')

        {{-- Hidden File Input for Photo Upload --}}
        <input type="file" 
               id="photoInput"
               @change="handleFileSelect($event)"
               accept="image/jpeg,image/png,image/gif,image/webp"
               class="hidden"
               x-ref="photoInput">
    </div>
@endsection

@push('scripts')
    @include('profile.partials.scripts')
@endpush
