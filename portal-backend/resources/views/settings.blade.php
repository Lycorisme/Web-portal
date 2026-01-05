@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    {{-- Page Header --}}
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-theme-gradient p-4 sm:p-6 lg:p-8 mb-4 sm:mb-8 animate-fade-in">
        <div class="absolute top-0 right-0 w-32 sm:w-64 h-32 sm:h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-1/4 w-16 sm:w-32 h-16 sm:h-32 bg-white/20 rounded-full blur-2xl"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 bg-white/20 backdrop-blur-sm rounded-full mb-3 sm:mb-4">
                <i data-lucide="settings" class="w-3.5 sm:w-4 h-3.5 sm:h-4 text-white"></i>
                <span class="text-xs sm:text-sm text-white/90 font-medium">Konfigurasi Sistem</span>
            </div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mb-1 sm:mb-2">Pengaturan Portal</h1>
            <p class="text-white/80 text-xs sm:text-sm lg:text-base max-w-xl">
                Kelola pengaturan umum, tampilan, SEO, media sosial, dan keamanan portal berita Anda.
            </p>
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


