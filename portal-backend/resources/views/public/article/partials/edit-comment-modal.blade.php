<template x-teleport="body">
    <div x-show="isEditing" 
         class="fixed inset-0 z-[9999] flex items-center justify-center h-screen w-screen px-4"
         style="display: none;">
        
        {{-- Backdrop: Full screen, blur, blocks interaction with background --}}
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity"></div>

        {{-- Modal Content --}}
        <div x-show="isEditing"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4"
             class="relative w-full max-w-lg bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl overflow-hidden z-10 flex flex-col">
            
            <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-900">
                <h3 class="text-white text-lg font-bold">Edit Komentar</h3>
                {{-- No close button (X) as requested --}}
            </div>

            <div class="p-6 bg-slate-900">
                <textarea x-model="editCommentText" rows="5" 
                          class="w-full bg-slate-950 border border-slate-800 rounded-xl text-white text-sm p-4 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none resize-none placeholder-slate-600 leading-relaxed"
                          placeholder="Tulis perubahan komentar Anda..."></textarea>
                
                <p x-show="editError" x-text="editError" class="mt-3 text-red-400 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span x-text="editError"></span>
                </p>
            </div>

            <div class="p-6 border-t border-slate-800 bg-slate-900/50 flex justify-end gap-3">
                <button @click="closeEditModal" 
                        class="px-5 py-2.5 text-slate-300 font-bold text-sm hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    Batal
                </button>
                <button @click="submitEdit()" 
                        :disabled="isSubmittingEdit || !editCommentText.trim()"
                        class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-bold text-sm rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transform hover:-translate-y-0.5">
                    <span x-show="isSubmittingEdit" class="animate-spin w-4 h-4 border-2 border-white/20 border-t-white rounded-full"></span>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</template>
