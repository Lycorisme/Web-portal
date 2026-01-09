@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    {{-- Page Header --}}
    {{-- Enhanced Page Header --}}
    <div class="relative mb-8 animate-fade-in group">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                {{-- Animated Icon Container --}}
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-theme-500/20 to-theme-300/20 rounded-2xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-3.5 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200/50 dark:border-surface-700/50 shadow-lg shadow-surface-100/50 dark:shadow-surface-900/50 ring-1 ring-white/50 dark:ring-surface-700/50">
                        <i data-lucide="settings" class="w-8 h-8 text-theme-600 dark:text-theme-400"></i>
                    </div>
                </div>
                
                {{-- Title & Subtitle --}}
                <div>
                    <h1 class="text-3xl font-bold text-surface-900 dark:text-white tracking-tight mb-2">
                        Pengaturan Portal
                    </h1>
                    <nav class="flex items-center gap-2 text-sm font-medium text-surface-500 dark:text-surface-400">
                        <a href="{{ route('dashboard') }}" class="hover:text-theme-600 transition-colors flex items-center gap-1.5">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        </a>
                        <i data-lucide="chevron-right" class="w-3 h-3 text-surface-300 dark:text-surface-600"></i>
                        <span class="text-theme-600 dark:text-theme-400">Konfigurasi Sistem</span>
                    </nav>
                </div>
            </div>

            {{-- Modern Server Time Widget --}}
            <div class="hidden lg:flex items-center gap-4 px-5 py-2.5 bg-white/50 dark:bg-surface-800/50 backdrop-blur-md border border-surface-200/60 dark:border-surface-700/60 rounded-2xl shadow-lg shadow-surface-200/10 dark:shadow-surface-900/10 hover:shadow-xl hover:scale-[1.02] hover:bg-white dark:hover:bg-surface-800 hover:border-theme-500/20 dark:hover:border-theme-500/20 transition-all duration-300 group/clock"
                 x-data="{
                    serverOffset: {{ now()->timestamp * 1000 }} - Date.now(),
                    hours: '00',
                    minutes: '00',
                    seconds: '00',
                    dayName: '',
                    fullDate: '',
                    init() {
                        this.update();
                        setInterval(() => {
                            this.update();
                        }, 1000);
                    },
                    getServerTime() {
                        return Date.now() + this.serverOffset;
                    },
                    update() {
                        const date = new Date(this.getServerTime());
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

    {{-- Settings Tabs --}}
    <div x-data="{ activeTab: 'general' }" class="animate-slide-up" style="animation-delay: 0.1s;">
        {{-- Tab Navigation --}}
        {{-- Tab Navigation --}}
        <div class="mb-6 p-1.5 bg-white dark:bg-surface-900/50 rounded-2xl border border-surface-200/50 dark:border-surface-800/50 shadow-sm">
            <div class="flex items-center overflow-x-auto gap-2 p-0.5 scrollbar-hide">
                <button @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="globe" class="w-4 h-4"></i>
                    <span>Umum</span>
                </button>
                
                <button @click="activeTab = 'seo'"
                    :class="activeTab === 'seo' ? 'bg-gradient-to-r from-accent-cyan to-accent-emerald text-white shadow-lg shadow-accent-cyan/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>SEO</span>
                </button>

                <button @click="activeTab = 'social'"
                    :class="activeTab === 'social' ? 'bg-gradient-to-r from-accent-violet to-pink-500 text-white shadow-lg shadow-accent-violet/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="share-2" class="w-4 h-4"></i>
                    <span>Sosial</span>
                </button>

                <button @click="activeTab = 'appearance'"
                    :class="activeTab === 'appearance' ? 'bg-gradient-to-r from-accent-amber to-accent-rose text-white shadow-lg shadow-accent-amber/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="palette" class="w-4 h-4"></i>
                    <span>Tampilan</span>
                </button>

                <button @click="activeTab = 'media'"
                    :class="activeTab === 'media' ? 'bg-gradient-to-r from-teal-500 to-accent-cyan text-white shadow-lg shadow-teal-500/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="image" class="w-4 h-4"></i>
                    <span>Media</span>
                </button>

                <button @click="activeTab = 'letterhead'"
                    :class="activeTab === 'letterhead' ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Kop Surat</span>
                </button>

                <button @click="activeTab = 'security'"
                    :class="activeTab === 'security' ? 'bg-gradient-to-r from-surface-800 to-surface-900 text-white shadow-lg shadow-surface-800/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    <span>Keamanan</span>
                </button>
            </div>
        </div>

        <form id="settingsForm" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- General Settings Tab --}}
            @include('settings.partials.general')

            {{-- SEO Settings Tab --}}
            @include('settings.partials.seo')

            {{-- Social Media Settings Tab --}}
            @include('settings.partials.social')

            {{-- Appearance Settings Tab --}}
            @include('settings.partials.appearance')

            {{-- Media Settings Tab --}}
            @include('settings.partials.media')

            {{-- Letterhead (Kop Surat) Settings Tab --}}
            @include('settings.partials.letterhead')

            {{-- Security Settings Tab --}}
            @include('settings.partials.security')

            {{-- Save Button --}}
            <div class="mt-6 sm:mt-8 flex flex-col-reverse sm:flex-row gap-3 sm:gap-4 sm:justify-end">
                <button type="button" id="resetBtn"
                    class="w-full sm:w-auto px-5 sm:px-6 py-2.5 sm:py-3 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200 text-sm sm:text-base">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 inline mr-2"></i>
                    Reset
                </button>
                <button type="button" id="saveBtn"
                    class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3 bg-theme-gradient text-white rounded-xl font-semibold shadow-theme hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base">
                    <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
@include('settings.partials.scripts')
@endpush


