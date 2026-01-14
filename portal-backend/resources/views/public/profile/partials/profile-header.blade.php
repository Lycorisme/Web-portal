<div class="rounded-[2rem] p-6 mb-14 relative overflow-hidden bg-slate-900/40 backdrop-blur-xl border border-white/5 shadow-2xl ring-1 ring-white/5 group">
    
    {{-- Decorative Background --}}
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-purple-500/5 opacity-50 group-hover:opacity-100 transition-opacity duration-1000"></div>
    <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-gradient-to-b from-emerald-500/5 to-transparent blur-3xl rounded-full pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
        
        {{-- Editable Avatar Section --}}
        <div class="relative shrink-0 group/avatar">
            <div class="w-28 h-28 md:w-32 md:h-32 rounded-full p-1 bg-gradient-to-br from-emerald-500/30 to-purple-500/30 backdrop-blur-sm ring-1 ring-white/10 relative">
                <div class="w-full h-full rounded-full overflow-hidden bg-slate-800 relative">
                    @if($user->profile_photo)
                        <img src="{{ $user->avatar }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/avatar:scale-110" id="header-avatar">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white text-4xl font-bold font-display cursor-default">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    
                    {{-- Upload Overlay --}}
                    <label for="header-photo-input" class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-300 cursor-pointer backdrop-blur-[2px]">
                        <i class="fas fa-camera text-white text-xl mb-1"></i>
                        <span class="text-[10px] font-bold text-white uppercase tracking-wider">Ubah</span>
                    </label>

                    {{-- Loading State (optional integration with Alpine) --}}
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center z-20" x-show="loading" style="display: none;">
                        <i class="fas fa-circle-notch fa-spin text-emerald-400 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Status Check --}}
             <div class="absolute -bottom-1 -right-1 md:bottom-0 md:right-0 z-20">
                @if($user->email_verified_at)
                    <div class="w-8 h-8 bg-emerald-500 rounded-full border-[3px] border-slate-900 flex items-center justify-center shadow-lg text-white text-xs transform transition-transform hover:scale-110" title="Terverifikasi">
                        <i class="fas fa-check"></i>
                    </div>
                @else
                    <div class="w-8 h-8 bg-amber-500 rounded-full border-[3px] border-slate-900 flex items-center justify-center shadow-lg text-white text-xs transform transition-transform hover:scale-110 animate-pulse" title="Belum Verifikasi">
                        <i class="fas fa-exclamation"></i>
                    </div>
                @endif
            </div>

            {{-- Delete Button (Visible if photo exists) --}}
            @if($user->profile_photo)
                <button type="button" @click="submitForm('header-photo-delete-form', '{{ route('public.profile.photo.delete') }}')" 
                        class="absolute top-0 right-0 -mr-2 bg-rose-500/90 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg border border-rose-400/50 opacity-0 group-hover/avatar:opacity-100 transition-all hover:bg-rose-600 hover:scale-110 z-30" 
                        title="Hapus Foto">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            @endif

            {{-- Hidden Forms --}}
            <form id="header-photo-form" enctype="multipart/form-data">
                <input type="file" name="profile_photo" id="header-photo-input" accept="image/*" class="hidden"
                       onchange="submitForm('header-photo-form', '{{ route('public.profile.photo') }}')">
            </form>
            <form id="header-photo-delete-form" class="hidden">@method('DELETE')</form>
        </div>

        {{-- User Info --}}
        <div class="flex-1 min-w-0 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-display font-bold text-white mb-1 tracking-tight" id="header-name">{{ $user->name }}</h1>
            <p class="text-slate-400 text-base mb-4 font-medium" id="header-email">{{ $user->email }}</p>
            
            <div class="flex flex-wrap justify-center md:justify-start gap-2">
                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-slate-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    Member
                </span>
                @if($user->email_verified_at)
                    <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-emerald-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                        Verified
                    </span>
                @else
                     <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 rounded-lg text-amber-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                        Unverified
                    </span>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="flex items-center gap-8 md:border-l border-white/5 md:pl-8">
            <div class="text-center">
                <span class="block text-2xl font-bold text-white mb-0.5">{{ $stats['likes_count'] }}</span>
                <span class="text-[10px] uppercase tracking-[0.15em] text-slate-500 font-bold">Likes</span>
            </div>
            <div class="text-center">
                 <span class="block text-2xl font-bold text-white mb-0.5">{{ $stats['comments_count'] }}</span>
                <span class="text-[10px] uppercase tracking-[0.15em] text-slate-500 font-bold">Comments</span>
            </div>
        </div>

    </div>
</div>
