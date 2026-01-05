<script>
    // Store original form values for reset functionality
    const originalFormData = {};
    const form = document.getElementById('settingsForm');
    if (form) {
        const formData = new FormData(form);
        for (let [key, value] of formData.entries()) {
            if (!(value instanceof File)) {
                originalFormData[key] = value;
            }
        }
    }
    
    // Store original saved theme
    const originalSavedTheme = '{{ $rawSettings['current_theme'] ?? 'indigo' }}';

    // Preview file upload
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = input.previousElementSibling;
                    container.innerHTML = `<img src="${e.target.result}" alt="Preview" class="max-h-full max-w-full object-contain p-4">`;
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Update theme preview in real-time when selection changes
    document.querySelectorAll('input[name="theme_preset"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedTheme = this.value;
            document.documentElement.setAttribute('data-theme', selectedTheme);
            // Also update the hidden input
            const hiddenInput = document.querySelector('input[name="current_theme"]');
            if (hiddenInput) {
                hiddenInput.value = selectedTheme;
            }
        });
    });

    // Function to update dynamic elements on the page
    function updateDynamicElements(settings) {
        // Update sidebar site name
        const sidebarSiteName = document.getElementById('sidebar-site-name');
        if (sidebarSiteName && settings.site_name) {
            sidebarSiteName.textContent = settings.site_name;
        }
        
        // Update sidebar tagline
        const sidebarTagline = document.getElementById('sidebar-site-tagline');
        if (sidebarTagline && settings.site_tagline !== undefined) {
            sidebarTagline.textContent = settings.site_tagline || '';
        }
        
        // Update sidebar footer name
        const sidebarFooterName = document.getElementById('sidebar-footer-name');
        if (sidebarFooterName && settings.site_name) {
            sidebarFooterName.textContent = settings.site_name;
        }
        
        // Update sidebar logo initial (if using initial letter)
        const sidebarLogoInitial = document.getElementById('sidebar-logo-initial');
        if (sidebarLogoInitial && settings.site_name) {
            sidebarLogoInitial.textContent = settings.site_name.charAt(0).toUpperCase();
        }
        
        // Update sidebar logo image
        const sidebarLogo = document.getElementById('sidebar-logo');
        if (sidebarLogo) {
            if (settings.logo_url) {
                // Replace with image logo
                sidebarLogo.innerHTML = `<img src="${settings.logo_url}" alt="${settings.site_name || 'Logo'}" class="w-full h-full object-cover">`;
                sidebarLogo.classList.remove('bg-theme-gradient');
                sidebarLogo.classList.add('overflow-hidden');
            } else if (settings.site_name) {
                // Revert to initials if no logo (though current UI doesn't allow removing logo easily, this handles the case)
                sidebarLogo.innerHTML = `<span id="sidebar-logo-initial" class="text-white font-space font-bold text-lg">${settings.site_name.charAt(0).toUpperCase()}</span>`;
                sidebarLogo.classList.add('bg-theme-gradient');
                sidebarLogo.classList.remove('overflow-hidden');
            }
        }
        
        // Update Favicon
        const faviconLink = document.getElementById('dynamic-favicon');
        if (faviconLink && settings.favicon_url) {
            faviconLink.href = settings.favicon_url;
        }
        
        // Update footer site name
        const footerSiteName = document.getElementById('footer-site-name');
        if (footerSiteName && settings.site_name) {
            footerSiteName.textContent = settings.site_name;
        }
        
        // Update footer email
        const footerEmail = document.getElementById('footer-email');
        if (footerEmail && settings.site_email !== undefined) {
            footerEmail.textContent = settings.site_email || '';
            footerEmail.href = settings.site_email ? `mailto:${settings.site_email}` : '#';
        }
        
        // Update theme
        if (settings.current_theme) {
            localStorage.setItem('themePreset', settings.current_theme);
            document.documentElement.setAttribute('data-theme', settings.current_theme);
        }
        
        // Update page title if site_name changed
        if (settings.site_name) {
            const pageTitle = document.querySelector('title');
            if (pageTitle) {
                const currentTitle = pageTitle.textContent;
                const parts = currentTitle.split(' - ');
                if (parts.length > 1) {
                    parts[parts.length - 1] = settings.site_name;
                    pageTitle.textContent = parts.join(' - ');
                }
            }
        }
    }

    // Save button with confirmation and AJAX submission
    document.getElementById('saveBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Simpan Pengaturan?',
            text: 'Pengaturan yang diubah akan diterapkan ke seluruh portal.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Get form data
                const form = document.getElementById('settingsForm');
                const formData = new FormData(form);
                
                // Sync theme to localStorage before submit
                const themeInput = document.querySelector('input[name="current_theme"]');
                if (themeInput) {
                    const selectedTheme = themeInput.value;
                    localStorage.setItem('themePreset', selectedTheme);
                    document.documentElement.setAttribute('data-theme', selectedTheme);
                }

                // Send AJAX request
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update dynamic elements on the page
                        if (data.settings) {
                            updateDynamicElements(data.settings);
                            
                            // Update original form data for reset functionality
                            for (let key in data.settings) {
                                if (typeof data.settings[key] !== 'object') {
                                    originalFormData[key] = data.settings[key];
                                }
                            }
                        }
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Pengaturan berhasil disimpan!',
                            confirmButtonText: 'OK'
                        });
                        
                        // Reinitialize Lucide icons
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menyimpan pengaturan.',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    });

    // Reset button with confirmation
    document.getElementById('resetBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Reset Pengaturan?',
            text: 'Form akan dikembalikan ke nilai terakhir yang tersimpan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f59e0b',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Reset form values to original saved values
                const form = document.getElementById('settingsForm');
                
                // Reset text inputs and textareas
                for (let key in originalFormData) {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = originalFormData[key] === 'on' || originalFormData[key] === true || originalFormData[key] === '1';
                        } else if (input.type === 'radio') {
                            const radios = form.querySelectorAll(`[name="${key}"]`);
                            radios.forEach(radio => {
                                radio.checked = radio.value === originalFormData[key];
                            });
                        } else {
                            input.value = originalFormData[key];
                        }
                    }
                }
                
                // Reset file input previews to original images
                document.querySelectorAll('input[type="file"]').forEach(input => {
                    const fieldName = input.name;
                    const currentValueInput = form.querySelector(`[name="${fieldName}_current"]`);
                    const container = input.previousElementSibling;
                    
                    if (currentValueInput && currentValueInput.value) {
                        container.innerHTML = `<img src="${currentValueInput.value}" alt="Preview" class="max-h-full max-w-full object-contain p-4">`;
                    }
                });
                
                // Reset theme preview to saved theme
                const savedTheme = originalFormData['current_theme'] || originalSavedTheme;
                document.documentElement.setAttribute('data-theme', savedTheme);
                
                // Update hidden input
                const hiddenInput = document.querySelector('input[name="current_theme"]');
                if (hiddenInput) {
                    hiddenInput.value = savedTheme;
                }
                
                // Update theme preset radio buttons
                const themeRadios = document.querySelectorAll('input[name="theme_preset"]');
                themeRadios.forEach(radio => {
                    radio.checked = radio.value === savedTheme;
                });
                
                // Re-initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                
                // Show toast notification
                showToast('success', 'Form telah direset ke nilai terakhir tersimpan');
            }
        });
    });
</script>
