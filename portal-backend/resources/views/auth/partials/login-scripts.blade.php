{{-- Login Page Scripts --}}
<script>
    function authPanel() {
        return {
            mode: 'login',
            resetStep: 1,
            resetEmail: '',
            maskedEmail: '',
            otpDigits: ['', '', '', '', '', ''],
            resetToken: '',
            newPassword: '',
            confirmPassword: '',
            showPassword: false,
            isLoading: false,
            errorMessage: '',
            successMessage: '',
            countdown: 0,
            resendCooldown: 0,
            countdownInterval: null,
            resendInterval: null,
            
            get passwordStrength() {
                const password = this.newPassword;
                if (!password) return 0;
                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^a-zA-Z0-9]/.test(password)) strength++;
                return strength;
            },
            
            switchMode(newMode) {
                this.errorMessage = '';
                this.successMessage = '';
                if (newMode === 'login' || newMode === 'register') {
                    this.resetStep = 1;
                    this.resetEmail = '';
                    this.otpDigits = ['', '', '', '', '', ''];
                    this.resetToken = '';
                    this.newPassword = '';
                    this.confirmPassword = '';
                    this.clearCountdown();
                }
                this.mode = newMode;
                this.$nextTick(() => { lucide.createIcons(); });
            },
            
            async sendOtp() {
                if (!this.resetEmail) { this.errorMessage = 'Email wajib diisi.'; return; }
                this.isLoading = true;
                this.errorMessage = '';
                this.successMessage = '';
                try {
                    const response = await fetch('{{ route("password.send-otp") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ email: this.resetEmail })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.successMessage = data.message;
                        this.maskedEmail = data.email_masked;
                        this.countdown = data.expires_in || 600;
                        this.startCountdown();
                        this.startResendCooldown(60);
                        this.resetStep = 2;
                        this.$nextTick(() => { document.getElementById('otp-0')?.focus(); lucide.createIcons(); });
                    } else { this.errorMessage = data.message; }
                } catch (error) { this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.'; console.error('Send OTP error:', error); }
                finally { this.isLoading = false; }
            },
            
            async verifyOtp() {
                const otp = this.otpDigits.join('');
                if (otp.length !== 6) { this.errorMessage = 'Masukkan kode OTP 6 digit.'; return; }
                this.isLoading = true;
                this.errorMessage = '';
                this.successMessage = '';
                try {
                    const response = await fetch('{{ route("password.verify-otp") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ email: this.resetEmail, otp: otp })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.successMessage = data.message;
                        this.resetToken = data.reset_token;
                        this.clearCountdown();
                        this.resetStep = 3;
                        this.$nextTick(() => { lucide.createIcons(); });
                    } else {
                        this.errorMessage = data.message;
                        this.otpDigits = ['', '', '', '', '', ''];
                        this.$nextTick(() => { document.getElementById('otp-0')?.focus(); });
                    }
                } catch (error) { this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.'; console.error('Verify OTP error:', error); }
                finally { this.isLoading = false; }
            },
            
            async resetPassword() {
                if (!this.newPassword || this.newPassword !== this.confirmPassword) { this.errorMessage = 'Password tidak valid atau tidak cocok.'; return; }
                if (this.passwordStrength < 2) { this.errorMessage = 'Password terlalu lemah.'; return; }
                this.isLoading = true;
                this.errorMessage = '';
                this.successMessage = '';
                try {
                    const response = await fetch('{{ route("password.reset") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ email: this.resetEmail, reset_token: this.resetToken, password: this.newPassword, password_confirmation: this.confirmPassword })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.successMessage = data.message;
                        setTimeout(() => { this.switchMode('login'); this.successMessage = 'Password berhasil diperbarui. Silakan login.'; }, 2000);
                    } else { this.errorMessage = data.message; }
                } catch (error) { this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.'; console.error('Reset password error:', error); }
                finally { this.isLoading = false; }
            },
            
            async resendOtp() { if (this.resendCooldown > 0) return; await this.sendOtp(); },
            
            handleOtpInput(event, index) {
                const value = event.target.value;
                if (!/^\d*$/.test(value)) { this.otpDigits[index] = ''; return; }
                if (value && index < 5) { document.getElementById('otp-' + (index + 1))?.focus(); }
            },
            
            handleOtpBackspace(event, index) {
                if (!this.otpDigits[index] && index > 0) { document.getElementById('otp-' + (index - 1))?.focus(); }
            },
            
            handleOtpPaste(event) {
                event.preventDefault();
                const pastedData = event.clipboardData.getData('text').trim();
                if (/^\d{6}$/.test(pastedData)) {
                    for (let i = 0; i < 6; i++) { this.otpDigits[i] = pastedData[i]; }
                    document.getElementById('otp-5')?.focus();
                }
            },
            
            startCountdown() {
                this.clearCountdown();
                this.countdownInterval = setInterval(() => {
                    if (this.countdown > 0) { this.countdown--; } else { this.clearCountdown(); }
                }, 1000);
            },
            
            clearCountdown() { if (this.countdownInterval) { clearInterval(this.countdownInterval); this.countdownInterval = null; } },
            
            formatCountdown() {
                const minutes = Math.floor(this.countdown / 60);
                const seconds = this.countdown % 60;
                return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            },
            
            startResendCooldown(seconds) {
                this.resendCooldown = seconds;
                if (this.resendInterval) { clearInterval(this.resendInterval); }
                this.resendInterval = setInterval(() => {
                    if (this.resendCooldown > 0) { this.resendCooldown--; } else { clearInterval(this.resendInterval); this.resendInterval = null; }
                }, 1000);
            },
            
            destroy() { this.clearCountdown(); if (this.resendInterval) { clearInterval(this.resendInterval); } }
        };
    }
    
    lucide.createIcons();
</script>
