@extends('layouts.app')

@section('title', 'Tong Sampah')

@section('content')
<div x-data="trashApp()" x-init="init()">
    {{-- Page Header --}}
    @include('trash.partials.header')

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('trash.partials.filter')

            {{-- Table Section --}}
            @include('trash.partials.table')

            {{-- Pagination Section --}}
            @include('trash.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('trash.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('trash.partials.action-menu')
</div>
@endsection

@push('scripts')
@include('trash.partials.scripts')
@endpush
