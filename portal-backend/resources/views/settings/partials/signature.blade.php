{{-- Signature (Kaki Surat) Settings Tab --}}
<div x-show="activeTab === 'signature'" 
     x-data="{
        leader_title: '{{ addslashes($rawSettings['leader_title'] ?? '') }}',
        leader_name: '{{ addslashes($rawSettings['leader_name'] ?? '') }}',
        leader_nip: '{{ addslashes($rawSettings['leader_nip'] ?? '') }}',
        signature_cc: `{{ $rawSettings['signature_cc'] ?? '' }}`, // Gunakan backtick untuk multiline
        city: '{{ addslashes($rawSettings['letterhead_city'] ?? $rawSettings['site_city'] ?? 'Kota') }}',
        date: '{{ date('d F Y') }}',
        
        // Size controls (in pixels)
        signatureSize: {{ $rawSettings['signature_size'] ?? 80 }},
        stampSize: {{ $rawSettings['stamp_size'] ?? 85 }},
        
        // Helper untuk preview gambar
        previewImage(event, targetRef, urlProperty) {
            const file = event.target.files[0];
            if (file) {
                // Update URL for local preview
                if (urlProperty) {
                    this[urlProperty] = URL.createObjectURL(file);
                }

                // Update Ref for side preview (legacy support)
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (this.$refs[targetRef]) {
                        this.$refs[targetRef].src = e.target.result;
                        this.$refs[targetRef].style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        },
        
        // State for drag & drop UI
        isDraggingSig: false,
        isDraggingStamp: false,
        sigUrl: '{{ $rawSettings['signature_url'] ?? '' }}',
        stampUrl: '{{ $rawSettings['stamp_url'] ?? '' }}',
        deleteSig: false,
        deleteStamp: false
     }"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30 flex-shrink-0">
                <i data-lucide="pen-tool" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Kaki Surat (Mandatum)</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Pengaturan penanda tangan dokumen, stempel resmi, dan tembusan</p>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="mb-6 sm:mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl">
            <div class="flex gap-3">
                <div class="flex-shrink-0">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div class="text-sm text-emerald-800 dark:text-emerald-200">
                    <p class="font-medium mb-1">Legalitas Dokumen</p>
                    <p class="text-emerald-700 dark:text-emerald-300">Bagian ini menentukan keabsahan dokumen. Pastikan Nama, NIP, dan Jabatan diisi dengan benar. Gunakan gambar PNG transparan untuk Tanda Tangan dan Stempel agar hasil cetak maksimal.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Form Column --}}
            <div class="space-y-6">
                
                {{-- Pejabat Penanda Tangan --}}
                <div class="space-y-4">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-surface-900 dark:text-white pb-2 border-b border-surface-200 dark:border-surface-700">
                        <i data-lucide="user-check" class="w-4 h-4 text-emerald-500"></i>
                        Identitas Pejabat
                    </h3>
                    
                    {{-- Jabatan --}}
                    <div class="space-y-2">
                        <label for="leader_title" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                            Jabatan Resmi <span class="text-accent-rose">*</span>
                        </label>
                        <input type="text" name="leader_title" id="leader_title"
                            x-model="leader_title"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 uppercase font-semibold"
                            placeholder="KEPALA BTIKP PORTAL">
                    </div>

                    {{-- Nama --}}
                    <div class="space-y-2">
                        <label for="leader_name" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                            Nama Lengkap & Gelar <span class="text-accent-rose">*</span>
                        </label>
                        <input type="text" name="leader_name" id="leader_name"
                            x-model="leader_name"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 uppercase font-bold"
                            placeholder="ADMINISTRATOR UTAMA, S.Komp">
                    </div>

                    {{-- NIP --}}
                    <div class="space-y-2">
                        <label for="leader_nip" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                            NIP / Nomor Identitas
                        </label>
                        <input type="text" name="leader_nip" id="leader_nip"
                            x-model="leader_nip"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 font-space"
                            placeholder="19800101 200501 1 001">
                    </div>
                </div>

                {{-- File Uploads --}}
                <div class="space-y-4 pt-4">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-surface-900 dark:text-white pb-2 border-b border-surface-200 dark:border-surface-700">
                        <i data-lucide="stamp" class="w-4 h-4 text-emerald-500"></i>
                        Berkas Digital
                    </h3>

                    {{-- Signature Upload --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                            Tanda Tangan (PNG Transparan)
                        </label>
                        <div 
                            @dragover.prevent="isDraggingSig = true"
                            @dragleave.prevent="isDraggingSig = false"
                            @drop.prevent="
                                isDraggingSig = false;
                                const file = $event.dataTransfer.files[0];
                                if (file) {
                                    $refs.sigInput.files = $event.dataTransfer.files;
                                    previewImage({ target: { files: [file] } }, 'previewSig', 'sigUrl');
                                }
                            "
                            class="relative w-full h-40 rounded-2xl border-2 border-dashed transition-all duration-300 ease-out overflow-hidden group"
                            :class="isDraggingSig 
                                ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/10 scale-[1.02] shadow-xl ring-4 ring-emerald-500/10' 
                                : 'border-surface-300 dark:border-surface-700 bg-surface-50 dark:bg-surface-800/50 hover:border-emerald-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                        >
                            <input 
                                type="file" 
                                name="signature_url" 
                                id="signature_url"
                                x-ref="sigInput"
                                accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                @change="previewImage($event, 'previewSig', 'sigUrl'); deleteSig = false;"
                            >
                            <input type="hidden" name="signature_url_current" value="{{ $rawSettings['signature_url'] ?? '' }}">
                            <input type="hidden" name="delete_signature_url" x-bind:value="deleteSig ? '1' : ''">

                            {{-- Delete Button --}}
                            <button 
                                x-show="sigUrl"
                                @click.stop.prevent="
                                    Swal.fire({
                                        title: 'Hapus Tanda Tangan?',
                                        text: 'Tanda tangan akan dihapus setelah menyimpan pengaturan.',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#ef4444',
                                        cancelButtonColor: '#64748b',
                                        confirmButtonText: 'Ya, Hapus',
                                        cancelButtonText: 'Batal',
                                        reverseButtons: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            sigUrl = '';
                                            deleteSig = true;
                                            $refs.sigInput.value = '';
                                            if ($refs.previewSig) {
                                                $refs.previewSig.src = '';
                                                $refs.previewSig.style.display = 'none';
                                            }
                                        }
                                    });
                                "
                                type="button"
                                class="absolute top-2 right-2 z-30 p-2 bg-rose-500 text-white rounded-xl shadow-lg hover:bg-rose-600 transition-all hover:scale-110 opacity-0 group-hover:opacity-100"
                                title="Hapus Tanda Tangan"
                            >
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>

                            {{-- Empty State --}}
                            <div x-show="!sigUrl" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-transform duration-300" :class="isDraggingSig ? 'scale-110' : 'scale-100'">
                                <div class="p-3 bg-white dark:bg-surface-700 rounded-xl shadow-sm mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="pen-tool" class="w-8 h-8 text-surface-400 group-hover:text-emerald-500 transition-colors"></i>
                                </div>
                                <p class="text-sm font-medium text-surface-600 dark:text-surface-300">Klik atau Drop Tanda Tangan</p>
                                <p class="text-xs text-surface-400 mt-1">PNG Transparan (Max 2MB)</p>
                            </div>

                            {{-- Preview --}}
                            <div x-show="sigUrl" class="absolute inset-0 w-full h-full p-4 flex items-center justify-center bg-surface-100 dark:bg-surface-800">
                                <img :src="sigUrl" class="max-w-full max-h-full object-contain drop-shadow-sm transition-transform duration-500 group-hover:scale-105">
                                
                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center text-white z-20 pointer-events-none">
                                    <i data-lucide="refresh-cw" class="w-8 h-8 mb-2 drop-shadow-md"></i>
                                    <span class="text-xs font-medium bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">Ganti Gambar</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stamp Upload --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                            Stempel Instansi (PNG Transparan)
                        </label>
                        <div 
                            @dragover.prevent="isDraggingStamp = true"
                            @dragleave.prevent="isDraggingStamp = false"
                            @drop.prevent="
                                isDraggingStamp = false;
                                const file = $event.dataTransfer.files[0];
                                if (file) {
                                    $refs.stampInput.files = $event.dataTransfer.files;
                                    previewImage({ target: { files: [file] } }, 'previewStamp', 'stampUrl');
                                }
                            "
                            class="relative w-full h-40 rounded-2xl border-2 border-dashed transition-all duration-300 ease-out overflow-hidden group"
                            :class="isDraggingStamp 
                                ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/10 scale-[1.02] shadow-xl ring-4 ring-emerald-500/10' 
                                : 'border-surface-300 dark:border-surface-700 bg-surface-50 dark:bg-surface-800/50 hover:border-emerald-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                        >
                            <input 
                                type="file" 
                                name="stamp_url" 
                                id="stamp_url" 
                                x-ref="stampInput"
                                accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                @change="previewImage($event, 'previewStamp', 'stampUrl'); deleteStamp = false;"
                            >
                            <input type="hidden" name="stamp_url_current" value="{{ $rawSettings['stamp_url'] ?? '' }}">
                            <input type="hidden" name="delete_stamp_url" x-bind:value="deleteStamp ? '1' : ''">

                            {{-- Delete Button --}}
                            <button 
                                x-show="stampUrl"
                                @click.stop.prevent="
                                    Swal.fire({
                                        title: 'Hapus Stempel?',
                                        text: 'Stempel akan dihapus setelah menyimpan pengaturan.',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#ef4444',
                                        cancelButtonColor: '#64748b',
                                        confirmButtonText: 'Ya, Hapus',
                                        cancelButtonText: 'Batal',
                                        reverseButtons: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            stampUrl = '';
                                            deleteStamp = true;
                                            $refs.stampInput.value = '';
                                            if ($refs.previewStamp) {
                                                $refs.previewStamp.src = '';
                                                $refs.previewStamp.style.display = 'none';
                                            }
                                        }
                                    });
                                "
                                type="button"
                                class="absolute top-2 right-2 z-30 p-2 bg-rose-500 text-white rounded-xl shadow-lg hover:bg-rose-600 transition-all hover:scale-110 opacity-0 group-hover:opacity-100"
                                title="Hapus Stempel"
                            >
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>

                            {{-- Empty State --}}
                            <div x-show="!stampUrl" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-transform duration-300" :class="isDraggingStamp ? 'scale-110' : 'scale-100'">
                                <div class="p-3 bg-white dark:bg-surface-700 rounded-xl shadow-sm mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="stamp" class="w-8 h-8 text-surface-400 group-hover:text-emerald-500 transition-colors"></i>
                                </div>
                                <p class="text-sm font-medium text-surface-600 dark:text-surface-300">Klik atau Drop Stempel</p>
                                <p class="text-xs text-surface-400 mt-1">PNG Transparan (Max 2MB)</p>
                            </div>

                            {{-- Preview --}}
                            <div x-show="stampUrl" class="absolute inset-0 w-full h-full p-4 flex items-center justify-center bg-surface-100 dark:bg-surface-800">
                                <img :src="stampUrl" class="max-w-full max-h-full object-contain drop-shadow-sm transition-transform duration-500 group-hover:scale-105">
                                
                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center text-white z-20 pointer-events-none">
                                    <i data-lucide="refresh-cw" class="w-8 h-8 mb-2 drop-shadow-md"></i>
                                    <span class="text-xs font-medium bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">Ganti Gambar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Size Controls --}}
                <div class="space-y-4 pt-4">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-surface-900 dark:text-white pb-2 border-b border-surface-200 dark:border-surface-700">
                        <i data-lucide="scaling" class="w-4 h-4 text-emerald-500"></i>
                        Pengaturan Ukuran
                    </h3>
                    <p class="text-xs text-surface-500 dark:text-surface-400 -mt-2">
                        Atur ukuran tanda tangan dan stempel. Perubahan langsung terlihat di Live Preview.
                    </p>

                    {{-- Signature Size Slider --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label for="signature_size" class="text-sm font-medium text-surface-700 dark:text-surface-300">
                                <i data-lucide="pen-tool" class="w-3.5 h-3.5 inline-block mr-1 text-emerald-500"></i>
                                Ukuran Tanda Tangan
                            </label>
                            <span class="text-sm font-mono font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded-md" x-text="signatureSize + 'px'">80px</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-surface-400">40</span>
                            <input 
                                type="range" 
                                name="signature_size" 
                                id="signature_size"
                                x-model="signatureSize"
                                min="40" 
                                max="150" 
                                step="5"
                                class="flex-1 h-2 bg-surface-200 dark:bg-surface-700 rounded-lg appearance-none cursor-pointer accent-emerald-500 
                                       [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:bg-emerald-500 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:shadow-lg [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:transition-transform [&::-webkit-slider-thumb]:hover:scale-110
                                       [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:bg-emerald-500 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0 [&::-moz-range-thumb]:shadow-lg [&::-moz-range-thumb]:cursor-pointer"
                            >
                            <span class="text-xs text-surface-400">150</span>
                        </div>
                        {{-- Visual Bar Indicator --}}
                        <div class="h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full transition-all duration-200" :style="'width: ' + ((signatureSize - 40) / 110 * 100) + '%'"></div>
                        </div>
                    </div>

                    {{-- Stamp Size Slider --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label for="stamp_size" class="text-sm font-medium text-surface-700 dark:text-surface-300">
                                <i data-lucide="stamp" class="w-3.5 h-3.5 inline-block mr-1 text-emerald-500"></i>
                                Ukuran Stempel
                            </label>
                            <span class="text-sm font-mono font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded-md" x-text="stampSize + 'px'">85px</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-surface-400">40</span>
                            <input 
                                type="range" 
                                name="stamp_size" 
                                id="stamp_size"
                                x-model="stampSize"
                                min="40" 
                                max="150" 
                                step="5"
                                class="flex-1 h-2 bg-surface-200 dark:bg-surface-700 rounded-lg appearance-none cursor-pointer accent-emerald-500 
                                       [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:bg-emerald-500 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:shadow-lg [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:transition-transform [&::-webkit-slider-thumb]:hover:scale-110
                                       [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:bg-emerald-500 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0 [&::-moz-range-thumb]:shadow-lg [&::-moz-range-thumb]:cursor-pointer"
                            >
                            <span class="text-xs text-surface-400">150</span>
                        </div>
                        {{-- Visual Bar Indicator --}}
                        <div class="h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full transition-all duration-200" :style="'width: ' + ((stampSize - 40) / 110 * 100) + '%'"></div>
                        </div>
                    </div>

                    {{-- Info Tip --}}
                    <div class="flex items-start gap-2 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-lg">
                        <i data-lucide="lightbulb" class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0"></i>
                        <p class="text-xs text-amber-700 dark:text-amber-300">
                            <strong>Tips:</strong> Ukuran yang disarankan adalah 70-100px untuk tanda tangan dan 80-120px untuk stempel agar proporsional di dokumen PDF.
                        </p>
                    </div>
                </div>

                {{-- Tembusan --}}
                <div class="space-y-4 pt-4">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-surface-900 dark:text-white pb-2 border-b border-surface-200 dark:border-surface-700">
                        <i data-lucide="copy" class="w-4 h-4 text-emerald-500"></i>
                        Tembusan (CC)
                    </h3>
                    <div class="space-y-2">
                        <label for="signature_cc" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                            Daftar Tembusan
                        </label>
                        <textarea name="signature_cc" id="signature_cc" rows="4"
                            x-model="signature_cc"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 resize-none"
                            placeholder="1. Ketua Yayasan&#10;2. Arsip"></textarea>
                        <p class="text-xs text-surface-500 dark:text-surface-400">Gunakan baris baru untuk setiap poin tembusan. Kosongkan jika tidak ada.</p>
                    </div>
                </div>
            </div>

            {{-- Live Preview Column --}}
            <div class="space-y-6">
                <div class="sticky top-6">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-surface-900 dark:text-white mb-4">
                        <i data-lucide="eye" class="w-4 h-4 text-emerald-500"></i>
                        <span class="bg-gradient-to-r from-emerald-500 to-teal-600 bg-clip-text text-transparent italic">Live Preview</span> Kaki Surat
                    </h3>

                    <div class="bg-white dark:bg-surface-900 border border-surface-300 dark:border-surface-600 rounded-lg p-8 shadow-lg min-h-[400px] flex flex-col justify-between">
                        
                        {{-- Content Spacer --}}
                        <div class="h-20 border-l-2 border-dashed border-surface-300 dark:border-surface-700 ml-4 mb-8 relative">
                            <span class="absolute top-1/2 left-4 text-xs text-surface-400 italic -translate-y-1/2">[Area Konten Surat]</span>
                        </div>

                        {{-- Signatory Section --}}
                        <div class="flex justify-end mb-8 relative">
                            {{-- TTD Box --}}
                            <div class="w-64 text-center relative z-10">
                                {{-- Titimangsa --}}
                                <p class="mb-1 text-surface-800 dark:text-surface-200 text-sm">
                                    <span x-text="city"></span>, <span x-text="date"></span>
                                </p>
                                
                                {{-- Jabatan --}}
                                <p class="mb-4 font-bold text-surface-900 dark:text-white text-sm uppercase" x-text="leader_title || 'JABATAN PEJABAT'"></p>
                                
                                {{-- Signature & Stamp Wrapper --}}
                                <div class="relative flex items-center justify-center my-2 transition-all duration-300" :style="'min-height: ' + Math.max(signatureSize, stampSize) + 'px'">
                                    {{-- Signature Image --}}
                                    <img x-ref="previewSig" src="{{ !empty($rawSettings['signature_url']) ? asset($rawSettings['signature_url']) : '' }}" 
                                         class="w-auto object-contain relative z-20 transition-all duration-300" 
                                         :style="'height: ' + signatureSize + 'px; {{ !empty($rawSettings['signature_url']) ? '' : 'display: none;' }}'"
                                         alt="Signature">
                                         
                                    <div x-show="!$refs.previewSig.src && !'{{ $rawSettings['signature_url'] ?? '' }}'" class="border border-dashed border-surface-300 p-2 rounded text-xs text-surface-400">
                                        [Area Tanda Tangan]
                                    </div>

                                    {{-- Stamp Image (Overlapping) --}}
                                    <img x-ref="previewStamp" src="{{ !empty($rawSettings['stamp_url']) ? asset($rawSettings['stamp_url']) : '' }}" 
                                         class="w-auto object-contain absolute left-0 z-10 opacity-80 rotate-[-10deg] transition-all duration-300" 
                                         :style="'height: ' + stampSize + 'px; left: -10px; {{ !empty($rawSettings['stamp_url']) ? '' : 'display: none;' }}'"
                                         alt="Stamp">
                                </div>

                                {{-- Name & NIP --}}
                                <div class="mt-2">
                                    <p class="font-bold underline text-surface-900 dark:text-white text-sm uppercase" x-text="leader_name || 'NAMA PEJABAT'"></p>
                                    <p class="text-sm text-surface-700 dark:text-surface-300" x-show="leader_nip">
                                        NIP. <span x-text="leader_nip"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Tembusan --}}
                        <div x-show="signature_cc" class="text-left text-xs text-surface-600 dark:text-surface-400 mt-auto pt-4 border-t border-dashed border-surface-200 dark:border-surface-700">
                            <p class="font-bold underline mb-1">Tembusan:</p>
                            <pre x-text="signature_cc" class="font-sans whitespace-pre-wrap"></pre>
                        </div>

                    </div>
                    <p class="text-center text-xs text-surface-400 mt-4 italic">Posisi stempel dan tanda tangan mungkin sedikit berbeda pada hasil ekspor PDF</p>
                </div>
            </div>
        </div>
    </div>
</div>
