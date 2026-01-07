{{-- Trix Editor with Custom Toolbar --}}
<div class="relative group rounded-2xl overflow-hidden ring-1 ring-surface-200 dark:ring-surface-700 shadow-sm focus-within:shadow-lg focus-within:shadow-theme-500/10 transition-all duration-300 bg-white dark:bg-surface-800/50" :class="injectionDetected ? 'ring-2 ring-rose-400 dark:ring-rose-600' : ''">
    <trix-toolbar id="wysiwyg-toolbar">
        {{-- Mobile Toolbar (Single Row) --}}
        <div class="flex sm:hidden items-center justify-between p-1.5 bg-surface-50 dark:bg-surface-800 border-b border-surface-200 dark:border-surface-700 overflow-x-auto">
            <div class="flex items-center gap-0.5">
                <button type="button" class="trix-button w-7 h-7 flex items-center justify-center rounded text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="bold" title="Bold">
                    <i data-lucide="bold" class="w-3.5 h-3.5"></i>
                </button>
                <button type="button" class="trix-button w-7 h-7 flex items-center justify-center rounded text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="italic" title="Italic">
                    <i data-lucide="italic" class="w-3.5 h-3.5"></i>
                </button>
                <button type="button" class="trix-button w-7 h-7 flex items-center justify-center rounded text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="href" data-trix-action="link" title="Link">
                    <i data-lucide="link" class="w-3.5 h-3.5"></i>
                </button>
                <div class="w-px h-4 bg-surface-300 dark:bg-surface-600 mx-0.5"></div>
                <button type="button" class="trix-button w-7 h-7 flex items-center justify-center rounded text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="heading1" title="Heading">
                    <i data-lucide="heading" class="w-3.5 h-3.5"></i>
                </button>
                <button type="button" class="trix-button w-7 h-7 flex items-center justify-center rounded text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="quote" title="Quote">
                    <i data-lucide="quote" class="w-3.5 h-3.5"></i>
                </button>
                <div class="w-px h-4 bg-surface-300 dark:bg-surface-600 mx-0.5"></div>
                <button type="button" class="trix-button w-7 h-7 flex items-center justify-center rounded text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="bullet" title="Bullets">
                    <i data-lucide="list" class="w-3.5 h-3.5"></i>
                </button>
                <button type="button" class="trix-button w-7 h-7 flex items-center justify-center rounded text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="number" title="Numbers">
                    <i data-lucide="list-ordered" class="w-3.5 h-3.5"></i>
                </button>
            </div>
            <div class="flex items-center gap-0.5 ml-1">
                <button type="button" class="trix-button w-7 h-7 flex items-center justify-center rounded text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors disabled:opacity-30" data-trix-action="undo" title="Undo">
                    <i data-lucide="undo" class="w-3.5 h-3.5"></i>
                </button>
                <button type="button" class="trix-button w-7 h-7 flex items-center justify-center rounded text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors disabled:opacity-30" data-trix-action="redo" title="Redo">
                    <i data-lucide="redo" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        </div>

        {{-- Desktop Toolbar (Grouped) --}}
        <div class="hidden sm:flex flex-wrap gap-2 p-2 bg-surface-50 dark:bg-surface-800 border-b border-surface-200 dark:border-surface-700">
            {{-- Group 1: Text Formatting --}}
            <div class="flex items-center gap-1 p-1 bg-white dark:bg-surface-900 rounded-lg shadow-sm border border-surface-200 dark:border-surface-700">
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="bold" title="Bold">
                    <i data-lucide="bold" class="w-4 h-4"></i>
                </button>
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="italic" title="Italic">
                    <i data-lucide="italic" class="w-4 h-4"></i>
                </button>
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="strike" title="Strike">
                    <i data-lucide="strikethrough" class="w-4 h-4"></i>
                </button>
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="href" data-trix-action="link" title="Link">
                    <i data-lucide="link" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Group 2: Blocks --}}
            <div class="flex items-center gap-1 p-1 bg-white dark:bg-surface-900 rounded-lg shadow-sm border border-surface-200 dark:border-surface-700">
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="heading1" title="Heading">
                    <i data-lucide="heading" class="w-4 h-4"></i>
                </button>
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="quote" title="Quote">
                    <i data-lucide="quote" class="w-4 h-4"></i>
                </button>
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="code" title="Code">
                    <i data-lucide="code" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Group 3: Lists & Indentation --}}
            <div class="flex items-center gap-1 p-1 bg-white dark:bg-surface-900 rounded-lg shadow-sm border border-surface-200 dark:border-surface-700">
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="bullet" title="Bullets">
                    <i data-lucide="list" class="w-4 h-4"></i>
                </button>
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors [&.trix-active]:bg-theme-500 [&.trix-active]:text-white" data-trix-attribute="number" title="Numbers">
                    <i data-lucide="list-ordered" class="w-4 h-4"></i>
                </button>
                <div class="w-px h-4 bg-surface-200 dark:bg-surface-700 mx-1"></div>
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors disabled:opacity-30" data-trix-action="decreaseNestingLevel" title="Decrease Level">
                    <i data-lucide="outdent" class="w-4 h-4"></i>
                </button>
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors disabled:opacity-30" data-trix-action="increaseNestingLevel" title="Increase Level">
                    <i data-lucide="indent" class="w-4 h-4"></i>
                </button>
            </div>
            
            {{-- Group 4: History --}}
            <div class="ml-auto flex items-center gap-1 p-1 bg-white dark:bg-surface-900 rounded-lg shadow-sm border border-surface-200 dark:border-surface-700">
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors disabled:opacity-30 disabled:cursor-not-allowed" data-trix-action="undo" title="Undo">
                    <i data-lucide="undo" class="w-4 h-4"></i>
                </button>
                <button type="button" class="trix-button w-8 h-8 flex items-center justify-center rounded-md text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors disabled:opacity-30 disabled:cursor-not-allowed" data-trix-action="redo" title="Redo">
                    <i data-lucide="redo" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </trix-toolbar>

    <input id="x" type="hidden" name="content" x-model="formData.content">
    <trix-editor 
        toolbar="wysiwyg-toolbar"
        input="x" 
        class="trix-content min-h-[300px] sm:min-h-[500px] bg-white dark:bg-surface-800/50 px-4 sm:px-6 py-4 outline-none border-none dark:text-white prose dark:prose-invert max-w-none"
        style="overflow-y: auto;"
        spellcheck="false"
        autocomplete="off"
        autocorrect="off"
        autocapitalize="off"
        x-on:trix-change="formData.content = $event.target.value; checkContentSafety($event.target.value)"
        x-on:trix-file-accept="$event.preventDefault()" 
    ></trix-editor>
</div>
