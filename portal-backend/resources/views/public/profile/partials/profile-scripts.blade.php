<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function profilePage() {
    return {
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'settings',
        loading: false,
        
        async submitForm(formId, url) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            this.loading = true;
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#10b981',
                        customClass: {
                            popup: 'rounded-2xl border border-white/10'
                        }
                    });
                    if (data.user) {
                        document.getElementById('header-name').textContent = data.user.name;
                        document.getElementById('header-email').textContent = data.user.email;
                    }
                    if (data.photo_url) {
                        const img = document.getElementById('header-avatar');
                        if(img) img.src = data.photo_url;
                        const preview = document.getElementById('preview-avatar');
                        if(preview) preview.src = data.photo_url;
                    }
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#ef4444',
                        customClass: {
                            popup: 'rounded-2xl border border-white/10'
                        }
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    background: '#0f172a',
                    color: '#f8fafc',
                    confirmButtonColor: '#ef4444',
                    customClass: {
                        popup: 'rounded-2xl border border-white/10'
                    }
                });
            }
            
            this.loading = false;
        },
        
        confirmDelete() {
            Swal.fire({
                title: 'Hapus Akun?',
                html: `
                    <p class="text-slate-400 text-sm mb-6">Seluruh data Anda akan dihapus permanen. Ketik <strong class="text-rose-400">HAPUS AKUN</strong> untuk konfirmasi.</p>
                    <input type="text" id="delete-confirm" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 transition-colors text-center font-bold tracking-widest uppercase mb-3" placeholder="HAPUS AKUN">
                    <input type="password" id="delete-password" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 transition-colors text-center" placeholder="Password Anda">
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#f8fafc',
                customClass: {
                    popup: 'rounded-2xl border border-rose-500/20'
                },
                preConfirm: () => {
                    return {
                        confirmation: document.getElementById('delete-confirm').value,
                        password: document.getElementById('delete-password').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteAccount(result.value);
                }
            });
        },
        
        async deleteAccount(data) {
            try {
                const response = await fetch('{{ route("public.profile.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Akun Terhapus',
                        text: result.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan sistem',
                    background: '#0f172a',
                    color: '#f8fafc',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
    };
}
</script>
