@extends('layouts.app')

@section('title', 'Kelola Berita')

@section('content')
<div x-data="articleApp()" x-init="init()">
    {{-- Enhanced Page Header --}}
    @include('articles.partials.header')

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('articles.partials.filter')

            {{-- Table Section --}}
            @include('articles.partials.table')

            {{-- Pagination Section --}}
            @include('articles.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('articles.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('articles.partials.action-menu')

    {{-- Form Modal (Create/Edit) --}}
    @include('articles.partials.form-modal')

    {{-- Detail Modal --}}
    @include('articles.partials.detail-modal')

    {{-- Statistics Modal --}}
    @include('articles.partials.statistics-modal')

    {{-- Activity Modal --}}
    @include('articles.partials.activity-modal')
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    trix-toolbar [data-trix-button-group="file-tools"] {
        display: none;
    }
    trix-editor {
        min-height: 300px;
    }
    .dark trix-editor {
        background-color: rgb(23 23 23); /* surface-900 */
        border-color: rgb(64 64 64); /* surface-700 */
        color: white;
    }
    .dark trix-toolbar {
        background-color: rgb(38 38 38); /* surface-800 */
        border-color: rgb(64 64 64);
    }
    .dark trix-toolbar .trix-button {
        background-color: rgb(64 64 64);
        border-bottom: none;
        color: white;
    }
    .dark trix-toolbar .trix-button.trix-active {
        background-color: rgb(16 185 129); /* theme-500 */
    }
</style>
@endpush

@push('scripts')
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@include('articles.partials.scripts')
@endpush
