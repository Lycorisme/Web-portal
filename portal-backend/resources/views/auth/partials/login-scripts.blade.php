{{-- Login Page Scripts --}}
<script>
    function authPanel() {
        return {
            mode: 'login', // login, register, reset, verify
            resetStep: 1,
            resetEmail: '',
            maskedEmail: '',
            otpDigits: ['', '', '', '', '', ''],
            resetToken: '',
            newPassword: '',
            confirmPassword: '',
            showPassword: false,
            
            // Register Data
            registerName: '',
            registerEmail: '',
            registerPassword: '',
            registerPasswordConfirmation: '',
            showRegisterPassword: false,
            agreed: false,
            
            // Verify Data
            verifyEmail: '',
            verifyOtpDigits: ['', '', '', '', '', ''],
            verifyCountdown: 0,
            verifyCountdownInterval: null,
            
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

            get registerPasswordStrength() {
                const password = this.registerPassword;
                if (!password) return 0;
                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^a-zA-Z0-9]/.test(password)) strength++;
                return strength;
            },

            get passwordStrengthText() {
                const s = this.registerPasswordStrength;
                if (s === 0) return '';
                if (s === 1) return 'Lemah';
                if (s === 2) return 'Cukup';
                if (s === 3) return 'Baik';
                return 'Sangat Kuat';
            },
            
            switchMode(newMode) {
                this.errorMessage = '';
                this.successMessage = '';
                
                // Clear state on switch unless specifically needed
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

            // Registration AJAX
            async submitRegister() {
                if (!this.agreed) return;
                this.isLoading = true;
                this.errorMessage = '';
                this.successMessage = '';

                try {
                    const response = await fetch('{{ route("register") }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ 
                            name: this.registerName,
                            email: this.registerEmail,
                            password: this.registerPassword,
                            password_confirmation: this.registerPasswordConfirmation
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        // Success -> Move to verify tab
                        this.verifyEmail = data.email;
                        this.switchMode('verify');
                        this.successMessage = data.message;
                        this.startVerifyCountdown(60);
                    } else {
                        // Laravel validation errors format or custom json
                        if (data.errors) {
                            // Join first error of each field
                            this.errorMessage = Object.values(data.errors).flat()[0];
                        } else {
                            this.errorMessage = data.message || 'Terjadi kesalahan saat registrasi.';
                        }
                    }
                } catch (error) {
                    this.errorMessage = 'Terjadi kesalahan jaringan atau server.';
                    console.error('Register error:', error);
                } finally {
                    this.isLoading = false;
                }
            },
            
            // Verify Logic
            handleVerifyOtpInput(event, index) {
                const value = event.target.value;
                if (!/^\d*$/.test(value)) { this.verifyOtpDigits[index] = ''; return; }
                if (value && index < 5) { document.getElementById('verify-otp-' + (index + 1))?.focus(); }
            },
            
            handleVerifyOtpBackspace(event, index) {
                if (!this.verifyOtpDigits[index] && index > 0) { document.getElementById('verify-otp-' + (index - 1))?.focus(); }
            },
            
            handleVerifyOtpPaste(event) {
                event.preventDefault();
                const pastedData = event.clipboardData.getData('text').trim();
                if (/^\d{6}$/.test(pastedData)) {
                    for (let i = 0; i < 6; i++) { this.verifyOtpDigits[i] = pastedData[i]; }
                    document.getElementById('verify-otp-5')?.focus();
                }
            },

            get formatVerifyCountdown() {
                const minutes = Math.floor(this.verifyCountdown / 60);
                const seconds = this.verifyCountdown % 60;
                return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            },

            startVerifyCountdown(seconds) {
                this.verifyCountdown = seconds;
                if (this.verifyCountdownInterval) clearInterval(this.verifyCountdownInterval);
                this.verifyCountdownInterval = setInterval(() => {
                    if (this.verifyCountdown > 0) this.verifyCountdown--;
                    else clearInterval(this.verifyCountdownInterval);
                }, 1000);
            },

            async submitVerify() {
                const otp = this.verifyOtpDigits.join('');
                if (otp.length !== 6) { this.errorMessage = 'Masukkan kode OTP 6 digit.'; return; }
                
                this.isLoading = true;
                this.errorMessage = '';
                
                try { // Using existing route logic which redirects, we might need to change it or handle redirect manually?
                      // Wait, auth controller verifyEmail returns redirect.
                      // If we fetch it, we get the redirected page content.
                      // Ideally we should update verifyEmail to return JSON too OR standard form submit.
                      // User said "tanpa refresh" for swapping tabs. But final login redirect is fine to be a comprehensive load.
                      // Let's try standard submit via JS construction to allow true redirect handling by browser if successful
                      
                      const form = document.createElement('form');
                      form.method = 'POST';
                      form.action = '{{ route("verification.verify") }}';
                      
                      const csrf = document.createElement('input');
                      csrf.type = 'hidden';
                      csrf.name = '_token';
                      csrf.value = '{{ csrf_token() }}';
                      form.appendChild(csrf);
                      
                      const emailInput = document.createElement('input');
                      emailInput.type = 'hidden';
                      emailInput.name = 'email';
                      emailInput.value = this.verifyEmail;
                      form.appendChild(emailInput);
                      
                      const otpInput = document.createElement('input');
                      otpInput.type = 'hidden';
                      otpInput.name = 'otp';
                      otpInput.value = otp;
                      form.appendChild(otpInput);
                      
                      document.body.appendChild(form);
                      form.submit();
                      
                } catch (e) {
                    this.isLoading = false;
                }
            },

            async resendVerifyOtp() {
                this.isLoading = true;
                try {
                     const response = await fetch('{{ route("verification.resend") }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email: this.verifyEmail })
                    });
                    
                    if (response.ok) {
                        this.successMessage = 'Kode baru telah dikirim.';
                        this.startVerifyCountdown(60);
                    } else {
                        this.errorMessage = 'Gagal mengirim ulang kode.';
                    }
                } catch(e) {
                    this.errorMessage = 'Terjadi kesalahan.';
                } finally {
                    this.isLoading = false;
                }
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
