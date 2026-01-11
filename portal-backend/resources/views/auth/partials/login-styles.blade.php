{{-- Login Page Styles --}}
<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #020617;
        color: #ffffff;
    }

    .glass-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .modern-input {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(148, 163, 184, 0.1);
        transition: all 0.3s ease;
        color: white;
    }
    .modern-input:focus {
        background: rgba(30, 41, 59, 0.8);
        border-color: #2dd4bf;
        box-shadow: 0 0 0 4px rgba(45, 212, 191, 0.1);
        outline: none;
    }
    .modern-input::placeholder {
        color: #64748b;
    }
    
    .modern-input:-webkit-autofill,
    .modern-input:-webkit-autofill:hover, 
    .modern-input:-webkit-autofill:focus, 
    .modern-input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #1e293b inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    [x-cloak] { display: none !important; }
</style>
