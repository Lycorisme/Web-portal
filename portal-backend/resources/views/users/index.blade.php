@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div x-data="userApp()" x-init="init()">
    {{-- Enhanced Page Header --}}
    @include('users.partials.header')

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('users.partials.filter')

            {{-- Table Section --}}
            @include('users.partials.table')

            {{-- Pagination Section --}}
            @include('users.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('users.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('users.partials.action-menu')

    {{-- Form Modal (Create/Edit) --}}
    @include('users.partials.form-modal')

    {{-- Detail Modal --}}
    @include('users.partials.detail-modal')
</div>
@endsection

@push('scripts')
@include('users.partials.scripts')
@endpush
