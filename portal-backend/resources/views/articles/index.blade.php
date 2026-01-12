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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
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
    
    /* Cropper.js Custom Styles */
    .cropper-container {
        border-radius: 1rem;
        overflow: hidden;
    }
    .cropper-view-box,
    .cropper-face {
        border-radius: 0;
    }
    .cropper-view-box {
        outline: 2px solid rgb(16 185 129);
        outline-color: rgb(16 185 129);
    }
    .cropper-line {
        background-color: rgb(16 185 129);
    }
    .cropper-point {
        background-color: rgb(16 185 129);
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .cropper-point.point-se {
        width: 12px;
        height: 12px;
    }
    .cropper-dashed {
        border-color: rgba(255, 255, 255, 0.5);
    }
    .cropper-modal {
        background-color: rgba(0, 0, 0, 0.7);
    }
    
    /* Zoom Range Slider */
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        background: rgb(16 185 129);
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
    }
    input[type="range"]::-moz-range-thumb {
        width: 16px;
        height: 16px;
        background: rgb(16 185 129);
        border-radius: 50%;
        cursor: pointer;
        border: none;
    }
</style>
@endpush

@push('scripts')
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
@include('articles.partials.scripts')
@endpush
