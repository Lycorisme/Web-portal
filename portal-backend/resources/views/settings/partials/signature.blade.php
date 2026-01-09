{{-- Signature (Kaki Surat) Settings Tab --}}
<div x-show="activeTab === 'signature'" 
     x-data="{
        leader_title: '{{ addslashes($rawSettings['leader_title'] ?? '') }}',
        leader_name: '{{ addslashes($rawSettings['leader_name'] ?? '') }}',
        leader_nip: '{{ addslashes($rawSettings['leader_nip'] ?? '') }}',
        signature_cc: `{{ $rawSettings['signature_cc'] ?? '' }}`, // Gunakan backtick untuk multiline
        city: '{{ addslashes($rawSettings['letterhead_city'] ?? $rawSettings['site_city'] ?? 'Kota') }}',
        date: '{{ date('d F Y') }}',
        
        // Helper untuk preview gambar
        previewImage(event, targetRef) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.$refs[targetRef].src = e.target.result;
                    this.$refs[targetRef].style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
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
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-lg border-2 border-dashed border-surface-300 dark:border-surface-600 flex items-center justify-center overflow-hidden bg-checkerboard">
                                @if(!empty($rawSettings['signature_url']))
                                    <img src="{{ asset($rawSettings['signature_url']) }}" class="w-full h-full object-contain" alt="Current Signature">
                                @else
                                    <i data-lucide="pen-tool" class="w-8 h-8 text-surface-400"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="signature_url" id="signature_url" accept="image/*"
                                    @change="previewImage($event, 'previewSig')"
                                    class="block w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-400">
                                <p class="mt-1 text-xs text-surface-500">Upload scan tanda tangan atau QR Code TTE (Max: 2MB)</p>
                                <input type="hidden" name="signature_url_current" value="{{ $rawSettings['signature_url'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    {{-- Stamp Upload --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                            Stempel Instansi (PNG Transparan)
                        </label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-lg border-2 border-dashed border-surface-300 dark:border-surface-600 flex items-center justify-center overflow-hidden bg-checkerboard">
                                @if(!empty($rawSettings['stamp_url']))
                                    <img src="{{ asset($rawSettings['stamp_url']) }}" class="w-full h-full object-contain" alt="Current Stamp">
                                @else
                                    <i data-lucide="stamp" class="w-8 h-8 text-surface-400"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="stamp_url" id="stamp_url" accept="image/*"
                                    @change="previewImage($event, 'previewStamp')"
                                    class="block w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-400">
                                <p class="mt-1 text-xs text-surface-500">Upload scan stempel basah transparan (Max: 2MB)</p>
                                <input type="hidden" name="stamp_url_current" value="{{ $rawSettings['stamp_url'] ?? '' }}">
                            </div>
                        </div>
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
                                <div class="h-24 relative flex items-center justify-center my-2">
                                    {{-- Signature Image --}}
                                    <img x-ref="previewSig" src="{{ !empty($rawSettings['signature_url']) ? asset($rawSettings['signature_url']) : '' }}" 
                                         class="h-20 w-auto object-contain relative z-20" 
                                         style="{{ !empty($rawSettings['signature_url']) ? '' : 'display: none;' }}"
                                         alt="Signature">
                                         
                                    <div x-show="!$refs.previewSig.src && !'{{ $rawSettings['signature_url'] ?? '' }}'" class="border border-dashed border-surface-300 p-2 rounded text-xs text-surface-400">
                                        [Area Tanda Tangan]
                                    </div>

                                    {{-- Stamp Image (Overlapping) --}}
                                    <img x-ref="previewStamp" src="{{ !empty($rawSettings['stamp_url']) ? asset($rawSettings['stamp_url']) : '' }}" 
                                         class="h-24 w-auto object-contain absolute left-0 z-10 opacity-80 rotate-[-10deg]" 
                                         style="left: -10px; {{ !empty($rawSettings['stamp_url']) ? '' : 'display: none;' }}"
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
