<div class="animate-slide-up" style="animation-delay: 0.1s;" wire:ignore.self>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8">
        
        {{-- Left Column - Photo & Quick Stats --}}
        <div class="lg:col-span-4 space-y-4 sm:space-y-6">
            {{-- Profile Photo Card --}}
            <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
                <div class="flex flex-col items-center text-center">
                    {{-- Photo Container --}}
                    <div class="relative group mb-4 sm:mb-6">
                        <div class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 rounded-2xl sm:rounded-3xl overflow-hidden ring-4 transition-all duration-300 cursor-pointer 
                            {{ $newPhoto || $hasPhotoChanged ? 'ring-theme-500 ring-offset-2' : 'ring-surface-200 dark:ring-surface-700 group-hover:ring-theme-500' }}"
                            onclick="document.getElementById('photoInput').click()">
                            
                            @if($newPhoto)
                                <img src="{{ $newPhoto->temporaryUrl() }}" alt="New Photo" class="w-full h-full object-cover">
                            @elseif($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="{{ $name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-theme-500 to-theme-600 flex items-center justify-center">
                                    <span class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white">
                                        {{ strtoupper(substr($name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            
                            {{-- Overlay on Hover --}}
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center rounded-2xl sm:rounded-3xl pointer-events-none">
                                <div class="text-white text-center">
                                    <i data-lucide="camera" class="w-8 h-8 mx-auto mb-2"></i>
                                    <span class="text-sm font-medium">{{ $newPhoto || $hasPhotoChanged ? 'Ganti Foto' : 'Ubah Foto' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        @if($profilePhoto || $newPhoto)
                        <div class="absolute top-2 right-2 flex items-center gap-1.5 z-30 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-300">
                            <button type="button" 
                                    wire:click="deletePhoto"
                                    wire:confirm="Apakah Anda yakin ingin menghapus foto profil?"
                                    class="p-2 bg-rose-500 text-white rounded-xl shadow-lg hover:bg-rose-600 transition-all hover:scale-110"
                                    title="Hapus Foto">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @endif

                        {{-- Status Badge --}}
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full border-4 border-white dark:border-surface-900 flex items-center justify-center transition-colors duration-300
                            {{ $hasPhotoChanged || $newPhoto ? 'bg-theme-500' : 'bg-accent-emerald' }}">
                            <i data-lucide="{{ $hasPhotoChanged || $newPhoto ? 'upload' : 'check' }}" class="w-4 h-4 text-white"></i>
                        </div>

                        {{-- Loading Indicator --}}
                        <div wire:loading wire:target="newPhoto" class="absolute inset-0 bg-black/60 rounded-2xl sm:rounded-3xl flex items-center justify-center">
                            <div class="text-white text-center">
                                <i data-lucide="loader-2" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
                                <span class="text-sm font-medium">Mengupload...</span>
                            </div>
                        </div>
                    </div>

                    {{-- Name & Role --}}
                    <h2 class="text-xl sm:text-2xl font-bold text-surface-900 dark:text-white mb-1">
                        {{ $name }}
                    </h2>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-theme-100 dark:bg-theme-900/30 rounded-full mb-3">
                        <i data-lucide="shield" class="w-4 h-4 text-theme-600 dark:text-theme-400"></i>
                        <span class="text-sm font-medium text-theme-600 dark:text-theme-400">
                            @switch($role)
                                @case('super_admin')
                                    Super Administrator
                                    @break
                                @case('admin')
                                    Administrator
                                    @break
                                @default
                                    Editor
                            @endswitch
                        </span>
                    </div>
                    
                    {{-- Contact Info --}}
                    <div class="w-full space-y-2 text-sm text-surface-600 dark:text-surface-400">
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            <span>{{ $email }}</span>
                        </div>
                        @if($phone)
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            <span>{{ $phone }}</span>
                        </div>
                        @endif
                        @if($location)
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                            <span>{{ $location }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Quick Stats Card --}}
            <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-4 sm:mb-6">
                    <div class="p-2 bg-theme-100 dark:bg-theme-900/30 rounded-xl">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 text-theme-600 dark:text-theme-400"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-surface-900 dark:text-white">Statistik</h3>
                </div>
                
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <i data-lucide="file-text" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <span class="text-sm text-surface-600 dark:text-surface-400">Artikel</span>
                        </div>
                        <span class="text-lg font-bold text-surface-900 dark:text-white">{{ $stats['articles_count'] ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                <i data-lucide="eye" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <span class="text-sm text-surface-600 dark:text-surface-400">Total Views</span>
                        </div>
                        <span class="text-lg font-bold text-surface-900 dark:text-white">{{ number_format($stats['total_views'] ?? 0) }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                                <i data-lucide="log-in" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                            </div>
                            <span class="text-sm text-surface-600 dark:text-surface-400">Total Login</span>
                        </div>
                        <span class="text-lg font-bold text-surface-900 dark:text-white">{{ $stats['login_count'] ?? 0 }}</span>
                    </div>
                    
                    <div class="pt-3 border-t border-surface-200 dark:border-surface-700">
                        <div class="flex items-center gap-2 text-xs text-surface-500">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <span>Bergabung sejak {{ $createdAt ? $createdAt->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - Profile Details --}}
        <div class="lg:col-span-8 space-y-4 sm:space-y-6">
            {{-- Profile Information Form --}}
            <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-surface-200/50 dark:border-surface-800/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-theme-100 dark:bg-theme-900/30 rounded-xl">
                            <i data-lucide="user" class="w-5 h-5 text-theme-600 dark:text-theme-400"></i>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-surface-900 dark:text-white">Informasi Profil</h3>
                            <p class="text-xs text-surface-500">Kelola informasi akun Anda</p>
                        </div>
                    </div>
                    
                    @if($hasChanges)
                    <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                        Perubahan belum disimpan
                    </span>
                    @endif
                </div>
                
                <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="user" class="w-5 h-5 text-surface-400"></i>
                            </div>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="name"
                                   class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                   placeholder="Nama Anda">
                        </div>
                        @error('name') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Email & Phone Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="w-5 h-5 text-surface-400"></i>
                                </div>
                                <input type="email" 
                                       wire:model.live.debounce.300ms="email"
                                       class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                       placeholder="email@example.com">
                            </div>
                            @error('email') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
                                Nomor Telepon
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="phone" class="w-5 h-5 text-surface-400"></i>
                                </div>
                                <input type="tel" 
                                       wire:model.live.debounce.300ms="phone"
                                       class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                       placeholder="+62 812 3456 7890">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Position & Location Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
                                Jabatan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="briefcase" class="w-5 h-5 text-surface-400"></i>
                                </div>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="position"
                                       class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                       placeholder="Jabatan Anda">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
                                Lokasi
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-surface-400"></i>
                                </div>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="location"
                                       class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                       placeholder="Kota, Provinsi">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Bio --}}
                    <div>
                        <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
                            Bio
                        </label>
                        <textarea wire:model.live.debounce.300ms="bio"
                                  rows="3"
                                  class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all resize-none"
                                  placeholder="Ceritakan sedikit tentang diri Anda..."></textarea>
                        <div class="flex justify-end mt-1">
                            <span class="text-xs text-surface-500">{{ strlen($bio ?? '') }}/500</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Security Settings --}}
            <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-surface-200/50 dark:border-surface-800/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-rose-100 dark:bg-rose-900/30 rounded-xl">
                            <i data-lucide="shield" class="w-5 h-5 text-rose-600 dark:text-rose-400"></i>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-surface-900 dark:text-white">Keamanan</h3>
                            <p class="text-xs text-surface-500">Kelola password dan keamanan akun</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 sm:p-6 space-y-4">
                    {{-- Change Password Section --}}
                    <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-surface-100 dark:bg-surface-700 rounded-lg">
                                    <i data-lucide="key" class="w-4 h-4 text-surface-600 dark:text-surface-400"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-surface-900 dark:text-white">Password</h4>
                                    <p class="text-xs text-surface-500">Ubah password untuk keamanan akun</p>
                                </div>
                            </div>
                            <button wire:click="togglePasswordForm"
                                    class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ $showPasswordForm ? 'bg-surface-200 dark:bg-surface-700 text-surface-700 dark:text-surface-300' : 'bg-theme-500 text-white hover:bg-theme-600' }}">
                                {{ $showPasswordForm ? 'Batal' : 'Ubah Password' }}
                            </button>
                        </div>
                        
                        @if($showPasswordForm)
                        <div class="space-y-4 pt-4 border-t border-surface-200 dark:border-surface-700" 
                             x-data x-init="$nextTick(() => lucide.createIcons())">
                            <div>
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Password Saat Ini</label>
                                <input type="password" 
                                       wire:model="currentPassword"
                                       class="w-full px-4 py-3 bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent"
                                       placeholder="••••••••">
                                @error('currentPassword') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Password Baru</label>
                                <input type="password" 
                                       wire:model="newPassword"
                                       class="w-full px-4 py-3 bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent"
                                       placeholder="Minimal 8 karakter">
                                @error('newPassword') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" 
                                       wire:model="newPasswordConfirmation"
                                       class="w-full px-4 py-3 bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent"
                                       placeholder="Ulangi password baru">
                            </div>
                            <button wire:click="updatePassword"
                                    wire:loading.attr="disabled"
                                    class="w-full py-3 bg-theme-600 hover:bg-theme-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                                <span wire:loading.remove wire:target="updatePassword">
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    Simpan Password
                                </span>
                                <span wire:loading wire:target="updatePassword" class="flex items-center gap-2">
                                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Logout All Devices --}}
                    <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                                    <i data-lucide="smartphone" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-surface-900 dark:text-white">Sesi Perangkat</h4>
                                    <p class="text-xs text-surface-500">Keluar dari semua perangkat lain</p>
                                </div>
                            </div>
                            <button wire:click="toggleLogoutModal"
                                    class="px-4 py-2 text-sm font-semibold text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-900/50 rounded-xl transition-all">
                                Keluar Semua
                            </button>
                        </div>
                        
                        @if($showLogoutModal)
                        <div class="mt-4 pt-4 border-t border-surface-200 dark:border-surface-700 space-y-4">
                            <p class="text-sm text-surface-600 dark:text-surface-400">
                                Masukkan password untuk mengkonfirmasi keluar dari semua perangkat lain.
                            </p>
                            <input type="password" 
                                   wire:model="logoutPassword"
                                   class="w-full px-4 py-3 bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   placeholder="Password Anda">
                            @error('logoutPassword') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <div class="flex gap-3">
                                <button wire:click="toggleLogoutModal"
                                        class="flex-1 py-2.5 bg-surface-200 dark:bg-surface-700 text-surface-700 dark:text-surface-300 font-semibold rounded-xl transition-all">
                                    Batal
                                </button>
                                <button wire:click="logoutAllDevices"
                                        wire:loading.attr="disabled"
                                        class="flex-1 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-all disabled:opacity-50">
                                    Konfirmasi
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Recent Activity --}}
            <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-surface-200/50 dark:border-surface-800/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-cyan-100 dark:bg-cyan-900/30 rounded-xl">
                            <i data-lucide="activity" class="w-5 h-5 text-cyan-600 dark:text-cyan-400"></i>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-surface-900 dark:text-white">Aktivitas Terbaru</h3>
                            <p class="text-xs text-surface-500">5 aktivitas terakhir Anda</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 sm:p-6">
                    @if(count($recentActivities) > 0)
                    <div class="space-y-3">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-center gap-3 p-3 bg-surface-50 dark:bg-surface-800/50 rounded-xl" wire:key="activity-{{ $activity['id'] }}">
                            <div class="p-2 bg-surface-100 dark:bg-surface-700 rounded-lg">
                                <i data-lucide="{{ $activity['action'] === 'login' ? 'log-in' : ($activity['action'] === 'update' ? 'edit' : 'activity') }}" class="w-4 h-4 text-surface-600 dark:text-surface-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-surface-900 dark:text-white truncate">{{ $activity['description'] }}</p>
                                <p class="text-xs text-surface-500">{{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-surface-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
                        <p class="text-sm">Belum ada aktivitas tercatat</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @if($hasChanges)
    <div class="fixed bottom-0 left-0 right-0 z-40 p-4 lg:pl-72 transition-all duration-300"
         x-data
         x-init="$nextTick(() => lucide.createIcons())">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200/50 dark:border-surface-700/50 shadow-2xl shadow-surface-900/10 dark:shadow-black/20 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-surface-700 dark:text-surface-300">
                        Ada perubahan belum disimpan
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="cancelChanges"
                            class="px-4 py-2.5 text-sm font-semibold text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-all flex items-center gap-2">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Batal
                    </button>
                    <button wire:click="saveProfile"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 bg-theme-600 hover:bg-theme-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-theme-500/20 flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveProfile">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Simpan
                        </span>
                        <span wire:loading wire:target="saveProfile" class="flex items-center gap-2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Hidden File Input --}}
    <input type="file" 
           id="photoInput"
           wire:model="newPhoto"
           accept="image/jpeg,image/png,image/gif,image/webp"
           class="hidden">

    {{-- Script to refresh lucide icons after livewire updates --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                lucide.createIcons();
            });
        });
        
        // Listen for toast events
        window.addEventListener('show-toast', (event) => {
            if (typeof showToast === 'function') {
                showToast(event.detail.type || event.detail[0]?.type, event.detail.title || event.detail[0]?.title, event.detail.message || event.detail[0]?.message);
            }
        });
        
        // Listen for profile update events (to update header)
        window.addEventListener('profile-updated', (event) => {
            const detail = event.detail[0] || event.detail;
            // Update header name if exists
            const headerName = document.querySelector('#header-user-name');
            if (headerName && detail.name) {
                headerName.textContent = detail.name;
            }
            // Update header photo if exists  
            const headerPhoto = document.querySelector('#headerProfilePhoto');
            if (headerPhoto && detail.photo) {
                headerPhoto.src = detail.photo + '?t=' + Date.now();
            }
        });
    </script>
</div>
