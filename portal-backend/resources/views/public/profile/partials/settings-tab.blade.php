{{-- Settings Tab Content --}}

{{-- Update Profile Info --}}
<div class="glass-card rounded-2xl p-6">
    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
            <i class="fas fa-user text-emerald-500"></i>
        </div>
        Informasi Dasar
    </h3>
    
    <form id="form-info" @submit.prevent="submitForm('form-info', '{{ route('public.profile.info') }}')" class="space-y-4">
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-400 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                       class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-400 mb-2">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" required
                       class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all">
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" :disabled="loading" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20 disabled:opacity-50">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- Update Avatar --}}
<div class="glass-card rounded-2xl p-6">
    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
            <i class="fas fa-camera text-purple-500"></i>
        </div>
        Foto Profil
    </h3>
    
    <div class="flex flex-col md:flex-row items-center gap-6">
        <div class="w-32 h-32 rounded-2xl overflow-hidden bg-slate-800 border-2 border-dashed border-slate-700">
            @if($user->profile_photo)
                <img src="{{ $user->avatar }}" class="w-full h-full object-cover" id="preview-avatar">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-600">
                    <i class="fas fa-user text-4xl"></i>
                </div>
            @endif
        </div>
        
        <div class="flex-1 space-y-4">
            <form id="form-photo" @submit.prevent="submitForm('form-photo', '{{ route('public.profile.photo') }}')" enctype="multipart/form-data">
                <input type="file" name="profile_photo" id="photo-input" accept="image/*" class="hidden"
                       onchange="document.getElementById('preview-avatar').src = URL.createObjectURL(this.files[0]); document.getElementById('photo-name').textContent = this.files[0].name;">
                <div class="flex flex-wrap gap-3">
                    <label for="photo-input" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl border border-slate-700 cursor-pointer transition-all">
                        <i class="fas fa-upload mr-2"></i> Pilih Foto
                    </label>
                    <button type="submit" :disabled="loading" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-all disabled:opacity-50">
                        <i class="fas fa-check mr-2"></i> Upload
                    </button>
                    @if($user->profile_photo)
                    <button type="button" @click="submitForm('form-delete-photo', '{{ route('public.profile.photo.delete') }}')" class="px-4 py-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-semibold rounded-xl border border-rose-500/30 transition-all">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                    @endif
                </div>
            </form>
            <form id="form-delete-photo" class="hidden">@method('DELETE')</form>
            <p class="text-xs text-slate-500" id="photo-name">Format: JPG, PNG, GIF, WebP. Maks 5MB</p>
        </div>
    </div>
</div>

{{-- Update Password --}}
<div class="glass-card rounded-2xl p-6">
    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
            <i class="fas fa-lock text-amber-500"></i>
        </div>
        Ubah Password
    </h3>
    
    <form id="form-password" @submit.prevent="submitForm('form-password', '{{ route('public.profile.password') }}')" class="space-y-4">
        @method('PUT')
        <div>
            <label class="block text-sm font-semibold text-slate-400 mb-2">Password Saat Ini</label>
            <div class="relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" name="current_password" required
                       class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-3 pr-12 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white">
                    <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-400 mb-2">Password Baru</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" name="password" required minlength="8"
                           class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-3 pr-12 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white">
                        <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-400 mb-2">Konfirmasi Password</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                           class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-3 pr-12 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white">
                        <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                    </button>
                </div>
            </div>
        </div>
        <p class="text-xs text-slate-500"><i class="fas fa-info-circle mr-1"></i> Minimal 8 karakter dengan huruf besar, kecil, dan angka</p>
        <div class="flex justify-end">
            <button type="submit" :disabled="loading" class="px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-amber-500/20 disabled:opacity-50">
                <i class="fas fa-key mr-2"></i> Ubah Password
            </button>
        </div>
    </form>
</div>
