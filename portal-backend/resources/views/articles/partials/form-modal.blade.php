{{-- Form Modal (Create/Edit) --}}
<template x-teleport="body">
    <div 
        x-show="showFormModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showFormModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-surface-900/40 backdrop-blur-sm transition-opacity"
            @click="closeFormModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-end sm:items-center justify-center sm:p-4">
            <div 
                x-show="showFormModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative transform overflow-hidden bg-white dark:bg-surface-900 text-left shadow-2xl transition-all w-full sm:max-w-6xl h-[95vh] sm:h-[90vh] flex flex-col sm:rounded-3xl border-t sm:border border-white/20 ring-1 ring-black/5 dark:ring-white/10"
                @click.stop
            >
                {{-- Header --}}
                <div class="bg-white/80 dark:bg-surface-900/80 backdrop-blur-md border-b border-surface-200/50 dark:border-surface-700/50 px-4 sm:px-8 py-4 sm:py-5 flex-shrink-0 flex items-center justify-between z-20">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-theme-600 text-white flex items-center justify-center shadow-lg shadow-theme-500/20 shrink-0">
                            <i :data-lucide="formMode === 'create' ? 'plus' : 'pen-line'" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white tracking-tight leading-tight" x-text="formMode === 'create' ? 'Buat Berita Baru' : 'Edit Berita'"></h3>
                            <p class="hidden sm:block text-sm text-surface-500 dark:text-surface-400 font-medium">Manajamen konten artikel & publikasi</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <template x-if="injectionDetected">
                            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-rose-500/10 dark:bg-rose-500/20 border border-rose-300 dark:border-rose-700 mr-2 animate-pulse">
                                <i data-lucide="shield-x" class="w-4 h-4 text-rose-500"></i>
                                <span class="text-xs font-bold text-rose-600 dark:text-rose-400" x-text="detectedThreats.length + ' Ancaman Terdeteksi'"></span>
                            </div>
                        </template>

                        <button 
                            type="button" 
                            @click="closeFormModal()"
                            class="px-3 py-2 sm:px-4 sm:py-2.5 text-xs sm:text-sm font-semibold text-surface-500 dark:text-surface-400 hover:text-surface-900 dark:hover:text-white hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-all"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            form="articleForm"
                            :disabled="formLoading || injectionDetected"
                            class="group relative overflow-hidden px-4 py-2 sm:px-6 sm:py-2.5 bg-theme-600 hover:bg-theme-500 text-white font-bold rounded-xl shadow-lg shadow-theme-500/30 hover:shadow-theme-500/50 hover:scale-[1.02] active:scale-95 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-2 text-xs sm:text-sm"
                        >
                            <div x-show="formLoading" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span x-text="formMode === 'create' ? 'Terbitkan' : 'Simpan'"></span>
                            
                            {{-- Shine Effect --}}
                            <div class="absolute inset-0 -translate-x-[100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent z-10"></div>
                        </button>
                    </div>
                </div>

                {{-- Main Layout --}}
                <div class="flex flex-1 overflow-hidden relative">
                    
                    {{-- Sidebar Navigation --}}
                    {{-- Mobile: w-20 (Icons Only), Desktop: w-72 (Full) --}}
                    <div class="w-16 sm:w-20 md:w-72 flex-shrink-0 bg-surface-50/50 dark:bg-surface-900/50 border-r border-surface-200/50 dark:border-surface-700/50 overflow-y-auto backdrop-blur-sm p-2 sm:p-4 md:p-6 flex flex-col justify-between pb-10">
                        
                        <nav class="space-y-2">
                            <template x-for="item in [
                                { id: 'content', icon: 'file-text', label: 'Konten Utama', desc: 'Judul & Artikel' },
                                { id: 'media', icon: 'image', label: 'Media & Visual', desc: 'Thumbnail & Aset' },
                                { id: 'seo', icon: 'globe', label: 'SEO & Social', desc: 'Optimasi Mesin Pencari' },
                                { id: 'settings', icon: 'sliders-horizontal', label: 'Pengaturan', desc: 'Status & Kategori' }
                            ]">
                                <button 
                                    type="button"
                                    @click="activeTab = item.id"
                                    class="w-full flex items-center gap-0 md:gap-4 px-0 md:px-4 py-3 md:py-3.5 rounded-xl md:rounded-2xl transition-all duration-300 group relative overflow-hidden justify-center md:justify-start"
                                    :class="activeTab === item.id ? 'bg-transparent' : 'bg-transparent'"
                                >
                                    <div 
                                        class="absolute left-0 top-0 bottom-0 w-1 bg-theme-500 rounded-l-full transition-all duration-300"
                                        :class="activeTab === item.id ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50'"
                                    ></div>
                                    
                                    <div 
                                        class="p-2.5 rounded-xl transition-colors duration-300 relative"
                                        :class="activeTab === item.id ? 'bg-transparent text-theme-600 dark:text-theme-400' : 'bg-transparent text-surface-400 group-hover:text-surface-600 dark:group-hover:text-surface-300'"
                                    >
                                        <i :data-lucide="item.icon" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                                    </div>
                                    
                                    {{-- Text Labels: Hidden on Mobile --}}
                                    <div class="hidden md:block text-left">
                                        <p 
                                            class="text-sm font-bold transition-colors duration-300"
                                            :class="activeTab === item.id ? 'text-theme-600 dark:text-theme-400' : 'text-surface-600 dark:text-surface-400'"
                                            x-text="item.label"
                                        ></p>
                                        <p class="text-[10px] text-surface-400 dark:text-surface-500 font-medium" x-text="item.desc"></p>
                                    </div>
                                </button>
                            </template>
                        </nav>

                        {{-- Audit Log --}}
                        <template x-if="formMode === 'edit' && auditInfo">
                            <div class="hidden md:block mt-6 pt-6 border-t border-surface-200 dark:border-surface-700/50">
                                <div class="space-y-4">
                                    <div class="group flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-surface-800/50 border border-surface-100 dark:border-surface-800 shadow-sm">
                                        <div class="h-8 w-8 rounded-full bg-surface-100 dark:bg-surface-700 flex items-center justify-center text-xs font-bold text-surface-600 dark:text-surface-300">
                                            <i data-lucide="user" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-surface-400">Created by</p>
                                            <p class="text-xs font-semibold text-surface-900 dark:text-white" x-text="auditInfo.created_by"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-surface-400">Last updated: <span x-text="auditInfo.updated_at ? formatDate(auditInfo.updated_at) : '-'"></span></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Content Scroll Area --}}
                    <div class="flex-1 overflow-y-auto bg-white dark:bg-surface-900 relative scroll-smooth" id="form-scroll-container">
                        <form id="articleForm" @submit.prevent="submitForm()" class="p-4 sm:p-8 pb-10 max-w-4xl mx-auto space-y-6 sm:space-y-8">
                            
                            {{-- Tab 1: Content --}}
                            <div x-show="activeTab === 'content'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6 sm:space-y-8">
                                
                                {{-- Modern Title Input --}}
                                <div class="group space-y-3 sm:space-y-4">
                                    <input 
                                        type="text"
                                        x-model="formData.title"
                                        @input="generateSlug()"
                                        placeholder="Tulis Judul..."
                                        class="w-full px-0 py-2 sm:py-4 bg-transparent border-0 border-b-2 border-surface-100 dark:border-surface-800 text-2xl sm:text-4xl font-black text-surface-900 dark:text-white placeholder-surface-300/50 focus:ring-0 focus:border-theme-500 transition-all duration-300"
                                    >
                                    <div class="flex items-center gap-2 sm:gap-3 px-3 py-2 bg-surface-50 dark:bg-surface-800/50 rounded-lg group-focus-within:bg-theme-50 dark:group-focus-within:bg-theme-900/10 transition-colors duration-300 overflow-hidden">
                                        <i data-lucide="link" class="w-3 h-3 text-surface-400 group-focus-within:text-theme-500 shrink-0"></i>
                                        <span class="text-[10px] sm:text-xs text-surface-400 shrink-0">btikp.cloud/berita/</span>
                                        <input 
                                            type="text" 
                                            x-model="formData.slug" 
                                            class="bg-transparent border-0 p-0 text-[10px] sm:text-xs text-surface-500 font-mono focus:ring-0 focus:text-theme-600 dark:focus:text-theme-400 w-full min-w-[50px]"
                                            placeholder="slug-auto"
                                        >
                                    </div>
                                    <template x-if="formErrors.title">
                                        <p class="text-sm font-medium text-rose-500 flex items-center gap-2 animate-shake">
                                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                            <span x-text="formErrors.title[0]"></span>
                                        </p>
                                    </template>
                                </div>

                                {{-- Summary --}}
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-surface-700 dark:text-surface-300">Ringkasan Berita</label>
                                    <textarea 
                                        x-model="formData.excerpt"
                                        rows="3"
                                        class="w-full px-4 sm:px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-0 ring-1 ring-surface-200 dark:ring-surface-800 rounded-2xl text-sm focus:ring-2 focus:ring-theme-500 transition-all resize-none dark:text-white placeholder-surface-400"
                                        placeholder="Tulis ringkasan singkat..."
                                    ></textarea>
                                </div>

                                {{-- Editor --}}
                                <div class="space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <label class="text-sm font-bold text-surface-700 dark:text-surface-300 flex items-center gap-2">
                                            <i data-lucide="edit-3" class="w-4 h-4 text-theme-500"></i>
                                            Editor Konten
                                        </label>
                                        
                                        {{-- Security Status Indicator --}}
                                        <div x-show="formData.content" class="flex items-center gap-2 self-start sm:self-auto">
                                            {{-- Safe Indicator --}}
                                            <template x-if="!injectionDetected && formData.content.length > 50">
                                                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                                                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                                                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Konten Aman</span>
                                                </div>
                                            </template>
                                            
                                            {{-- Threat Detected Indicator --}}
                                            <template x-if="injectionDetected">
                                                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 animate-pulse">
                                                    <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-rose-500"></i>
                                                    <span class="text-xs font-bold text-rose-600 dark:text-rose-400" x-text="detectedThreats.length + ' Ancaman'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    {{-- SECURITY ALERT PANEL - Shown when threats detected --}}
                                    <template x-if="injectionDetected">
                                        <div class="rounded-2xl border-2 border-rose-300 dark:border-rose-700 bg-gradient-to-br from-rose-50 to-orange-50 dark:from-rose-900/30 dark:to-orange-900/20 overflow-hidden shadow-lg shadow-rose-500/10">
                                            {{-- Alert Header --}}
                                            <div class="bg-rose-500 dark:bg-rose-600 px-4 py-3 flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="p-2 bg-white/20 rounded-lg">
                                                        <i data-lucide="shield-x" class="w-5 h-5 text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-sm font-bold text-white">⚠️ Terdeteksi Konten Berbahaya!</h4>
                                                        <p class="text-xs text-rose-100">Sistem mendeteksi potensi serangan injeksi atau konten terlarang</p>
                                                    </div>
                                                </div>
                                                <span class="px-3 py-1 bg-white/20 text-white text-xs font-bold rounded-full" x-text="detectedThreats.length + ' ancaman'"></span>
                                            </div>
                                            
                                            {{-- Threat List --}}
                                            <div class="p-4 space-y-3 max-h-48 overflow-y-auto">
                                                <template x-for="(threat, index) in detectedThreats" :key="index">
                                                    <div class="flex items-start gap-3 p-3 rounded-xl border-l-4 transition-all hover:scale-[1.01]" :class="getSeverityBorderColor(threat.severity)">
                                                        <div class="flex-shrink-0">
                                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[10px] font-bold" :class="getSeverityColor(threat.severity)" x-text="index + 1"></span>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span class="text-xs font-bold px-2 py-0.5 rounded-full" :class="getSeverityColor(threat.severity)" x-text="threat.category"></span>
                                                                <code class="text-[10px] px-2 py-0.5 bg-surface-200 dark:bg-surface-700 text-rose-600 dark:text-rose-400 rounded font-mono" x-text="threat.keyword"></code>
                                                            </div>
                                                            <p class="text-xs text-surface-600 dark:text-surface-400 mt-1" x-text="threat.description"></p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                            
                                            {{-- Action Buttons --}}
                                            <div class="px-4 py-3 bg-surface-100 dark:bg-surface-800/50 border-t border-rose-200 dark:border-rose-800 flex flex-col sm:flex-row gap-2">
                                                <button 
                                                    type="button"
                                                    @click="previewSanitization()"
                                                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-300 dark:border-surface-600 text-surface-700 dark:text-surface-300 rounded-xl text-sm font-semibold hover:bg-surface-50 dark:hover:bg-surface-700 transition-all"
                                                >
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                    Preview Hasil Pembersihan
                                                </button>
                                                <button 
                                                    type="button"
                                                    @click="applySanitization()"
                                                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all"
                                                >
                                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                                    Bersihkan Semua Ancaman
                                                </button>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Custom Toolbar --}}
                                    <div class="relative group rounded-2xl overflow-hidden ring-1 ring-surface-200 dark:ring-surface-700 shadow-sm focus-within:shadow-lg focus-within:shadow-theme-500/10 transition-all duration-300 bg-white dark:bg-surface-800/50" :class="injectionDetected ? 'ring-2 ring-rose-400 dark:ring-rose-600' : ''">
                                        <trix-toolbar id="wysiwyg-toolbar">
                                            <div class="flex flex-wrap gap-2 p-2 bg-surface-50 dark:bg-surface-800 border-b border-surface-200 dark:border-surface-700">
                                                {{-- Group 1: Text Formatting --}}
                                                <div class="flex items-center gap-1 p-1 bg-white dark:bg-surface-900 rounded-lg shadow-sm border border-surface-200 dark:border-surface-700">
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="bold" title="Bold">
                                                        <i data-lucide="bold" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="italic" title="Italic">
                                                        <i data-lucide="italic" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="strike" title="Strike">
                                                        <i data-lucide="strikethrough" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="href" data-trix-action="link" title="Link">
                                                        <i data-lucide="link" class="w-4 h-4"></i>
                                                    </button>
                                                </div>

                                                {{-- Group 2: Blocks --}}
                                                <div class="flex items-center gap-1 p-1 bg-white dark:bg-surface-900 rounded-lg shadow-sm border border-surface-200 dark:border-surface-700">
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="heading1" title="Heading">
                                                        <i data-lucide="heading" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="quote" title="Quote">
                                                        <i data-lucide="quote" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="code" title="Code">
                                                        <i data-lucide="code" class="w-4 h-4"></i>
                                                    </button>
                                                </div>

                                                {{-- Group 3: Lists & Indentation --}}
                                                <div class="flex items-center gap-1 p-1 bg-white dark:bg-surface-900 rounded-lg shadow-sm border border-surface-200 dark:border-surface-700">
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="bullet" title="Bullets">
                                                        <i data-lucide="list" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="number" title="Numbers">
                                                        <i data-lucide="list-ordered" class="w-4 h-4"></i>
                                                    </button>
                                                    <div class="w-px h-4 bg-surface-200 dark:bg-surface-700 mx-1"></div>
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors disabled:opacity-30" data-trix-action="decreaseNestingLevel" title="Decrease Level">
                                                        <i data-lucide="outdent" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors disabled:opacity-30" data-trix-action="increaseNestingLevel" title="Increase Level">
                                                        <i data-lucide="indent" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                                
                                                {{-- Group 4: History --}}
                                                <div class="ml-auto flex items-center gap-1 p-1 bg-white dark:bg-surface-900 rounded-lg shadow-sm border border-surface-200 dark:border-surface-700">
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors disabled:opacity-30 disabled:cursor-not-allowed" data-trix-action="undo" title="Undo">
                                                        <i data-lucide="undo" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors disabled:opacity-30 disabled:cursor-not-allowed" data-trix-action="redo" title="Redo">
                                                        <i data-lucide="redo" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </trix-toolbar>

                                        <input id="x" type="hidden" name="content" x-model="formData.content">
                                        <trix-editor 
                                            toolbar="wysiwyg-toolbar"
                                            input="x" 
                                            class="trix-content min-h-[300px] sm:min-h-[500px] bg-white dark:bg-surface-800/50 px-4 sm:px-6 py-4 outline-none border-none dark:text-white prose dark:prose-invert max-w-none"
                                            style="overflow-y: auto;"
                                            x-on:trix-change="formData.content = $event.target.value; checkContentSafety($event.target.value)"
                                            x-on:trix-file-accept="$event.preventDefault()" 
                                        ></trix-editor>
                                    </div>
                                    <template x-if="formErrors.content">
                                        <p class="text-xs text-rose-500 mt-1 pl-2" x-text="formErrors.content[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Tab 2: Media --}}
                            <div x-show="activeTab === 'media'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                <div class="bg-surface-50 dark:bg-surface-800/30 rounded-3xl p-4 sm:p-8 border border-surface-100 dark:border-surface-700/50 space-y-6">
                                    <div class="text-center space-y-2">
                                        <div class="inline-flex items-center justify-center p-3 bg-theme-100 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 rounded-2xl mb-2">
                                            <i data-lucide="image-plus" class="w-8 h-8"></i>
                                        </div>
                                        <h4 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Assets Gambar</h4>
                                        <p class="text-xs sm:text-sm text-surface-500 max-w-sm mx-auto">Upload thumbnail berita.</p>
                                    </div>

                                    {{-- Drag & Drop Upload --}}
                                    <div 
                                        x-data="{ isDragging: false }"
                                        @dragover.prevent="isDragging = true"
                                        @dragleave.prevent="isDragging = false"
                                        @drop.prevent="
                                            isDragging = false;
                                            const file = $event.dataTransfer.files[0];
                                            if (file) {
                                                formData.thumbnail = file;
                                                formData.thumbnail_url = URL.createObjectURL(file);
                                            }
                                        "
                                        class="relative w-full aspect-video rounded-3xl border-3 border-dashed transition-all duration-500 ease-out overflow-hidden group"
                                        :class="isDragging 
                                            ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/10 scale-[1.02] shadow-2xl shadow-theme-500/10 ring-4 ring-theme-500/20' 
                                            : 'border-surface-300 dark:border-surface-600 bg-surface-100 dark:bg-surface-800 hover:border-theme-400 hover:bg-surface-50 dark:hover:bg-surface-700'"
                                    >
                                        <input 
                                            type="file" 
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                            accept="image/*"
                                            @change="
                                                const file = $event.target.files[0];
                                                if (file) {
                                                    formData.thumbnail = file;
                                                    formData.thumbnail_url = URL.createObjectURL(file);
                                                }
                                            "
                                        >
                                        
                                        {{-- Empty State --}}
                                        <template x-if="!formData.thumbnail_url">
                                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-transform duration-300 text-center p-4" :class="isDragging ? 'scale-110' : 'scale-100'">
                                                <div class="p-3 sm:p-4 rounded-full bg-white dark:bg-surface-700 shadow-sm mb-4">
                                                    <i data-lucide="upload-cloud" class="w-6 h-6 sm:w-8 sm:h-8 text-surface-400 group-hover:text-theme-500 transition-colors"></i>
                                                </div>
                                                <p class="text-sm font-semibold text-surface-700 dark:text-surface-300">
                                                    <span class="text-theme-600 dark:text-theme-400">Klik Upload</span> / Drop
                                                </p>
                                            </div>
                                        </template>

                                        {{-- Preview --}}
                                        <template x-if="formData.thumbnail_url">
                                            <div class="absolute inset-0 w-full h-full bg-black/5">
                                                <img :src="formData.thumbnail_url" class="absolute inset-0 w-full h-full object-cover">
                                                
                                                {{-- Hover Overlay --}}
                                                <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center p-6 text-white z-20 pointer-events-none">
                                                    <i data-lucide="refresh-cw" class="w-8 h-8 mb-2 drop-shadow-lg"></i>
                                                    <button type="button" @click.stop.prevent="window.open(formData.thumbnail_url)" class="pointer-events-auto mt-4 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-full text-xs font-medium backdrop-blur-sm transition-colors border border-white/50">
                                                        Lihat Fullsize
                                                    </button>
                                                </div>

                                                <button 
                                                    type="button" 
                                                    @click.stop.prevent="formData.thumbnail = null; formData.thumbnail_url = ''"
                                                    class="absolute top-4 right-4 p-2 bg-rose-500 text-white rounded-xl shadow-lg hover:bg-rose-600 transition-all z-30 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 hover:scale-110"
                                                >
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <template x-if="formErrors.thumbnail">
                                        <p class="text-center text-sm font-medium text-rose-500" x-text="formErrors.thumbnail[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Tab 3: SEO --}}
                            <div x-show="activeTab === 'seo'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="space-y-6">
                                        <div>
                                            <h4 class="text-lg font-bold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                                                <i data-lucide="search" class="w-5 h-5 text-theme-500"></i>
                                                SEO Setup
                                            </h4>
                                            
                                            <div class="space-y-5">
                                                <div class="group">
                                                    <label class="block text-xs font-semibold uppercase tracking-wider text-surface-500 mb-1.5">Meta Title</label>
                                                    <input type="text" x-model="formData.meta_title" class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white" placeholder="Judul di hasil pencarian">
                                                </div>
                                                <div class="group">
                                                    <label class="block text-xs font-semibold uppercase tracking-wider text-surface-500 mb-1.5">Meta Description</label>
                                                    <textarea x-model="formData.meta_description" rows="4" class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all resize-none dark:text-white" placeholder="Deskripsi di hasil pencarian"></textarea>
                                                </div>
                                                <div class="group">
                                                    <label class="block text-xs font-semibold uppercase tracking-wider text-surface-500 mb-1.5">Meta Keywords</label>
                                                    <input type="text" x-model="formData.meta_keywords" class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white" placeholder="pisahkan, dengan, koma">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Live Preview --}}
                                    <div class="pt-8 lg:pt-0">
                                        <div class="sticky top-4">
                                            <label class="block text-xs font-semibold uppercase tracking-wider text-surface-500 mb-3 text-center lg:text-left">Preview Social Media</label>
                                            
                                            {{-- Card Preview --}}
                                            <div class="bg-white dark:bg-surface-800 rounded-3xl overflow-hidden shadow-2xl border border-surface-100 dark:border-surface-700 transform transition-transform hover:scale-[1.02] duration-500">
                                                <div class="h-48 bg-surface-100 dark:bg-surface-700 w-full relative overflow-hidden group">
                                                    <template x-if="formData.thumbnail_url">
                                                        <img :src="formData.thumbnail_url" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                                    </template>
                                                    <template x-if="!formData.thumbnail_url">
                                                        <div class="absolute inset-0 flex flex-col items-center justify-center text-surface-400">
                                                            <i data-lucide="image" class="w-12 h-12 opacity-50 mb-2"></i>
                                                            <span class="text-xs">No Image</span>
                                                        </div>
                                                    </template>
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                                                </div>
                                                <div class="p-6 relative">
                                                    <div class="absolute -top-10 right-6 w-12 h-12 bg-white dark:bg-surface-800 rounded-full flex items-center justify-center shadow-lg border-2 border-white dark:border-surface-700">
                                                        <span class="text-xs font-bold text-theme-600">BTIKP</span>
                                                    </div>
                                                    <p class="text-[10px] items-center flex gap-1 text-surface-400 uppercase tracking-widest font-bold mb-2">
                                                        btikp.cloud <span class="w-1 h-1 rounded-full bg-surface-300"></span> News
                                                    </p>
                                                    <h3 class="font-bold text-lg text-surface-900 dark:text-white leading-tight mb-2 line-clamp-2" x-text="formData.meta_title || formData.title || 'Judul Berita Anda'"></h3>
                                                    <p class="text-sm text-surface-600 dark:text-surface-400 line-clamp-3 leading-relaxed" x-text="formData.meta_description || formData.excerpt || 'Deskripsi berita akan muncul disini...'">
                                                    </p>
                                                </div>
                                                <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-100 dark:border-surface-700/50 flex items-center justify-between text-xs text-surface-500">
                                                    <span>Baca Selengkapnya</span>
                                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 4: Settings --}}
                            <div x-show="activeTab === 'settings'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-6">
                                        <div class="bg-surface-50 dark:bg-surface-800/50 p-6 rounded-2xl border border-surface-100 dark:border-surface-700">
                                            <h5 class="text-sm font-bold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                                                <i data-lucide="tag" class="w-4 h-4"></i> Klasifikasi
                                            </h5>
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-xs font-medium text-surface-500 mb-1.5">Kategori</label>
                                                    <select x-model="formData.category_id" class="w-full px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white">
                                                        <option value="">Pilih Kategori</option>
                                                        <template x-for="cat in categories" :key="cat.id">
                                                            <option :value="cat.id" x-text="cat.name"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-surface-500 mb-1.5">Waktu Baca</label>
                                                    <input type="number" x-model="formData.read_time" placeholder="Auto" class="w-full px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-6">
                                        <div class="bg-surface-50 dark:bg-surface-800/50 p-6 rounded-2xl border border-surface-100 dark:border-surface-700">
                                            <h5 class="text-sm font-bold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                                                <i data-lucide="eye" class="w-4 h-4"></i> Visibilitas
                                            </h5>
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-xs font-medium text-surface-500 mb-1.5">Status Publikasi</label>
                                                    <select x-model="formData.status" class="w-full px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white">
                                                        <option value="draft">Draft (Konsep)</option>
                                                        <option value="pending">Pending Review</option>
                                                        <option value="published">Published (Terbit)</option>
                                                    </select>
                                                </div>
                                                <div x-show="formData.status === 'published'" x-transition>
                                                    <label class="block text-xs font-medium text-surface-500 mb-1.5">Waktu Terbit</label>
                                                    <input type="datetime-local" x-model="formData.published_at" class="w-full px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-br from-theme-50 to-white dark:from-theme-900/10 dark:to-surface-800 border border-theme-100 dark:border-theme-900/20 rounded-2xl p-6">
                                    <h5 class="text-sm font-bold text-surface-900 dark:text-white mb-4">Featured Options</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="relative flex items-start p-4 hover:bg-white/50 dark:hover:bg-surface-700/50 rounded-xl cursor-pointer transition-all border border-transparent hover:border-theme-200 dark:hover:border-theme-800">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" x-model="formData.is_pinned" class="w-5 h-5 text-theme-600 rounded border-gray-300 focus:ring-theme-500">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <span class="font-bold text-surface-900 dark:text-white">Pin to Home</span>
                                                <p class="text-xs text-surface-500 mt-1">Sematkan berita ini di posisi paling atas halaman depan.</p>
                                            </div>
                                        </label>

                                        <label class="relative flex items-start p-4 hover:bg-white/50 dark:hover:bg-surface-700/50 rounded-xl cursor-pointer transition-all border border-transparent hover:border-theme-200 dark:hover:border-theme-800">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" x-model="formData.is_headline" class="w-5 h-5 text-theme-600 rounded border-gray-300 focus:ring-theme-500">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <span class="font-bold text-surface-900 dark:text-white">Jadikan Headline</span>
                                                <p class="text-xs text-surface-500 mt-1">Tampilkan sebagai berita utama dengan layout khusus.</p>
                                            </div>
                                        </label>
                                    </div>
                            </div>
                        </form>
                    </div>
                


                </div>
            </div>
        </div>
    </div>
</template>

{{-- Auto-Sanitization Preview Modal --}}
<template x-teleport="body">
    <div 
        x-show="showSanitizePreview"
        x-cloak
        class="fixed inset-0 z-[60] overflow-y-auto"
        aria-labelledby="sanitize-preview-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showSanitizePreview"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-surface-900/60 backdrop-blur-sm transition-opacity"
            @click="closeSanitizePreview()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div 
                x-show="showSanitizePreview"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative transform overflow-hidden bg-white dark:bg-surface-900 text-left shadow-2xl transition-all w-full max-w-4xl rounded-3xl border border-white/20 ring-1 ring-black/5 dark:ring-white/10"
                @click.stop
            >
                {{-- Header --}}
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-white/20 rounded-xl">
                            <i data-lucide="sparkles" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 id="sanitize-preview-title" class="text-lg font-bold text-white">Preview Auto-Sanitization</h3>
                            <p class="text-sm text-white/80">Lihat hasil pembersihan konten sebelum diterapkan</p>
                        </div>
                    </div>
                    <button 
                        type="button"
                        @click="closeSanitizePreview()"
                        class="p-2 hover:bg-white/20 rounded-lg transition-colors"
                    >
                        <i data-lucide="x" class="w-5 h-5 text-white"></i>
                    </button>
                </div>

                {{-- Info Banner --}}
                <div class="px-6 py-3 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800 flex items-center gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0"></i>
                    <p class="text-sm text-amber-700 dark:text-amber-300">
                        Teks yang ditandai dengan <span class="bg-rose-200 dark:bg-rose-800 text-rose-600 dark:text-rose-300 px-1.5 py-0.5 rounded text-xs font-mono line-through">[REMOVED]</span> akan dihapus dari konten.
                    </p>
                </div>

                {{-- Preview Content --}}
                <div class="p-6 max-h-[60vh] overflow-y-auto">
                    <div class="prose dark:prose-invert max-w-none p-4 bg-surface-50 dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700" x-html="sanitizedPreviewContent">
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-rose-100 dark:bg-rose-900/30 rounded-full">
                                <i data-lucide="trash-2" class="w-4 h-4 text-rose-500"></i>
                                <span class="text-sm font-semibold text-rose-600 dark:text-rose-400" x-text="detectedThreats.length + ' ancaman akan dihapus'"></span>
                            </div>
                            <div class="text-xs text-surface-500 dark:text-surface-400">
                                Kategori: 
                                <template x-for="category in [...new Set(detectedThreats.map(t => t.category))]" :key="category">
                                    <span class="inline-block px-2 py-0.5 bg-surface-200 dark:bg-surface-700 rounded-full text-surface-600 dark:text-surface-300 mr-1" x-text="category"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="px-6 py-4 bg-white dark:bg-surface-900 border-t border-surface-200 dark:border-surface-700 flex flex-col sm:flex-row gap-3 justify-end">
                    <button 
                        type="button"
                        @click="closeSanitizePreview()"
                        class="px-5 py-2.5 text-sm font-semibold text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-all"
                    >
                        Batal
                    </button>
                    <button 
                        type="button"
                        @click="applySanitization()"
                        class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition-all flex items-center justify-center gap-2"
                    >
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Terapkan Pembersihan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
