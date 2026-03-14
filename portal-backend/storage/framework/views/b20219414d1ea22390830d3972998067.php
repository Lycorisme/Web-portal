
<?php
    use Illuminate\Support\Str;
    // Fetch latest real data for notifications
    $notifications = collect();

    // 1. Blocked IPs
    try {
        $blockedIps = \App\Models\BlockedClient::latest()
            ->take(3)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'blocked_ip',
                    'title' => 'IP ' . $item->ip_address . ' terblokir',
                    'desc' => $item->reason ? Str::limit($item->reason, 40) : 'Aktivitas mencurigakan',
                    'time' => $item->created_at,
                    'icon' => 'shield-alert',
                    'color' => 'text-accent-amber',
                    'bg' => 'bg-accent-amber/20'
                ];
            });
        $notifications = $notifications->merge($blockedIps);
    } catch (\Exception $e) {}

    // 2. Published Articles
    try {
        $articles = \App\Models\Article::where('status', 'published')
            ->latest()
            ->take(3)
            ->with('author')
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'article',
                    'title' => 'Berita dipublish: ' . Str::limit($item->title, 25),
                    'desc' => 'Oleh ' . ($item->author->name ?? 'Admin'),
                    'time' => $item->updated_at ?? $item->created_at,
                    'icon' => 'check-circle',
                    'color' => 'text-accent-emerald',
                    'bg' => 'bg-accent-emerald/20'
                ];
            });
        $notifications = $notifications->merge($articles);
    } catch (\Exception $e) {}

    // 3. New Users
    try {
        $users = \App\Models\User::latest()
            ->take(2)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'user',
                    'title' => 'User baru: ' . $item->name,
                    'desc' => $item->email,
                    'time' => $item->created_at,
                    'icon' => 'user-plus',
                    'color' => 'text-theme-600 dark:text-theme-400',
                    'bg' => 'bg-theme-100 dark:bg-theme-500/20'
                ];
            });
        $notifications = $notifications->merge($users);
    } catch (\Exception $e) {}

    // Sort and limit
    $notifications = $notifications->sortByDesc('time')->take(8);
?>

<template x-teleport="body">
    <div 
        x-show="showNotificationModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        
        <div 
            x-show="showNotificationModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="showNotificationModal = false"
        ></div>

        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="showNotificationModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                @click.stop
            >
                
                <div class="bg-gradient-to-r from-theme-500 to-theme-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Notifikasi</h3>
                        <button @click="showNotificationModal = false" class="p-2 rounded-xl hover:bg-white/20 transition-colors">
                            <i data-lucide="x" class="w-5 h-5 text-white"></i>
                        </button>
                    </div>
                </div>

                
                <div class="max-h-[60vh] overflow-y-auto">
                    
                    <div class="divide-y divide-surface-100 dark:divide-surface-800/50">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="#" class="flex items-start gap-4 p-5 hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors group">
                            <div class="w-10 h-10 rounded-xl <?php echo e($notif['bg']); ?> flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="<?php echo e($notif['icon']); ?>" class="w-5 h-5 <?php echo e($notif['color']); ?>"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-surface-900 dark:text-white group-hover:text-theme-600 transition-colors"><?php echo e($notif['title']); ?></p>
                                <p class="text-xs text-surface-500 mt-1"><?php echo e($notif['desc']); ?> • <?php echo e($notif['time']->diffForHumans()); ?></p>
                            </div>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-8 text-center text-surface-500">
                            <i data-lucide="bell-off" class="w-12 h-12 mx-auto mb-3 text-surface-300"></i>
                            <p>Belum ada notifikasi</p>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <div class="p-4 border-t border-surface-200 dark:border-surface-800 bg-surface-50 dark:bg-surface-800/50">
                    <a href="#" class="block w-full py-2.5 text-center text-sm font-medium text-theme-600 dark:text-theme-400 hover:text-theme-700 dark:hover:text-theme-300 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views/partials/notification-modal.blade.php ENDPATH**/ ?>