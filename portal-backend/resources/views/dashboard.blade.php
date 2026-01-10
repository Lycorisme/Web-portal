@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6 animate-fade-in">
        {{-- Welcome Banner --}}
        @include('dashboard.partials.welcome-banner')

        {{-- Key Stats Grid --}}
        @include('dashboard.partials.stats-grid')

        {{-- Main Content & Widgets Split --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Main Content Column (Left - 8 cols) --}}
            <div class="lg:col-span-8 flex flex-col gap-6">
                
                {{-- Data Visualization Row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Visitor Charts --}}
                    @include('dashboard.partials.visitor-chart')

                    {{-- Category Charts --}}
                    @include('dashboard.partials.category-distribution')
                </div>

                {{-- Recent News Section --}}
                @include('dashboard.partials.recent-news')
            </div>

            {{-- Right Column (Widgets - 4 cols) --}}
            <div class="lg:col-span-4 flex flex-col gap-6">
                
                {{-- Quick Actions --}}
                @include('dashboard.partials.quick-actions')

                {{-- Security Widget - Admin Only --}}
                @if($stats['is_admin'] ?? false)
                    @include('dashboard.partials.security-widget')
                @endif

                {{-- Activity Log --}}
                @include('dashboard.partials.activity-log')
            </div>
        </div>
    </div>
@endsection
