{{-- Global Loading Screen --}}
<div 
    id="global-loading-screen" 
    class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-surface-950 transition-opacity duration-500"
>
    {{-- Background Glow Effects --}}
    <div class="absolute top-0 left-0 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-pulse" style="animation-delay: 700ms"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-10 animate-pulse" style="animation-delay: 350ms"></div>

    {{-- Loader Container --}}
    <div class="relative flex items-center justify-center w-32 h-32 mb-8">
        
        {{-- Static Ring --}}
        <div class="absolute w-full h-full rounded-full border border-white/10"></div>
        
        {{-- Outer Spinning Ring --}}
        <div class="absolute w-full h-full rounded-full border-2 border-transparent border-t-indigo-500 border-r-purple-500 loader-spin-slow"></div>
        
        {{-- Inner Spinning Ring (Reverse) --}}
        <div class="absolute w-20 h-20 rounded-full border-2 border-transparent border-b-cyan-400 border-l-blue-500 loader-spin-reverse"></div>
        
        {{-- Center Glow Dot --}}
        <div class="absolute w-3 h-3 bg-white rounded-full loader-pulse-glow" style="box-shadow: 0 0 20px rgba(255,255,255,0.8)"></div>
        
    </div>

    {{-- Text Content --}}
    <div class="text-center z-10 relative">
        <h2 class="text-white text-sm font-light uppercase tracking-[0.3em] mb-3 animate-pulse">
            Memuat Sistem
        </h2>
        
        {{-- Bouncing Dots --}}
        <div class="flex items-center justify-center space-x-1.5 mb-4">
            <span class="block w-1.5 h-1.5 bg-gray-400 rounded-full loader-bounce" style="animation-delay: 0ms"></span>
            <span class="block w-1.5 h-1.5 bg-gray-400 rounded-full loader-bounce" style="animation-delay: 150ms"></span>
            <span class="block w-1.5 h-1.5 bg-gray-400 rounded-full loader-bounce" style="animation-delay: 300ms"></span>
        </div>
        
        {{-- Progress Percentage --}}
        <p class="text-xs text-gray-500 font-mono tabular-nums" id="loading-progress-text">0%</p>
    </div>
</div>

<style>
    /* Loading Screen Animations */
    @keyframes loaderSpinSlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @keyframes loaderSpinReverse {
        from { transform: rotate(0deg); }
        to { transform: rotate(-360deg); }
    }
    
    @keyframes loaderPulseGlow {
        0%, 100% { 
            opacity: 1; 
            transform: scale(1);
            box-shadow: 0 0 20px rgba(255,255,255,0.8);
        }
        50% { 
            opacity: 0.5; 
            transform: scale(0.95);
            box-shadow: 0 0 30px rgba(255,255,255,0.4);
        }
    }
    
    @keyframes loaderBounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-6px);
        }
    }
    
    .loader-spin-slow {
        animation: loaderSpinSlow 3s linear infinite;
    }
    
    .loader-spin-reverse {
        animation: loaderSpinReverse 2s linear infinite;
    }
    
    .loader-pulse-glow {
        animation: loaderPulseGlow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .loader-bounce {
        animation: loaderBounce 0.6s ease-in-out infinite;
    }
    
    /* Fade out animation */
    #global-loading-screen.fade-out {
        opacity: 0;
        pointer-events: none;
    }
</style>

<script>
    // Global Loading Screen Logic
    (function() {
        const loadingScreen = document.getElementById('global-loading-screen');
        const progressText = document.getElementById('loading-progress-text');
        
        let progress = 0;
        let targetProgress = 0;
        let animationFrame;
        
        // Simulate loading progress
        function updateProgress() {
            // Accelerate progress based on actual loading state
            if (document.readyState === 'loading') {
                targetProgress = Math.min(targetProgress + 2, 60);
            } else if (document.readyState === 'interactive') {
                targetProgress = Math.min(targetProgress + 5, 85);
            } else if (document.readyState === 'complete') {
                targetProgress = 100;
            }
            
            // Smooth progress animation
            if (progress < targetProgress) {
                progress += Math.min(3, targetProgress - progress);
                progressText.textContent = Math.round(progress) + '%';
            }
            
            if (progress >= 100) {
                // Complete - hide loading screen immediately
                hideLoadingScreen();
            } else {
                animationFrame = requestAnimationFrame(updateProgress);
            }
        }
        
        function hideLoadingScreen() {
            if (animationFrame) {
                cancelAnimationFrame(animationFrame);
            }
            
            progressText.textContent = '100%';
            
            // Add fade-out class
            loadingScreen.classList.add('fade-out');
            
            // Remove from DOM after animation
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 500);
        }
        
        // Start progress animation
        updateProgress();
        
        // Also listen to document ready states
        document.addEventListener('DOMContentLoaded', function() {
            targetProgress = Math.max(targetProgress, 85);
        });
        
        window.addEventListener('load', function() {
            targetProgress = 100;
        });
        
        // Fallback: force complete after maximum time
        setTimeout(() => {
            progress = 100;
            hideLoadingScreen();
        }, 3000);
    })();
</script>
