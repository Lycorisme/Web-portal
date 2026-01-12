@extends('public.layouts.public')

@section('meta_title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="min-h-screen pt-28 pb-16 relative" x-data="profilePage()">
    
    {{-- Background Glow --}}
    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[500px] bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto px-6">
        
        {{-- Profile Header Card --}}
        @include('public.profile.partials.profile-header')

        {{-- Main Layout Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Navigation Sidebar --}}
            <div class="lg:col-span-3 sticky top-32 z-20">
                @include('public.profile.partials.navigation-sidebar')
            </div>

            {{-- Tab Contents --}}
            <div class="lg:col-span-9 min-h-[400px]">
                
                {{-- Settings Tab --}}
                <div x-show="activeTab === 'settings'"
                     x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     style="display: none;"
                     class="space-y-8">
                    @include('public.profile.partials.settings-tab')
                </div>

                {{-- Activity Tab --}}
                <div x-show="activeTab === 'activity'"
                     x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     style="display: none;"
                     class="space-y-8">
                    @include('public.profile.partials.activity-tab')
                </div>

                {{-- Security Tab --}}
                <div x-show="activeTab === 'security'"
                     x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     style="display: none;"
                     class="space-y-8">
                    @include('public.profile.partials.security-tab')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('public.profile.partials.profile-scripts')
@endpush
