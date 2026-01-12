{{-- Settings Tab Content --}}

{{-- Update Profile Info --}}
<div class="rounded-3xl p-6 md:p-8 bg-slate-900/60 backdrop-blur-md border border-white/5 ring-1 ring-white/5 shadow-xl relative overflow-hidden group">
    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-colors duration-700 pointer-events-none"></div>
    
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center shadow-lg shadow-emerald-500/10 border border-emerald-500/20">
            <i class="fas fa-user-edit text-emerald-400 text-lg"></i>
        </div>
        <div>
            <h3 class="text-xl font-display font-bold text-white">Informasi Dasar</h3>
            <p class="text-xs text-slate-400 mt-1">Ubah nama dan alamat email Anda</p>
        </div>
    </div>
    
    <form id="form-info" @submit.prevent="submitForm('form-info', '{{ route('public.profile.info') }}')" class="space-y-6 relative z-10">
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-400 ml-1">Nama Lengkap</label>
                <div class="relative group/input">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-user text-slate-500 group-focus-within/input:text-emerald-500 transition-colors"></i>
                    </div>
                    <input type="text" name="name" value="{{ $user->name }}" required
                           class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all font-medium">
                </div>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-400 ml-1">Alamat Email</label>
                <div class="relative group/input">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-slate-500 group-focus-within/input:text-emerald-500 transition-colors"></i>
                    </div>
                    <input type="email" name="email" value="{{ $user->email }}" required
                           class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all font-medium">
                </div>
            </div>
        </div>
        <div class="flex justify-end pt-2">
            <button type="submit" :disabled="loading" class="px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm tracking-wide uppercase rounded-xl transition-all shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                <i class="fas fa-save"></i> <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>

{{-- Update Avatar --}}
<div class="rounded-3xl p-6 md:p-8 bg-slate-900/60 backdrop-blur-md border border-white/5 ring-1 ring-white/5 shadow-xl relative overflow-hidden group mt-8">
    <div class="absolute top-0 right-0 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl group-hover:bg-purple-500/10 transition-colors duration-700 pointer-events-none"></div>
    
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center shadow-lg shadow-purple-500/10 border border-purple-500/20">
            <i class="fas fa-camera text-purple-400 text-lg"></i>
        </div>
        <div>
            <h3 class="text-xl font-display font-bold text-white">Foto Profil</h3>
            <p class="text-xs text-slate-400 mt-1">Sesuaikan identitas visual Anda</p>
        </div>
    </div>
    
    <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
        <div class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden bg-slate-950 border-4 border-slate-800 shadow-2xl relative group/preview">
            @if($user->profile_photo)
                <img src="{{ $user->avatar }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/preview:scale-110" id="preview-avatar">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-700 bg-slate-900">
                    <i class="fas fa-user-circle text-6xl"></i>
                </div>
            @endif
            <div class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover/preview:opacity-100 transition-opacity pointer-events-none">
                <span class="text-xs font-bold text-white uppercase tracking-wider">Preview</span>
            </div>
        </div>
        
        <div class="flex-1 w-full space-y-6 text-center md:text-left">
            <div class="p-4 rounded-xl bg-slate-950/50 border border-slate-800/80 border-dashed">
                <p class="text-xs text-slate-400 leading-relaxed">
                    Unggah foto baru untuk mengganti avatar Anda. Format yang didukung: <span class="text-slate-300 font-bold">JPG, PNG, GIF, WebP</span>. Ukuran maksimal file: <span class="text-slate-300 font-bold">5MB</span>.
                </p>
            </div>
            
            <form id="form-photo" @submit.prevent="submitForm('form-photo', '{{ route('public.profile.photo') }}')" enctype="multipart/form-data">
                <input type="file" name="profile_photo" id="photo-input" accept="image/*" class="hidden"
                       onchange="if(this.files[0]) { document.getElementById('preview-avatar').src = URL.createObjectURL(this.files[0]); document.getElementById('file-name-display').textContent = this.files[0].name; document.getElementById('upload-btn').disabled = false; }">
                
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <label for="photo-input" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs uppercase tracking-wider rounded-xl border border-slate-700 cursor-pointer transition-all hover:text-white flex items-center gap-2">
                        <i class="fas fa-folder-open"></i> Cari File
                    </label>
                    
                    <button type="submit" id="upload-btn" disabled class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-purple-500/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt"></i> Upload
                    </button>
                    
                    @if($user->profile_photo)
                    <button type="button" @click="submitForm('form-delete-photo', '{{ route('public.profile.photo.delete') }}')" class="px-6 py-3 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold text-xs uppercase tracking-wider rounded-xl border border-rose-500/30 transition-all hover:text-rose-300 flex items-center gap-2">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                    @endif
                </div>
                <div class="mt-2 text-xs text-emerald-400 font-medium h-4" id="file-name-display"></div>
            </form>
            <form id="form-delete-photo" class="hidden">@method('DELETE')</form>
        </div>
    </div>
</div>

{{-- Update Password --}}
<div class="rounded-3xl p-6 md:p-8 bg-slate-900/60 backdrop-blur-md border border-white/5 ring-1 ring-white/5 shadow-xl relative overflow-hidden group mt-8">
    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl group-hover:bg-amber-500/10 transition-colors duration-700 pointer-events-none"></div>
    
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center shadow-lg shadow-amber-500/10 border border-amber-500/20">
            <i class="fas fa-key text-amber-400 text-lg"></i>
        </div>
        <div>
            <h3 class="text-xl font-display font-bold text-white">Keamanan Akun</h3>
            <p class="text-xs text-slate-400 mt-1">Perbarui kata sandi secara berkala</p>
        </div>
    </div>
    
    <form id="form-password" @submit.prevent="submitForm('form-password', '{{ route('public.profile.password') }}')" class="space-y-6 relative z-10">
        @method('PUT')
        
        <div class="bg-amber-500/5 border border-amber-500/10 rounded-xl p-4 flex items-start gap-3">
            <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
            <p class="text-xs text-slate-300 leading-relaxed">
                Gunakan minimal <span class="text-amber-400 font-bold">8 karakter</span> dengan kombinasi huruf besar, huruf kecil, dan angka untuk keamanan maksimal.
            </p>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-400 ml-1">Password Saat Ini</label>
            <div class="relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" name="current_password" required
                       class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/20 transition-all font-medium font-mono">
                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                    <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-400 ml-1">Password Baru</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" name="password" required minlength="8"
                           class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/20 transition-all font-medium font-mono">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                        <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                    </button>
                </div>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-400 ml-1">Konfirmasi Password</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                           class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/20 transition-all font-medium font-mono">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                        <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" :disabled="loading" class="px-8 py-3.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold text-sm tracking-wide uppercase rounded-xl transition-all shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                <i class="fas fa-lock-open"></i> <span>Perbarui Password</span>
            </button>
        </div>
    </form>
</div>
