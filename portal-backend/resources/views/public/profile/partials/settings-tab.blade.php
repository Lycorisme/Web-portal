{{-- Settings Tab Content --}}
<div class="space-y-12">

    {{-- Personal Information Card --}}
    <div class="group relative overflow-hidden rounded-[2rem] bg-slate-900/40 border border-white/5 p-8 transition-all hover:bg-slate-900/60 transition-colors duration-500">
        {{-- Decorative Glow --}}
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-emerald-500/5 blur-3xl rounded-full pointer-events-none group-hover:bg-emerald-500/10 transition-colors duration-700"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-5 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 border border-emerald-500/10 flex items-center justify-center shadow-lg shadow-emerald-500/5">
                    <i class="fas fa-user-edit text-2xl text-emerald-400"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-display font-bold text-white tracking-tight">Informasi Dasar</h3>
                    <p class="text-slate-400 text-sm font-medium">Perbarui biodata dan alamat email Anda</p>
                </div>
            </div>

            <form id="form-info" @submit.prevent="submitForm('form-info', '{{ route('public.profile.info') }}')" class="space-y-6">
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-slate-500 group-focus-within/input:text-emerald-400 transition-colors"></i>
                            </div>
                            <input type="text" name="name" value="{{ $user->name }}" required
                                   class="w-full bg-slate-950/30 border border-white/5 focus:border-emerald-500/50 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-emerald-500/50 transition-all font-medium">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Alamat Email</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-slate-500 group-focus-within/input:text-emerald-400 transition-colors"></i>
                            </div>
                            <input type="email" name="email" value="{{ $user->email }}" required
                                   class="w-full bg-slate-950/30 border border-white/5 focus:border-emerald-500/50 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-emerald-500/50 transition-all font-medium">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-white/5">
                    <button type="submit" :disabled="loading" class="px-8 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-bold text-sm tracking-wide rounded-xl transition-all shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <span>Simpan Perubahan</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Security Card --}}
    <div class="group relative overflow-hidden rounded-[2rem] bg-slate-900/40 border border-white/5 p-8 transition-all hover:bg-slate-900/60 transition-colors duration-500">
        {{-- Decorative Glow --}}
        <div class="absolute top-0 left-0 -mt-16 -ml-16 w-64 h-64 bg-amber-500/5 blur-3xl rounded-full pointer-events-none group-hover:bg-amber-500/10 transition-colors duration-700"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-5 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500/10 to-orange-500/10 border border-amber-500/10 flex items-center justify-center shadow-lg shadow-amber-500/5">
                    <i class="fas fa-lock text-2xl text-amber-400"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-display font-bold text-white tracking-tight">Keamanan & Password</h3>
                    <p class="text-slate-400 text-sm font-medium">Lindungi akun Anda dengan password yang kuat</p>
                </div>
            </div>

            <form id="form-password" @submit.prevent="submitForm('form-password', '{{ route('public.profile.password') }}')" class="space-y-6">
                @method('PUT')
                
                <div class="bg-amber-900/10 border border-amber-500/10 rounded-xl p-4 flex gap-3 items-start">
                    <i class="fas fa-shield-alt text-amber-500 mt-1"></i>
                    <p class="text-xs text-amber-200/80 leading-relaxed font-medium">
                        Untuk keamanan maksimal, gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Password Saat Ini</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="current_password" required
                                   class="w-full bg-slate-950/30 border border-white/5 focus:border-amber-500/50 rounded-xl py-3.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-amber-500/50 transition-all font-mono">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                                <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Password Baru</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" required minlength="8"
                                       class="w-full bg-slate-950/30 border border-white/5 focus:border-amber-500/50 rounded-xl py-3.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-amber-500/50 transition-all font-mono">
                                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                                    <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Konfirmasi Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                       class="w-full bg-slate-950/30 border border-white/5 focus:border-amber-500/50 rounded-xl py-3.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-amber-500/50 transition-all font-mono">
                                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                                    <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-white/5">
                    <button type="submit" :disabled="loading" class="px-8 py-3 bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold text-sm tracking-wide rounded-xl transition-all shadow-lg shadow-amber-500/20 hover:shadow-amber-500/40 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <span>Update Password</span>
                        <i class="fas fa-lock text-xs"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
