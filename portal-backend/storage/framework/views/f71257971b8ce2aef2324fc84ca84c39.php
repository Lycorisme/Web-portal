<?php $__env->startSection('title', 'Laporan'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="reportsPage()" x-init="init()">
    
    <?php echo $__env->make('reports.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-slide-up" style="animation-delay: 0.1s;">

        
        
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->canManageUsers()): ?>
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
                            <option value="member">Member</option>
                        </select>
                    </div>
                    <?php echo $__env->make('reports.partials.submit-button', ['key' => 'users'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </form>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        
        
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="newspaper" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Berita</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Data berita / artikel</p>
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
                    <?php echo $__env->make('reports.partials.submit-button', ['key' => 'articles'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </form>
            </div>
        </div>

        
        
        
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="folder-tree" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Kategori</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Data kategori berita</p>
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
                    <?php echo $__env->make('reports.partials.submit-button', ['key' => 'categories'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </form>
            </div>
        </div>

        
        
        
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
                    <?php echo $__env->make('reports.partials.submit-button', ['key' => 'galleries'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </form>
            </div>
        </div>

        
        
        
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="message-circle-heart" class="w-6 h-6 text-cyan-600 dark:text-cyan-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Interaksi</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Komentar, likes & views</p>
                    </div>
                </div>
                
                <form @submit.prevent="generateReport('interactions')" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Dari Tanggal</label>
                            <input type="date" x-model="forms.interactions.start_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Sampai Tanggal</label>
                            <input type="date" x-model="forms.interactions.end_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                    </div>
                    <p class="text-xs text-surface-400 dark:text-surface-500 italic">Menampilkan artikel published diurutkan dari views terbanyak</p>
                    <?php echo $__env->make('reports.partials.submit-button', ['key' => 'interactions'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </form>
            </div>
        </div>

        
        
        
        <?php if(auth()->user()->canAccessSecurity()): ?>
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="activity" class="w-6 h-6 text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Activity Log</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Audit trail sistem</p>
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
                    <?php echo $__env->make('reports.partials.submit-button', ['key' => 'activityLogs'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </form>
            </div>
        </div>

        
        
        
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="shield-alert" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Laporan Keamanan</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Keamanan & IP terblokir</p>
                    </div>
                </div>
                
                <form @submit.prevent="generateReport('security')" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Dari Tanggal</label>
                            <input type="date" x-model="forms.security.start_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Sampai Tanggal</label>
                            <input type="date" x-model="forms.security.end_date" 
                                class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Status</label>
                        <select x-model="forms.security.is_blocked" 
                            class="w-full px-3 py-2 text-sm border border-surface-300 dark:border-surface-600 rounded-lg bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-theme-primary">
                            <option value="">Semua Status</option>
                            <option value="true">Terblokir</option>
                            <option value="false">Tidak Terblokir</option>
                        </select>
                    </div>
                    <?php echo $__env->make('reports.partials.submit-button', ['key' => 'security'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </form>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        
        
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                        <i data-lucide="bar-chart-3" class="w-6 h-6 text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-white">Statistik & Rekap</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Ringkasan eksekutif</p>
                    </div>
                </div>
                
                <form @submit.prevent="generateReport('statistics')" class="space-y-4">
                    <p class="text-xs text-surface-400 dark:text-surface-500 italic">
                        Laporan ringkasan seluruh data sistem: pengguna per role, artikel per status, gallery, interaksi, keamanan, dan top 5 artikel terpopuler.
                    </p>
                    <?php echo $__env->make('reports.partials.submit-button', ['key' => 'statistics'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </form>
            </div>
        </div>
    </div>

    
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo $__env->make('reports.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views/reports/index.blade.php ENDPATH**/ ?>