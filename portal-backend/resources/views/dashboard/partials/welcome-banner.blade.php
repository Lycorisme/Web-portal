<div class="relative overflow-hidden rounded-2xl bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 p-6 shadow-xl shadow-theme-500/5 group">
    
    {{-- Abstract Art / Glows --}}
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-theme-500/10 rounded-full blur-3xl pointer-events-none opacity-50 mix-blend-screen animate-pulse-slow"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-theme-300/10 rounded-full blur-3xl pointer-events-none opacity-30 mix-blend-screen"></div>
    
    <!-- Grid Pattern Overlay -->
     <div class="absolute inset-0 bg-[linear-gradient(to_right,#00000005_1px,transparent_1px),linear-gradient(to_bottom,#00000005_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:24px_24px] mask-image:linear-gradient(to_bottom,black,transparent) pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
        
        {{-- Left Side: Text --}}
        <div class="flex-1 text-center md:text-left">
            <div class="flex items-center justify-center md:justify-start gap-3 mb-3">
                 <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-surface-100/80 dark:bg-surface-800/80 border border-surface-200 dark:border-surface-700 backdrop-blur-md">
                     <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] uppercase tracking-wider font-bold text-surface-500 dark:text-surface-400">System Online</span>
                </div>
            </div>
            
            <h1 class="text-2xl lg:text-3xl font-bold text-surface-900 dark:text-white tracking-tight mb-2">
                Welcome back, 
                <span class="text-theme-600 dark:text-theme-400">
                    {{ Auth::user()->name ?? 'Administrator' }}
                </span> 
                <span class="inline-block animate-wave origin-bottom-right">👋</span>
            </h1>
            
            <p class="text-sm text-surface-500 dark:text-surface-400 font-medium max-w-xl mx-auto md:mx-0">
                @if($stats['draft_articles'] > 0)
                    You have <span class="text-surface-900 dark:text-white font-semibold">{{ $stats['draft_articles'] }} drafts</span> pending review.
                @else
                    Everything is up to date. Have a productive day!
                @endif
            </p>

            {{-- Compact Actions --}}
            @if($stats['draft_articles'] > 0)
            <div class="flex items-center justify-center md:justify-start gap-3 mt-5">
                <a href="#" class="px-5 py-2.5 bg-surface-900 dark:bg-surface-800 text-white border border-surface-700 rounded-lg text-sm font-semibold hover:bg-surface-800 dark:hover:bg-surface-700 transition-colors flex items-center gap-2">
                    <i data-lucide="file-check" class="w-4 h-4 text-theme-400"></i>
                    <span>Review Draft</span>
                </a>
            </div>
            @endif
        </div>

        {{-- Right Side: Clock Widget (From Settings) --}}
        <div class="hidden md:flex items-center gap-4 px-5 py-2.5 bg-white/50 dark:bg-surface-800/50 backdrop-blur-md border border-surface-200/60 dark:border-surface-700/60 rounded-2xl shadow-lg shadow-surface-200/10 dark:shadow-surface-900/10 hover:shadow-xl hover:scale-[1.02] hover:bg-white dark:hover:bg-surface-800 hover:border-theme-500/20 dark:hover:border-theme-500/20 transition-all duration-300 group/clock"
             x-data="{
                timestamp: {{ now()->timestamp * 1000 }},
                hours: '00',
                minutes: '00',
                seconds: '00',
                dayName: '',
                fullDate: '',
                init() {
                    this.update();
                    setInterval(() => {
                        this.timestamp += 1000;
                        this.update();
                    }, 1000);
                },
                update() {
                    const date = new Date(this.timestamp);
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    
                    this.dayName = days[date.getDay()];
                    this.fullDate = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
                    this.hours = String(date.getHours()).padStart(2, '0');
                    this.minutes = String(date.getMinutes()).padStart(2, '0');
                    this.seconds = String(date.getSeconds()).padStart(2, '0');
                }
             }"
             x-cloak>
            
            {{-- Animated Icon --}}
            <div class="relative">
                <div class="absolute inset-0 bg-theme-500 rounded-full blur opacity-0 group-hover/clock:opacity-20 transition-opacity duration-500"></div>
                <div class="relative p-2.5 bg-gradient-to-br from-theme-50 to-theme-100 dark:from-theme-900/40 dark:to-theme-800/40 rounded-xl text-theme-600 dark:text-theme-400 group-hover/clock:rotate-12 transition-transform duration-500">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>

            {{-- Divider --}}
            <div class="h-8 w-px bg-surface-200 dark:bg-surface-700/50"></div>

            {{-- Time Display --}}
            <div class="flex flex-col">
                <div class="flex items-baseline gap-0.5">
                     <span class="text-xl font-bold font-space text-surface-900 dark:text-white tracking-tight" x-text="hours"></span>
                     <span class="text-theme-500 font-bold animate-pulse px-0.5">:</span>
                     <span class="text-xl font-bold font-space text-surface-900 dark:text-white tracking-tight" x-text="minutes"></span>
                     <span class="text-surface-400 font-bold px-0.5">:</span>
                     <span class="text-base font-medium font-space text-surface-500 dark:text-surface-400" x-text="seconds"></span>
                </div>
                <div class="flex items-center gap-1.5 text-xs font-medium text-surface-500 dark:text-surface-400">
                    <span x-text="dayName" class="text-theme-600 dark:text-theme-400"></span>
                    <span class="w-1 h-1 rounded-full bg-surface-300 dark:bg-surface-600"></span>
                    <span x-text="fullDate"></span>
                </div>
            </div>
        </div>

    </div>
</div>
