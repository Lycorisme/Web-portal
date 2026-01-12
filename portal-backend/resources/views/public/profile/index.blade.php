@extends('public.layouts.public')

@section('meta_title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="min-h-screen pt-28 pb-16 relative" x-data="profilePage()">
    
    {{-- Background Glow --}}
    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[500px] bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto px-6">
        
        {{-- Profile Header Card --}}
        <div class="rounded-[2.5rem] p-8 mb-10 relative overflow-hidden bg-slate-900/40 backdrop-blur-xl border border-white/5 shadow-2xl ring-1 ring-white/5 group">
            
            {{-- Decorative Background --}}
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 via-transparent to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
            
            <div class="relative flex flex-col md:flex-row items-center gap-8 md:gap-12 z-10">
                {{-- Avatar Section --}}
                <div class="relative shrink-0">
                    <div class="w-32 h-32 md:w-36 md:h-36 rounded-full overflow-hidden ring-4 ring-emerald-500/20 shadow-2xl relative z-10 group-hover:ring-emerald-500/40 transition-all duration-500 transform group-hover:scale-105 bg-slate-800">
                        @if($user->profile_photo)
                            <img src="{{ $user->avatar }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" id="header-avatar">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white text-5xl font-bold font-display" id="header-avatar-placeholder">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    {{-- Status Badge (Absolute) --}}
                    <div class="absolute bottom-1 right-1 md:bottom-2 md:right-2 z-20">
                         @if($user->email_verified_at)
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-500 rounded-full border-[3px] border-slate-900 flex items-center justify-center shadow-lg transform translate-y-1 translate-x-1" title="Terverifikasi">
                                <i class="fas fa-check text-white text-xs md:text-sm font-bold"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-amber-500 rounded-full border-[3px] border-slate-900 flex items-center justify-center shadow-lg transform translate-y-1 translate-x-1 animate-pulse" title="Belum Verifikasi">
                                <i class="fas fa-exclamation text-white text-xs md:text-sm font-bold"></i>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- User Info Section --}}
                <div class="text-center md:text-left flex-1 min-w-0">
                    <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-2 tracking-tight drop-shadow-sm" id="header-name">{{ $user->name }}</h1>
                    <p class="text-slate-400 text-lg mb-6 font-medium tracking-wide" id="header-email">{{ $user->email }}</p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-emerald-400 text-xs font-bold uppercase tracking-widest shadow-lg shadow-emerald-500/5 backdrop-blur-sm">
                            <i class="fas fa-user-circle"></i> Member Area
                        </span>
                        
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 text-xs font-bold uppercase tracking-widest shadow-lg shadow-blue-500/5 backdrop-blur-sm">
                                <i class="fas fa-shield-alt"></i> Akun Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-500/10 border border-amber-500/20 rounded-full text-amber-400 text-xs font-bold uppercase tracking-widest shadow-lg shadow-amber-500/5 backdrop-blur-sm animate-pulse">
                                <i class="fas fa-lock"></i> Perlu Verifikasi
                            </span>
                        @endif
                    </div>
                </div>
                
                {{-- Quick Stats Section --}}
                <div class="flex gap-8 md:gap-12 md:pr-8 py-4 md:py-0 border-t md:border-t-0 md:border-l border-white/5 w-full md:w-auto justify-center md:justify-end">
                    <div class="text-center group/stat cursor-default">
                        <div class="text-3xl font-display font-bold text-white mb-1 group-hover/stat:text-emerald-400 transition-colors">{{ $stats['likes_count'] }}</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] group-hover/stat:text-slate-300 transition-colors">Disukai</div>
                    </div>
                    <div class="text-center group/stat cursor-default">
                        <div class="text-3xl font-display font-bold text-white mb-1 group-hover/stat:text-blue-400 transition-colors">{{ $stats['comments_count'] }}</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] group-hover/stat:text-slate-300 transition-colors">Komentar</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Layout Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Navigation Sidebar --}}
            <div class="lg:col-span-3 sticky top-32 z-20">
                <nav class="flex flex-col gap-2 p-3 rounded-2xl bg-slate-900/60 backdrop-blur-md border border-white/5 shadow-xl">
                    <button @click="activeTab = 'settings'" 
                            :class="activeTab === 'settings' ? 'bg-slate-800 text-white shadow-lg ring-1 ring-white/10' : 'text-slate-400 hover:text-white hover:bg-white/5'"
                            class="w-full flex items-center gap-4 px-5 py-4 rounded-xl transition-all duration-300 group text-left relative overflow-hidden">
                        
                        {{-- Active Indicator Bar --}}
                        <div x-show="activeTab === 'settings'" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-emerald-500 rounded-r-full"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-x-full"
                             x-transition:enter-end="opacity-100 translate-x-0"></div>

                        <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors duration-300"
                             :class="activeTab === 'settings' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-800/50 text-slate-500 group-hover:text-emerald-400 group-hover:bg-emerald-500/10'">
                            <i class="fas fa-user-cog text-lg"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold tracking-wide">Pengaturan Akun</span>
                            <span class="block text-[10px] text-slate-500 font-medium mt-0.5">Ubah profil & sandi</span>
                        </div>
                    </button>
                    
                    <button @click="activeTab = 'activity'" 
                            :class="activeTab === 'activity' ? 'bg-slate-800 text-white shadow-lg ring-1 ring-white/10' : 'text-slate-400 hover:text-white hover:bg-white/5'"
                            class="w-full flex items-center gap-4 px-5 py-4 rounded-xl transition-all duration-300 group text-left relative overflow-hidden">
                        
                        <div x-show="activeTab === 'activity'" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-pink-500 rounded-r-full"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-x-full"
                             x-transition:enter-end="opacity-100 translate-x-0"></div>

                        <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors duration-300"
                             :class="activeTab === 'activity' ? 'bg-pink-500/20 text-pink-400' : 'bg-slate-800/50 text-slate-500 group-hover:text-pink-400 group-hover:bg-pink-500/10'">
                            <i class="fas fa-heart text-lg"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold tracking-wide">Aktivitas Saya</span>
                            <span class="block text-[10px] text-slate-500 font-medium mt-0.5">Likes & komentar</span>
                        </div>
                    </button>
                    
                    <button @click="activeTab = 'security'" 
                            :class="activeTab === 'security' ? 'bg-slate-800 text-white shadow-lg ring-1 ring-white/10' : 'text-slate-400 hover:text-white hover:bg-white/5'"
                            class="w-full flex items-center gap-4 px-5 py-4 rounded-xl transition-all duration-300 group text-left relative overflow-hidden">
                        
                        <div x-show="activeTab === 'security'" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-cyan-500 rounded-r-full"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-x-full"
                             x-transition:enter-end="opacity-100 translate-x-0"></div>

                        <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors duration-300"
                             :class="activeTab === 'security' ? 'bg-cyan-500/20 text-cyan-400' : 'bg-slate-800/50 text-slate-500 group-hover:text-cyan-400 group-hover:bg-cyan-500/10'">
                            <i class="fas fa-shield-alt text-lg"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold tracking-wide">Keamanan & Sesi</span>
                            <span class="block text-[10px] text-slate-500 font-medium mt-0.5">Logs & zona bahaya</span>
                        </div>
                    </button>
                </nav>
            </div>

            {{-- Tab Contents --}}
            <div class="lg:col-span-9 min-h-[400px]">
                
                {{-- Settings Tab --}}
                <div x-show="activeTab === 'settings'"
                     x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     style="display: none;"
                     class="space-y-8">
                    @include('public.profile.partials.settings-tab')
                </div>

                {{-- Activity Tab --}}
                <div x-show="activeTab === 'activity'"
                     x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     style="display: none;"
                     class="space-y-8">
                    @include('public.profile.partials.activity-tab')
                </div>

                {{-- Security Tab --}}
                <div x-show="activeTab === 'security'"
                     x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     style="display: none;"
                     class="space-y-8">
                    @include('public.profile.partials.security-tab')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function profilePage() {
    return {
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'settings',
        loading: false,
        
        async submitForm(formId, url) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            this.loading = true;
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#10b981',
                        customClass: {
                            popup: 'rounded-2xl border border-white/10'
                        }
                    });
                    if (data.user) {
                        document.getElementById('header-name').textContent = data.user.name;
                        document.getElementById('header-email').textContent = data.user.email;
                    }
                    if (data.photo_url) {
                        const img = document.getElementById('header-avatar');
                        if(img) img.src = data.photo_url;
                        const preview = document.getElementById('preview-avatar');
                        if(preview) preview.src = data.photo_url;
                    }
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#ef4444',
                        customClass: {
                            popup: 'rounded-2xl border border-white/10'
                        }
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    background: '#0f172a',
                    color: '#f8fafc',
                    confirmButtonColor: '#ef4444',
                    customClass: {
                        popup: 'rounded-2xl border border-white/10'
                    }
                });
            }
            
            this.loading = false;
        },
        
        confirmDelete() {
            Swal.fire({
                title: 'Hapus Akun?',
                html: `
                    <p class="text-slate-400 text-sm mb-6">Seluruh data Anda akan dihapus permanen. Ketik <strong class="text-rose-400">HAPUS AKUN</strong> untuk konfirmasi.</p>
                    <input type="text" id="delete-confirm" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 transition-colors text-center font-bold tracking-widest uppercase mb-3" placeholder="HAPUS AKUN">
                    <input type="password" id="delete-password" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 transition-colors text-center" placeholder="Password Anda">
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#f8fafc',
                customClass: {
                    popup: 'rounded-2xl border border-rose-500/20'
                },
                preConfirm: () => {
                    return {
                        confirmation: document.getElementById('delete-confirm').value,
                        password: document.getElementById('delete-password').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteAccount(result.value);
                }
            });
        },
        
        async deleteAccount(data) {
            try {
                const response = await fetch('{{ route("public.profile.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Akun Terhapus',
                        text: result.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan sistem',
                    background: '#0f172a',
                    color: '#f8fafc',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
    };
}
</script>
@endpush
