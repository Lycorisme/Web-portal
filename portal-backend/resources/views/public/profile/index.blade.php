@extends('public.layouts.public')

@section('meta_title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .sidebar-link.active {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(6, 182, 212, 0.1));
        border-color: rgba(16, 185, 129, 0.3);
        color: #10b981;
    }
    .input-field {
        @apply w-full bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all;
    }
    .btn-primary {
        @apply px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 hover:-translate-y-0.5;
    }
    .btn-secondary {
        @apply px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl border border-slate-700 transition-all;
    }
    .btn-danger {
        @apply px-6 py-3 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold rounded-xl border border-rose-500/30 transition-all;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen pt-28 pb-16" x-data="profilePage()">
    <div class="max-w-7xl mx-auto px-6">
        
        {{-- Profile Header --}}
        <div class="glass-card rounded-3xl p-8 mb-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 via-transparent to-purple-500/5"></div>
            <div class="relative flex flex-col md:flex-row items-center gap-6">
                {{-- Avatar --}}
                <div class="relative group">
                    <div class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-emerald-500/20 group-hover:ring-emerald-500/40 transition-all shadow-2xl">
                        @if($user->profile_photo)
                            <img src="{{ $user->avatar }}" class="w-full h-full object-cover" id="header-avatar">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white text-4xl font-bold" id="header-avatar-placeholder">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 bg-emerald-500 rounded-full border-4 border-slate-900 flex items-center justify-center">
                        @if($user->email_verified_at)
                            <i class="fas fa-check text-white text-xs"></i>
                        @else
                            <i class="fas fa-exclamation text-white text-xs"></i>
                        @endif
                    </div>
                </div>
                
                {{-- Info --}}
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-3xl font-display font-bold text-white mb-2" id="header-name">{{ $user->name }}</h1>
                    <p class="text-slate-400 mb-3" id="header-email">{{ $user->email }}</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2">
                        <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 rounded-full text-emerald-400 text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-user mr-1"></i> Member
                        </span>
                        @if($user->email_verified_at)
                            <span class="px-3 py-1 bg-blue-500/10 border border-blue-500/30 rounded-full text-blue-400 text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-shield-alt mr-1"></i> Terverifikasi
                            </span>
                        @else
                            <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/30 rounded-full text-amber-400 text-xs font-bold uppercase tracking-wider">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Belum Verifikasi
                            </span>
                        @endif
                    </div>
                </div>
                
                {{-- Stats --}}
                <div class="flex gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $stats['likes_count'] }}</div>
                        <div class="text-xs text-slate-500 uppercase tracking-wider">Likes</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $stats['comments_count'] }}</div>
                        <div class="text-xs text-slate-500 uppercase tracking-wider">Komentar</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Sidebar Navigation --}}
            <div class="lg:col-span-1">
                <div class="glass-card rounded-2xl p-4 sticky top-28 space-y-2">
                    <button @click="activeTab = 'settings'" 
                            :class="activeTab === 'settings' ? 'active' : ''"
                            class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all border border-transparent">
                        <i class="fas fa-user-cog w-5"></i>
                        <span class="font-semibold">Pengaturan Akun</span>
                    </button>
                    <button @click="activeTab = 'activity'" 
                            :class="activeTab === 'activity' ? 'active' : ''"
                            class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all border border-transparent">
                        <i class="fas fa-history w-5"></i>
                        <span class="font-semibold">Aktivitas Saya</span>
                    </button>
                    <button @click="activeTab = 'security'" 
                            :class="activeTab === 'security' ? 'active' : ''"
                            class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all border border-transparent">
                        <i class="fas fa-shield-alt w-5"></i>
                        <span class="font-semibold">Keamanan</span>
                    </button>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="lg:col-span-3">
                {{-- Tab: Settings --}}
                <div x-show="activeTab === 'settings'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    @include('public.profile.partials.settings-tab')
                </div>

                {{-- Tab: Activity --}}
                <div x-show="activeTab === 'activity'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    @include('public.profile.partials.activity-tab')
                </div>

                {{-- Tab: Security --}}
                <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
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
                        background: '#1e293b',
                        color: '#fff',
                        confirmButtonColor: '#10b981'
                    });
                    if (data.user) {
                        document.getElementById('header-name').textContent = data.user.name;
                        document.getElementById('header-email').textContent = data.user.email;
                    }
                    if (data.photo_url) {
                        location.reload();
                    }
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        background: '#1e293b',
                        color: '#fff',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    background: '#1e293b',
                    color: '#fff',
                    confirmButtonColor: '#ef4444'
                });
            }
            
            this.loading = false;
        },
        
        confirmDelete() {
            Swal.fire({
                title: 'Hapus Akun?',
                html: `
                    <p class="text-slate-400 mb-4">Tindakan ini tidak dapat dibatalkan. Ketik <strong class="text-rose-400">HAPUS AKUN</strong> untuk konfirmasi.</p>
                    <input type="text" id="delete-confirm" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white" placeholder="Ketik: HAPUS AKUN">
                    <input type="password" id="delete-password" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white mt-3" placeholder="Password Anda">
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Hapus Akun',
                cancelButtonText: 'Batal',
                background: '#1e293b',
                color: '#fff',
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
                        title: 'Akun Dihapus',
                        text: result.message,
                        background: '#1e293b',
                        color: '#fff'
                    }).then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message,
                        background: '#1e293b',
                        color: '#fff'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan',
                    background: '#1e293b',
                    color: '#fff'
                });
            }
        }
    };
}
</script>
@endpush
