
<div x-show="activeTab === 'letterhead'" 
     x-data="{
        letterhead_parent_org_1: '<?php echo e(addslashes($rawSettings['letterhead_parent_org_1'] ?? '')); ?>',
        letterhead_parent_org_2: '<?php echo e(addslashes($rawSettings['letterhead_parent_org_2'] ?? '')); ?>',
        letterhead_org_name: '<?php echo e(addslashes($rawSettings['letterhead_org_name'] ?? '')); ?>',
        letterhead_street: '<?php echo e(addslashes($rawSettings['letterhead_street'] ?? '')); ?>',
        letterhead_district: '<?php echo e(addslashes($rawSettings['letterhead_district'] ?? '')); ?>',
        letterhead_city: '<?php echo e(addslashes($rawSettings['letterhead_city'] ?? '')); ?>',
        letterhead_province: '<?php echo e(addslashes($rawSettings['letterhead_province'] ?? '')); ?>',
        letterhead_postal_code: '<?php echo e(addslashes($rawSettings['letterhead_postal_code'] ?? '')); ?>',
        letterhead_phone: '<?php echo e(addslashes($rawSettings['letterhead_phone'] ?? '')); ?>',
        letterhead_fax: '<?php echo e(addslashes($rawSettings['letterhead_fax'] ?? '')); ?>',
        letterhead_email: '<?php echo e(addslashes($rawSettings['letterhead_email'] ?? '')); ?>',
        letterhead_website: '<?php echo e(addslashes($rawSettings['letterhead_website'] ?? '')); ?>'
     }"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
        
        
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 flex-shrink-0">
                <i data-lucide="file-text" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Kop Surat</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Pengaturan identitas resmi kop surat untuk dokumen formal dan laporan PDF</p>
            </div>
        </div>

        


        
        <div class="mb-8">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-surface-900 dark:text-white mb-4 pb-2 border-b border-surface-200 dark:border-surface-700">
                <i data-lucide="building-2" class="w-4 h-4 text-indigo-500"></i>
                Hierarki Instansi
            </h3>
            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                
                <div class="space-y-2">
                    <label for="letterhead_parent_org_1" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Hierarki Instansi 1 (Tingkat Atas)
                    </label>
                    <input type="text" name="letterhead_parent_org_1" id="letterhead_parent_org_1"
                        x-model="letterhead_parent_org_1"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 uppercase"
                        placeholder="PEMERINTAH PROVINSI JAWA BARAT">
                    <p class="text-xs text-surface-500 dark:text-surface-400">Gunakan huruf kapital (All Caps). Kosongkan jika tidak diperlukan.</p>
                </div>

                
                <div class="space-y-2">
                    <label for="letterhead_parent_org_2" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Hierarki Instansi 2 (Tingkat Bawah)
                    </label>
                    <input type="text" name="letterhead_parent_org_2" id="letterhead_parent_org_2"
                        x-model="letterhead_parent_org_2"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 uppercase"
                        placeholder="DINAS PENDIDIKAN DAN KEBUDAYAAN">
                    <p class="text-xs text-surface-500 dark:text-surface-400">Gunakan huruf kapital (All Caps). Kosongkan jika tidak diperlukan.</p>
                </div>

                
                <div class="space-y-2">
                    <label for="letterhead_org_name" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Nama Organisasi/Instansi Utama
                    </label>
                    <input type="text" name="letterhead_org_name" id="letterhead_org_name"
                        x-model="letterhead_org_name"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 uppercase font-semibold"
                        placeholder="BALAI TEKNOLOGI INFORMASI DAN KOMUNIKASI PENDIDIKAN (BTIKP)">
                    <p class="text-xs text-surface-500 dark:text-surface-400">Nama resmi instansi yang akan ditampilkan paling menonjol pada kop surat.</p>
                </div>
            </div>
        </div>

        
        <div class="mb-8">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-surface-900 dark:text-white mb-4 pb-2 border-b border-surface-200 dark:border-surface-700">
                <i data-lucide="map-pin" class="w-4 h-4 text-indigo-500"></i>
                Alamat Lengkap
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                
                <div class="md:col-span-2 space-y-2">
                    <label for="letterhead_street" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Alamat Jalan <span class="text-accent-rose">*</span>
                    </label>
                    <input type="text" name="letterhead_street" id="letterhead_street"
                        x-model="letterhead_street"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                        placeholder="Jalan Raya Utama No. 123">
                    <p class="text-xs text-surface-500 dark:text-surface-400">Tulis lengkap tanpa singkatan (gunakan "Jalan" bukan "Jl.")</p>
                </div>

                
                <div class="space-y-2">
                    <label for="letterhead_district" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Kelurahan/Kecamatan
                    </label>
                    <input type="text" name="letterhead_district" id="letterhead_district"
                         x-model="letterhead_district"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                        placeholder="Kelurahan Sukajadi">
                </div>

                
                <div class="space-y-2">
                    <label for="letterhead_city" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Kota/Kabupaten <span class="text-accent-rose">*</span>
                    </label>
                    <input type="text" name="letterhead_city" id="letterhead_city"
                        x-model="letterhead_city"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                        placeholder="Kota Bandung">
                </div>

                
                <div class="space-y-2">
                    <label for="letterhead_province" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Provinsi
                    </label>
                    <input type="text" name="letterhead_province" id="letterhead_province"
                        x-model="letterhead_province"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                        placeholder="Jawa Barat">
                </div>

                
                <div class="space-y-2">
                    <label for="letterhead_postal_code" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Kode Pos <span class="text-accent-rose">*</span>
                    </label>
                    <input type="text" name="letterhead_postal_code" id="letterhead_postal_code"
                        x-model="letterhead_postal_code"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                        placeholder="40123"
                        maxlength="5">
                </div>
            </div>
        </div>

        
        <div class="mb-8">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-surface-900 dark:text-white mb-4 pb-2 border-b border-surface-200 dark:border-surface-700">
                <i data-lucide="contact" class="w-4 h-4 text-indigo-500"></i>
                Informasi Kontak
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                
                <div class="space-y-2">
                    <label for="letterhead_phone" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Nomor Telepon <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                        <input type="text" name="letterhead_phone" id="letterhead_phone"
                            x-model="letterhead_phone"
                            class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                            placeholder="(021) 1234567">
                    </div>
                    <p class="text-xs text-surface-500 dark:text-surface-400">Sertakan kode area dalam tanda kurung</p>
                </div>

                
                <div class="space-y-2">
                    <label for="letterhead_fax" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Nomor Faksimili
                    </label>
                    <div class="relative">
                        <i data-lucide="printer" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                        <input type="text" name="letterhead_fax" id="letterhead_fax"
                            x-model="letterhead_fax"
                            class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                            placeholder="(021) 7654321">
                    </div>
                </div>

                
                <div class="space-y-2">
                    <label for="letterhead_email" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Email Resmi <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                        <input type="email" name="letterhead_email" id="letterhead_email"
                            x-model="letterhead_email"
                            class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                            placeholder="admin@btikp.go.id">
                    </div>
                    <p class="text-xs text-surface-500 dark:text-surface-400">Gunakan domain resmi instansi, hindari email gratisan</p>
                </div>

                
                <div class="space-y-2">
                    <label for="letterhead_website" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Website <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <i data-lucide="globe" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                        <input type="text" name="letterhead_website" id="letterhead_website"
                            x-model="letterhead_website"
                            class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                            placeholder="www.btikp.cloud">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-8 p-4 sm:p-6 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-surface-900 dark:text-white mb-4">
                <i data-lucide="eye" class="w-4 h-4 text-indigo-500"></i>
                <span class="bg-gradient-to-r from-blue-500 to-indigo-600 bg-clip-text text-transparent italic">Live Preview</span> Kop Surat
            </h3>
            <div class="bg-white dark:bg-surface-900 border border-surface-300 dark:border-surface-600 rounded-lg p-6 shadow-sm overflow-hidden">
                
                <div class="w-full bg-white text-black p-4" style="font-family: 'Times New Roman', Times, serif;">
                    <div class="flex items-center gap-4 pb-4">
                        
                        <div class="w-[90px] flex-shrink-0 flex items-center justify-center">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($rawSettings['logo_url'])): ?>
                                <img src="<?php echo e(asset($rawSettings['logo_url'])); ?>" alt="Logo" class="w-[80px] h-auto object-contain">
                            <?php else: ?>
                                <div class="w-[80px] h-[80px] border border-dashed border-gray-400 flex items-center justify-center bg-gray-50">
                                    <span class="text-xs text-gray-400">Logo</span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        
                        
                        <div class="flex-grow text-center">
                            
                            
                            <div x-show="letterhead_parent_org_1" x-text="letterhead_parent_org_1" class="font-bold uppercase leading-tight text-black" style="font-size: 14pt;"></div>
                            <div x-show="!letterhead_parent_org_1" class="font-bold uppercase leading-tight text-gray-300 border border-dashed border-gray-300 p-1 mb-1 inline-block" style="font-size: 14pt;">[PEMERINTAH PROVINSI ...]</div>

                            
                            <div x-show="letterhead_parent_org_2" x-text="letterhead_parent_org_2" class="font-bold uppercase leading-tight text-black" style="font-size: 14pt;"></div>
                            
                            
                            <div x-show="letterhead_org_name" x-text="letterhead_org_name" class="font-black uppercase leading-none mt-1 text-black" style="font-size: 17pt;"></div>
                            <div x-show="!letterhead_org_name" class="font-black uppercase leading-none mt-1 text-gray-300 border border-dashed border-gray-300 p-1 inline-block" style="font-size: 17pt;">[NAMA INSTANSI UTAMA]</div>

                            
                            <div class="mt-2 text-black leading-snug" style="font-size: 10pt;">
                                <span x-show="letterhead_street" x-text="letterhead_street"></span>
                                <span x-show="!letterhead_street" class="text-gray-300">[Alamat Jalan...]</span>

                                <span x-show="letterhead_district" x-text="', ' + letterhead_district"></span>
                                <span x-show="letterhead_city" x-text="', ' + letterhead_city"></span>
                                <span x-show="!letterhead_city" class="text-gray-300">, [Kota...]</span>

                                <span x-show="letterhead_postal_code" x-text="' ' + letterhead_postal_code"></span>
                                <br>
                                
                                <span x-show="letterhead_phone" x-text="'Telp: ' + letterhead_phone"></span>
                                <span x-show="!letterhead_phone" class="text-gray-300">[Telp...]</span>

                                <span x-show="letterhead_fax" x-text="' | Fax: ' + letterhead_fax"></span>

                                <span x-show="letterhead_email" x-text="' | Email: ' + letterhead_email"></span>
                                <span x-show="!letterhead_email" class="text-gray-300"> | [Email...]</span>

                                <span x-show="letterhead_website" x-text="' | Web: ' + letterhead_website"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="border-top: 4px solid #000; border-bottom: 1px solid #000; height: 2px; width: 100%;"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\settings\partials\letterhead.blade.php ENDPATH**/ ?>