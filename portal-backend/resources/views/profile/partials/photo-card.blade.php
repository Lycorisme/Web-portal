{{-- Profile Photo Card --}}
<div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col items-center text-center">
        {{-- Photo Container - Click to Upload --}}
        <div class="relative group mb-4 sm:mb-6 cursor-pointer" @click="$refs.photoInput.click()">
            <div class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 rounded-2xl sm:rounded-3xl overflow-hidden ring-4 transition-all duration-300"
                 :class="pendingPhotoFile ? 'ring-theme-500 ring-offset-2' : 'ring-surface-200 dark:ring-surface-700 group-hover:ring-theme-500'">
                @if($user->profile_photo)
                    <img id="profilePhotoPreview" 
                         src="{{ $user->profile_photo }}" 
                         alt="{{ $user->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div id="profilePhotoPreview" class="w-full h-full bg-gradient-to-br from-theme-500 to-theme-600 flex items-center justify-center">
                        <span class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                
                {{-- Overlay on Hover --}}
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <div class="text-white text-center">
                        <i data-lucide="camera" class="w-8 h-8 mx-auto mb-2"></i>
                        <span class="text-sm font-medium" x-text="pendingPhotoFile ? 'Ganti Foto' : 'Ubah Foto'"></span>
                    </div>
                </div>
            </div>

            {{-- Status Badge - Changes based on state --}}
            <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full border-4 border-white dark:border-surface-900 flex items-center justify-center transition-colors duration-300"
                 :class="pendingPhotoFile ? 'bg-theme-500' : 'bg-accent-emerald'">
                <i :data-lucide="pendingPhotoFile ? 'upload' : 'check'" class="w-4 h-4 text-white"></i>
            </div>

            {{-- Upload Progress Indicator --}}
            <div x-show="isUploading" 
                 x-cloak
                 class="absolute inset-0 bg-black/60 rounded-2xl sm:rounded-3xl flex items-center justify-center">
                <div class="text-white text-center">
                    <i data-lucide="loader-2" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
                    <span class="text-sm font-medium">Mengupload...</span>
                </div>
            </div>
        </div>

        {{-- Pending Photo Notice --}}
        <div x-show="pendingPhotoFile" x-cloak class="mb-4 px-3 py-2 bg-theme-100 dark:bg-theme-900/30 rounded-lg">
            <p class="text-xs text-theme-600 dark:text-theme-400 font-medium flex items-center gap-1.5">
                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                <span>Klik "Simpan" untuk menyimpan foto baru</span>
            </p>
        </div>

        {{-- Name & Role --}}
        <h2 class="text-xl sm:text-2xl font-bold text-surface-900 dark:text-white mb-1">
            {{ $user->name }}
        </h2>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-theme-100 dark:bg-theme-900/30 rounded-full mb-3">
            <i data-lucide="shield" class="w-4 h-4 text-theme-600 dark:text-theme-400"></i>
            <span class="text-sm font-medium text-theme-600 dark:text-theme-400">
                @switch($user->role)
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
                <span>{{ $user->email }}</span>
            </div>
            @if($user->phone)
            <div class="flex items-center justify-center gap-2">
                <i data-lucide="phone" class="w-4 h-4"></i>
                <span>{{ $user->phone }}</span>
            </div>
            @endif
            @if($user->location)
            <div class="flex items-center justify-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
                <span>{{ $user->location }}</span>
            </div>
            @endif
        </div>

        {{-- Delete Photo Button (only if has photo) --}}
        @if($user->profile_photo)
        <div class="w-full mt-6">
            <button @click="deletePhoto()"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-accent-rose/10 hover:text-accent-rose transition-all duration-200">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span>Hapus Foto</span>
            </button>
        </div>
        @endif
    </div>
</div>
