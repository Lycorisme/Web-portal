@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div x-data="reportsPage()" x-init="init()">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-space font-bold text-surface-900 dark:text-white">Laporan</h1>
                <p class="text-surface-500 dark:text-surface-400 mt-1">Generate dan unduh laporan dalam format PDF</p>
            </div>
        </div>
    </div>

    {{-- Report Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Article Report Card --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="newspaper" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Artikel</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Data artikel berdasarkan periode</p>
                    </div>
                </div>
                
                <form @submit.prevent="generateReport('articles')" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Dari Tanggal</label>
                            <input type="date" x-model="forms.articles.start_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Sampai Tanggal</label>
                            <input type="date" x-model="forms.articles.end_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Status</label>
                        <select x-model="forms.articles.status" 
                            class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                            <option value="">Semua Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <button type="submit" :disabled="loading.articles" 
                        class="w-full px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl shadow-theme hover:shadow-theme-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50">
                        <template x-if="loading.articles">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        </template>
                        <template x-if="!loading.articles">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </template>
                        <span x-text="loading.articles ? 'Generating...' : 'Unduh PDF'"></span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Category Report Card --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="folder-tree" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Kategori</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Data kategori dan jumlah artikel</p>
                    </div>
                </div>
                
                <form @submit.prevent="generateReport('categories')" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Dari Tanggal</label>
                            <input type="date" x-model="forms.categories.start_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Sampai Tanggal</label>
                            <input type="date" x-model="forms.categories.end_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Status</label>
                        <select x-model="forms.categories.is_active" 
                            class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                            <option value="">Semua Status</option>
                            <option value="true">Aktif</option>
                            <option value="false">Nonaktif</option>
                        </select>
                    </div>
                    <button type="submit" :disabled="loading.categories" 
                        class="w-full px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl shadow-theme hover:shadow-theme-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50">
                        <template x-if="loading.categories">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        </template>
                        <template x-if="!loading.categories">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </template>
                        <span x-text="loading.categories ? 'Generating...' : 'Unduh PDF'"></span>
                    </button>
                </form>
            </div>
        </div>

        {{-- User Report Card --}}
        @if(auth()->user()->canManageUsers())
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Pengguna</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Data pengguna sistem</p>
                    </div>
                </div>
                
                <form @submit.prevent="generateReport('users')" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Dari Tanggal</label>
                            <input type="date" x-model="forms.users.start_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Sampai Tanggal</label>
                            <input type="date" x-model="forms.users.end_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Role</label>
                        <select x-model="forms.users.role" 
                            class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                            <option value="">Semua Role</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="editor">Editor</option>
                            <option value="author">Author</option>
                        </select>
                    </div>
                    <button type="submit" :disabled="loading.users" 
                        class="w-full px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl shadow-theme hover:shadow-theme-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50">
                        <template x-if="loading.users">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        </template>
                        <template x-if="!loading.users">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </template>
                        <span x-text="loading.users ? 'Generating...' : 'Unduh PDF'"></span>
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Activity Log Report Card --}}
        @if(auth()->user()->canAccessSecurity())
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="activity" class="w-6 h-6 text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Activity Log</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Log aktivitas sistem</p>
                    </div>
                </div>
                
                <form @submit.prevent="generateReport('activity-logs')" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Dari Tanggal</label>
                            <input type="date" x-model="forms.activityLogs.start_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Sampai Tanggal</label>
                            <input type="date" x-model="forms.activityLogs.end_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Action</label>
                        <select x-model="forms.activityLogs.action" 
                            class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                            <option value="">Semua Action</option>
                            <option value="CREATE">CREATE</option>
                            <option value="UPDATE">UPDATE</option>
                            <option value="DELETE">DELETE</option>
                            <option value="LOGIN">LOGIN</option>
                            <option value="LOGIN_FAILED">LOGIN FAILED</option>
                            <option value="LOGOUT">LOGOUT</option>
                        </select>
                    </div>
                    <button type="submit" :disabled="loading.activityLogs" 
                        class="w-full px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl shadow-theme hover:shadow-theme-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50">
                        <template x-if="loading.activityLogs">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        </template>
                        <template x-if="!loading.activityLogs">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </template>
                        <span x-text="loading.activityLogs ? 'Generating...' : 'Unduh PDF'"></span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Blocked Client Report Card --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="shield-ban" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan IP Terblokir</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Data IP yang diblokir</p>
                    </div>
                </div>
                
                <form @submit.prevent="generateReport('blocked-clients')" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Dari Tanggal</label>
                            <input type="date" x-model="forms.blockedClients.start_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Sampai Tanggal</label>
                            <input type="date" x-model="forms.blockedClients.end_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Status</label>
                        <select x-model="forms.blockedClients.is_blocked" 
                            class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                            <option value="">Semua Status</option>
                            <option value="true">Terblokir</option>
                            <option value="false">Tidak Terblokir</option>
                        </select>
                    </div>
                    <button type="submit" :disabled="loading.blockedClients" 
                        class="w-full px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl shadow-theme hover:shadow-theme-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50">
                        <template x-if="loading.blockedClients">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        </template>
                        <template x-if="!loading.blockedClients">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </template>
                        <span x-text="loading.blockedClients ? 'Generating...' : 'Unduh PDF'"></span>
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Gallery Report Card --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="images" class="w-6 h-6 text-pink-600 dark:text-pink-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Gallery</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Data media gallery</p>
                    </div>
                </div>
                
                <form @submit.prevent="generateReport('galleries')" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Dari Tanggal</label>
                            <input type="date" x-model="forms.galleries.start_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Sampai Tanggal</label>
                            <input type="date" x-model="forms.galleries.end_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Tipe Media</label>
                        <select x-model="forms.galleries.media_type" 
                            class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                            <option value="">Semua Tipe</option>
                            <option value="image">Gambar</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    <button type="submit" :disabled="loading.galleries" 
                        class="w-full px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl shadow-theme hover:shadow-theme-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50">
                        <template x-if="loading.galleries">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        </template>
                        <template x-if="!loading.galleries">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </template>
                        <span x-text="loading.galleries ? 'Generating...' : 'Unduh PDF'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Section --}}
    <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
        <div class="flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5"></i>
            <div>
                <h4 class="font-medium text-blue-900 dark:text-blue-300">Informasi</h4>
                <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                    Laporan akan di-generate dalam format PDF dengan kop surat dari pengaturan situs. 
                    Pastikan logo, alamat, dan informasi lainnya sudah diatur di halaman Pengaturan Situs.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function reportsPage() {
    return {
        loading: {
            articles: false,
            categories: false,
            users: false,
            activityLogs: false,
            blockedClients: false,
            galleries: false
        },
        forms: {
            articles: {
                start_date: '',
                end_date: '',
                status: ''
            },
            categories: {
                start_date: '',
                end_date: '',
                is_active: ''
            },
            users: {
                start_date: '',
                end_date: '',
                role: ''
            },
            activityLogs: {
                start_date: '',
                end_date: '',
                action: ''
            },
            blockedClients: {
                start_date: '',
                end_date: '',
                is_blocked: ''
            },
            galleries: {
                start_date: '',
                end_date: '',
                media_type: ''
            }
        },

        init() {
            // Set default date range (last 30 days)
            const today = new Date();
            const lastMonth = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
            
            const formatDate = (date) => date.toISOString().split('T')[0];
            
            Object.keys(this.forms).forEach(key => {
                this.forms[key].start_date = formatDate(lastMonth);
                this.forms[key].end_date = formatDate(today);
            });

            // Reinitialize Lucide icons
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        async generateReport(type) {
            // Map form key to loading key
            const loadingKeys = {
                'articles': 'articles',
                'categories': 'categories',
                'users': 'users',
                'activity-logs': 'activityLogs',
                'blocked-clients': 'blockedClients',
                'galleries': 'galleries'
            };

            const formKeys = {
                'articles': 'articles',
                'categories': 'categories',
                'users': 'users',
                'activity-logs': 'activityLogs',
                'blocked-clients': 'blockedClients',
                'galleries': 'galleries'
            };

            const loadingKey = loadingKeys[type];
            const formKey = formKeys[type];
            
            this.loading[loadingKey] = true;

            try {
                const form = this.forms[formKey];
                const params = new URLSearchParams();

                Object.keys(form).forEach(key => {
                    if (form[key]) {
                        params.append(key, form[key]);
                    }
                });

                const url = `/reports/${type}?${params.toString()}`;
                
                // Open in new tab or download
                window.location.href = url;

                // Small delay to show loading state
                await new Promise(resolve => setTimeout(resolve, 1000));
            } catch (error) {
                console.error('Error generating report:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat generate laporan.',
                    confirmButtonColor: 'var(--theme-primary)',
                });
            } finally {
                this.loading[loadingKey] = false;
            }
        }
    };
}
</script>
@endpush
