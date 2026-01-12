{{-- Navigation Sidebar --}}
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
