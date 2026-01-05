@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    {{-- Page Header --}}
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-theme-gradient p-4 sm:p-6 lg:p-8 mb-4 sm:mb-8 animate-fade-in">
        <div class="absolute top-0 right-0 w-32 sm:w-64 h-32 sm:h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-1/4 w-16 sm:w-32 h-16 sm:h-32 bg-white/20 rounded-full blur-2xl"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 bg-white/20 backdrop-blur-sm rounded-full mb-3 sm:mb-4">
                <i data-lucide="user" class="w-3.5 sm:w-4 h-3.5 sm:h-4 text-white"></i>
                <span class="text-xs sm:text-sm text-white/90 font-medium">Profil Pengguna</span>
            </div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mb-1 sm:mb-2">Profil Saya</h1>
            <p class="text-white/80 text-xs sm:text-sm lg:text-base max-w-xl">
                Kelola informasi pribadi, foto profil, dan pengaturan keamanan akun Anda.
            </p>
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
    </div>

    {{-- Photo Upload Modal --}}
    @include('profile.partials.photo-modal')
@endsection

@push('scripts')
    @include('profile.partials.scripts')
@endpush
