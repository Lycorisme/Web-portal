{{-- Custom Toast Notification System --}}
{{-- Modern Pro Toast with Theme Integration --}}

<style>
    /* Toast Container */
    #custom-toast-container {
        position: fixed;
        top: 1.5rem;
        right: 1.5rem;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        width: 24rem;
        max-width: calc(100vw - 3rem);
        pointer-events: none;
    }

    /* Toast Animation */
    @keyframes toast-countdown {
        from { width: 100%; }
        to { width: 0%; }
    }
    
    .toast-animate-countdown {
        animation: toast-countdown linear forwards;
    }

    @keyframes toast-slide-in {
        from {
            transform: translateX(120%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes toast-slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(120%);
            opacity: 0;
        }
    }

    .toast-slide-in {
        animation: toast-slide-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .toast-slide-out {
        animation: toast-slide-out 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Toast Item Base */
    .custom-toast-item {
        pointer-events: auto;
        position: relative;
        width: 100%;
        overflow: hidden;
        border-radius: 0.75rem;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 12px 24px -8px rgba(0, 0, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Light Mode */
    .custom-toast-item {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    /* Dark Mode */
    .dark .custom-toast-item {
        background: rgba(24, 24, 27, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* Toast Hover Effect */
    .custom-toast-item:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 30px 60px -15px rgba(0, 0, 0, 0.3),
            0 15px 30px -10px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }

    /* Toast Content */
    .toast-content {
        display: flex;
        gap: 1rem;
        padding: 1rem;
    }

    /* Toast Icon Container */
    .toast-icon-wrapper {
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .toast-icon-circle {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        ring: 1px;
        ring-inset: true;
    }

    /* Light Mode Icon Backgrounds */
    .toast-icon-circle.toast-success {
        background: rgba(16, 185, 129, 0.15);
        box-shadow: inset 0 0 0 1px rgba(16, 185, 129, 0.2);
    }
    .toast-icon-circle.toast-error {
        background: rgba(239, 68, 68, 0.15);
        box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.2);
    }
    .toast-icon-circle.toast-warning {
        background: rgba(245, 158, 11, 0.15);
        box-shadow: inset 0 0 0 1px rgba(245, 158, 11, 0.2);
    }
    .toast-icon-circle.toast-info {
        background: rgb(var(--theme-500) / 0.15);
        box-shadow: inset 0 0 0 1px rgb(var(--theme-500) / 0.2);
    }

    /* Dark Mode Icon Backgrounds */
    .dark .toast-icon-circle.toast-success {
        background: rgba(16, 185, 129, 0.2);
        box-shadow: inset 0 0 0 1px rgba(16, 185, 129, 0.3);
    }
    .dark .toast-icon-circle.toast-error {
        background: rgba(239, 68, 68, 0.2);
        box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.3);
    }
    .dark .toast-icon-circle.toast-warning {
        background: rgba(245, 158, 11, 0.2);
        box-shadow: inset 0 0 0 1px rgba(245, 158, 11, 0.3);
    }
    .dark .toast-icon-circle.toast-info {
        background: rgb(var(--theme-500) / 0.2);
        box-shadow: inset 0 0 0 1px rgb(var(--theme-500) / 0.3);
    }

    .toast-icon-circle svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .toast-icon-circle.toast-success svg { color: #10b981; }
    .toast-icon-circle.toast-error svg { color: #ef4444; }
    .toast-icon-circle.toast-warning svg { color: #f59e0b; }
    .toast-icon-circle.toast-info svg { color: rgb(var(--theme-500)); }

    /* Toast Text */
    .toast-text-wrapper {
        flex: 1;
        min-width: 0;
    }

    .toast-title {
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 0.25rem;
        color: #18181b;
    }

    .dark .toast-title {
        color: #ffffff;
    }

    .toast-message {
        font-size: 0.8125rem;
        line-height: 1.5;
        color: #71717a;
    }

    .dark .toast-message {
        color: #a1a1aa;
    }

    /* Toast Close Button */
    .toast-close-btn {
        flex-shrink: 0;
        margin-top: -0.25rem;
        margin-right: -0.25rem;
        padding: 0.375rem;
        border-radius: 0.5rem;
        color: #71717a;
        transition: all 0.2s ease;
        cursor: pointer;
        background: transparent;
        border: none;
    }

    .toast-close-btn:hover {
        color: #18181b;
        background: rgba(0, 0, 0, 0.05);
    }

    .dark .toast-close-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
    }

    .toast-close-btn svg {
        width: 1rem;
        height: 1rem;
    }

    /* Toast Progress Bar */
    .toast-progress-wrapper {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: rgba(0, 0, 0, 0.08);
    }

    .dark .toast-progress-wrapper {
        background: rgba(255, 255, 255, 0.1);
    }

    .toast-progress-bar {
        height: 100%;
        border-radius: 0 0 0.75rem 0.75rem;
    }

    .toast-progress-bar.toast-success { background: linear-gradient(90deg, #10b981, #34d399); }
    .toast-progress-bar.toast-error { background: linear-gradient(90deg, #ef4444, #f87171); }
    .toast-progress-bar.toast-warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .toast-progress-bar.toast-info { 
        background: linear-gradient(90deg, var(--theme-gradient-from), var(--theme-gradient-to)); 
    }

    /* Glow Effect for Toast Types */
    .custom-toast-item.glow-success {
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 0 30px -5px rgba(16, 185, 129, 0.2);
    }
    .custom-toast-item.glow-error {
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 0 30px -5px rgba(239, 68, 68, 0.2);
    }
    .custom-toast-item.glow-warning {
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 0 30px -5px rgba(245, 158, 11, 0.2);
    }
    .custom-toast-item.glow-info {
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 0 30px -5px rgb(var(--theme-500) / 0.3);
    }

    /* Responsive */
    @media (max-width: 640px) {
        #custom-toast-container {
            left: 1rem;
            right: 1rem;
            top: 1rem;
            width: auto;
            max-width: none;
        }
    }
</style>

{{-- Toast Container --}}
<div id="custom-toast-container"></div>

<script>
    // Toast Icons SVG
    const toastIcons = {
        success: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>`,
        error: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>`,
        warning: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>`,
        info: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>`
    };

    // Close Icon SVG
    const closeIcon = `<svg viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
    </svg>`;

    // Create Custom Toast
    function createCustomToast(type, title, message = '', options = {}) {
        const container = document.getElementById('custom-toast-container');
        if (!container) return;

        const duration = options.duration || 4000;
        const icon = toastIcons[type] || toastIcons.info;

        // Create Toast Element
        const toast = document.createElement('div');
        toast.className = `custom-toast-item glow-${type}`;
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(120%)';

        toast.innerHTML = `
            <div class="toast-content">
                <div class="toast-icon-wrapper">
                    <div class="toast-icon-circle toast-${type}">
                        ${icon}
                    </div>
                </div>
                <div class="toast-text-wrapper">
                    <p class="toast-title">${title}</p>
                    ${message ? `<p class="toast-message">${message}</p>` : ''}
                </div>
                <button class="toast-close-btn" onclick="removeCustomToast(this.closest('.custom-toast-item'))">
                    ${closeIcon}
                </button>
            </div>
            <div class="toast-progress-wrapper">
                <div class="toast-progress-bar toast-${type} toast-animate-countdown" style="animation-duration: ${duration}ms;"></div>
            </div>
        `;

        container.appendChild(toast);

        // Trigger Slide In Animation
        requestAnimationFrame(() => {
            toast.style.transition = 'all 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        });

        // Auto remove timeout
        let timeoutId = setTimeout(() => {
            removeCustomToast(toast);
        }, duration);

        // Pause on hover
        toast.addEventListener('mouseenter', () => {
            const progressBar = toast.querySelector('.toast-animate-countdown');
            if (progressBar) {
                progressBar.style.animationPlayState = 'paused';
            }
            clearTimeout(timeoutId);
        });

        // Resume on mouse leave
        toast.addEventListener('mouseleave', () => {
            const progressBar = toast.querySelector('.toast-animate-countdown');
            if (progressBar) {
                progressBar.style.animationPlayState = 'running';
            }
            // Set a shorter timeout after hover
            timeoutId = setTimeout(() => {
                removeCustomToast(toast);
            }, 1500);
        });

        return toast;
    }

    // Remove Custom Toast
    function removeCustomToast(element) {
        if (!element || element.classList.contains('removing')) return;
        
        element.classList.add('removing');
        element.style.transition = 'all 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
        element.style.opacity = '0';
        element.style.transform = 'translateX(120%)';

        setTimeout(() => {
            if (element && element.parentNode) {
                element.remove();
            }
        }, 500);
    }

    // Global Toast Function (replaces SweetAlert Toast)
    function showToast(type, title, message = '') {
        createCustomToast(type, title, message);
    }

    // Alias functions for convenience
    function toastSuccess(title, message = '') {
        createCustomToast('success', title, message);
    }

    function toastError(title, message = '') {
        createCustomToast('error', title, message);
    }

    function toastWarning(title, message = '') {
        createCustomToast('warning', title, message);
    }

    function toastInfo(title, message = '') {
        createCustomToast('info', title, message);
    }
</script>
