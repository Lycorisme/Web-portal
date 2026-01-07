{{-- Form Modal Logic Module --}}

// Form Modal Logic
openCreateModal() {
    this.formMode = 'create';
    this.formData = {
        id: null,
        title: '',
        slug: '',
        excerpt: '',
        content: '',
        thumbnail: null,
        thumbnail_url: '',
        category_id: '',
        read_time: null,
        status: 'draft',
        meta_title: '',
        meta_description: '',
        meta_keywords: '',
        published_at: null,
        is_pinned: false,
        is_headline: false,
    };
    this.activeTab = 'content';
    this.injectionDetected = false;
    this.detectedThreats = [];
    this.auditInfo = null;
    this.formErrors = {};
    this.showFormModal = true;
    
    // Clear Trix Editor content for new article
    this.$nextTick(() => {
        lucide.createIcons();
        
        // Reset Trix Editor to empty
        setTimeout(() => {
            const trixEditor = document.querySelector('trix-editor');
            if (trixEditor && trixEditor.editor) {
                trixEditor.editor.loadHTML('');
            }
        }, 100);
    });
},

openEditModal(article) {
    this.formMode = 'edit';
    
    // Format published_at for datetime-local input (remove timezone info)
    let formattedPublishedAt = null;
    if (article.published_at) {
        // Convert to local datetime format (YYYY-MM-DDTHH:mm)
        const date = new Date(article.published_at);
        formattedPublishedAt = date.toISOString().slice(0, 16);
    }
    
    this.formData = {
        id: article.id,
        title: article.title || '',
        slug: article.slug || '',
        excerpt: article.excerpt || '',
        content: article.content || '',
        thumbnail: null, // Reset file input
        thumbnail_url: article.thumbnail || '', // Existing URL
        category_id: article.category_id ? String(article.category_id) : '', // Ensure string for select
        read_time: article.read_time || null,
        status: article.status || 'draft',
        meta_title: article.meta_title || '',
        meta_description: article.meta_description || '',
        meta_keywords: article.meta_keywords || '',
        published_at: formattedPublishedAt,
        is_pinned: article.is_pinned || false,
        is_headline: article.is_headline || false,
    };
    this.activeTab = 'content';
    this.auditInfo = {
        created_by: article.author_name || 'Admin',
        created_by_avatar: article.author_avatar,
        created_at: article.created_at || null,
        updated_by: article.author_name || 'Admin', // Same author for now, ideally track last editor
        updated_at: article.updated_at || null
    };
    this.detectedThreats = [];
    this.injectionDetected = false;
    this.formErrors = {};
    this.showFormModal = true;
    this.closeMenu();
    
    // Wait for modal to render, then load content into Trix Editor
    this.$nextTick(() => {
        lucide.createIcons();
        
        // Load existing content into Trix Editor
        setTimeout(() => {
            const trixEditor = document.querySelector('trix-editor');
            if (trixEditor && trixEditor.editor && this.formData.content) {
                trixEditor.editor.loadHTML(this.formData.content);
                // Check content safety after loading
                this.checkContentSafety(this.formData.content);
            }
        }, 100);
    });
},

closeFormModal() {
    this.showFormModal = false;
    this.formData = {
        id: null,
        title: '',
        slug: '',
        excerpt: '',
        content: '',
        thumbnail: null,
        thumbnail_url: '',
        category_id: '',
        read_time: null,
        status: 'draft',
        meta_title: '',
        meta_description: '',
        meta_keywords: '',
        published_at: null,
        is_pinned: false,
        is_headline: false,
    };
    this.activeTab = 'content';
    this.auditInfo = null;
    this.formErrors = {};
},

async submitForm() {
    this.formLoading = true;
    this.formErrors = {};

    // Client-side validation first
    const validationErrors = this.validateForm();
    if (Object.keys(validationErrors).length > 0) {
        this.formErrors = validationErrors;
        this.formLoading = false;
        
        // Show specific error message
        const errorFields = Object.keys(validationErrors);
        const fieldNames = {
            title: 'Judul',
            content: 'Konten',
            category_id: 'Kategori',
            status: 'Status'
        };
        const errorFieldName = fieldNames[errorFields[0]] || errorFields[0];
        showToast('error', `${errorFieldName} wajib diisi!`);
        
        // Navigate to the tab containing the first error
        if (['title', 'content', 'excerpt'].includes(errorFields[0])) {
            this.activeTab = 'content';
        } else if (['thumbnail'].includes(errorFields[0])) {
            this.activeTab = 'media';
        } else if (['meta_title', 'meta_description', 'meta_keywords'].includes(errorFields[0])) {
            this.activeTab = 'seo';
        } else if (['category_id', 'status'].includes(errorFields[0])) {
            this.activeTab = 'settings';
        }
        
        return;
    }

    try {
        const url = this.formMode === 'create' 
            ? '{{ route("articles.store") }}'
            : `/articles/${this.formData.id}`;
        
        const method = this.formMode === 'create' ? 'POST' : 'PUT';

        const formDataStart = new FormData();
        
        // Append all fields
        formDataStart.append('title', this.formData.title);
        if (this.formData.slug) formDataStart.append('slug', this.formData.slug);
        if (this.formData.excerpt) formDataStart.append('excerpt', this.formData.excerpt);
        if (this.formData.content) formDataStart.append('content', this.formData.content);
        if (this.formData.category_id) formDataStart.append('category_id', this.formData.category_id);
        if (this.formData.read_time) formDataStart.append('read_time', this.formData.read_time);
        formDataStart.append('status', this.formData.status);
        if (this.formData.meta_title) formDataStart.append('meta_title', this.formData.meta_title);
        if (this.formData.meta_description) formDataStart.append('meta_description', this.formData.meta_description);
        if (this.formData.meta_keywords) formDataStart.append('meta_keywords', this.formData.meta_keywords);
        if (this.formData.published_at) formDataStart.append('published_at', this.formData.published_at);
        formDataStart.append('is_pinned', this.formData.is_pinned ? 1 : 0);
        formDataStart.append('is_headline', this.formData.is_headline ? 1 : 0);

        // Handle Thumbnail File
        if (this.formData.thumbnail instanceof File) {
            formDataStart.append('thumbnail', this.formData.thumbnail);
        }

        // Method spoofing for PUT since FormData sends as multipart/form-data
        if (this.formMode === 'edit') {
            formDataStart.append('_method', 'PUT');
        }

        const response = await fetch(url, {
            method: 'POST', // Always POST for FormData with binary (even for updates, using _method)
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
             },
            body: formDataStart,
        });

        const result = await response.json();

        if (response.ok && result.success) {
            this.closeFormModal();
            this.fetchArticles();
            showToast('success', result.message);
        } else if (response.status === 422) {
            // Validation errors from server
            this.formErrors = result.errors || {};
            
            // Show detailed error message
            const errorKeys = Object.keys(this.formErrors);
            if (errorKeys.length > 0) {
                const firstError = this.formErrors[errorKeys[0]];
                const errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                showToast('error', errorMessage);
            } else {
                showToast('error', 'Mohon periksa kembali data yang diinput.');
            }
        } else {
            showToast('error', result.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showToast('error', 'Gagal menyimpan berita. Periksa koneksi internet Anda.');
    } finally {
        this.formLoading = false;
    }
},

// Client-side form validation
validateForm() {
    const errors = {};
    
    // Title is required
    if (!this.formData.title || this.formData.title.trim() === '') {
        errors.title = ['Judul berita wajib diisi'];
    } else if (this.formData.title.length < 3) {
        errors.title = ['Judul minimal 3 karakter'];
    } else if (this.formData.title.length > 255) {
        errors.title = ['Judul maksimal 255 karakter'];
    }
    
    // Content is optional but check if it has dangerous content
    if (this.injectionDetected) {
        errors.content = ['Konten mengandung karakter berbahaya. Silakan bersihkan terlebih dahulu.'];
    }
    
    // Category is optional
    // Status is required (has default)
    if (!this.formData.status) {
        errors.status = ['Status wajib dipilih'];
    }
    
    return errors;
},

// Get validation status for a field
getFieldStatus(fieldName) {
    if (this.formErrors[fieldName]) {
        return 'error';
    }
    // Check if field has valid content
    const value = this.formData[fieldName];
    if (value && value.toString().trim().length > 0) {
        return 'valid';
    }
    return 'empty';
},

// Generate slug from title
generateSlug() {
    if (this.formData.title) {
        this.formData.slug = this.formData.title
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
},
