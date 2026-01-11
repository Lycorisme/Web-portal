{{-- Public Layout Scripts --}}

{{-- Login Prompt Modal --}}
<div class="modal-overlay" id="loginModal" x-data="{ open: false }" x-show="open" @keydown.escape.window="open = false" style="display: none;">
    <div class="modal" @click.away="open = false">
        <div class="modal-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h3>Login Diperlukan</h3>
        <p id="loginModalMessage">Silakan login terlebih dahulu untuk melakukan aksi ini.</p>
        <div class="modal-actions">
            <button class="btn btn-outline" onclick="closeLoginModal()">Batal</button>
            <a href="{{ route('login', ['intended' => url()->current()]) }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        </div>
    </div>
</div>

{{-- Toast Container --}}
<div class="toast-container" id="toastContainer"></div>

<script>
    // Login Modal Functions
    function showLoginPrompt(message) {
        const modal = document.getElementById('loginModal');
        const msgEl = document.getElementById('loginModalMessage');
        msgEl.textContent = message || 'Silakan login terlebih dahulu untuk melakukan aksi ini.';
        modal.classList.add('active');
        modal.style.display = 'flex';
    }

    function closeLoginModal() {
        const modal = document.getElementById('loginModal');
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 200);
    }

    // Toast Functions
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}" style="color: var(--${type === 'success' ? 'success' : 'danger'});"></i>
            <span>${message}</span>
        `;
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Show flash messages
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
</script>
