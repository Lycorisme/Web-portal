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

    /* Zoom-out handling for professional appearance */
    .zoom-wrapper {
        min-width: 1000px;
        min-height: 100vh;
    }

    .zoom-container {
        transform-origin: center center;
        transition: transform 0.3s ease;
    }

    /* For very large screens / extreme zoom out (larger than 2000px viewport) */
    @media screen and (min-width: 2000px) {
        .zoom-container {
            min-width: 1000px;
            max-width: 1400px;
            min-height: 700px;
            max-height: 900px;
        }
        
        .zoom-wrapper {
            padding: 2rem;
        }
    }

    /* For ultra-wide screens (larger than 2500px viewport) */
    @media screen and (min-width: 2500px) {
        .zoom-container {
            max-width: 1600px;
            min-height: 750px;
            max-height: 950px;
        }
        
        /* Scale up text and elements proportionally */
        .zoom-container h1 {
            font-size: clamp(3.5rem, 5vw, 5rem);
        }
        
        .zoom-container p {
            font-size: clamp(1rem, 1.2vw, 1.25rem);
        }
        
        .zoom-container input {
            font-size: clamp(0.875rem, 1vw, 1rem);
            padding: clamp(0.875rem, 1.2vw, 1.25rem);
        }
        
        .zoom-container button {
            font-size: clamp(0.875rem, 1vw, 1rem);
            padding: clamp(0.75rem, 1vw, 1rem);
        }
    }

    /* For 4K and above screens (larger than 3000px viewport) */
    @media screen and (min-width: 3000px) {
        .zoom-container {
            max-width: 1800px;
            min-height: 850px;
            max-height: 1100px;
            border-radius: 48px;
        }
        
        .zoom-container .rounded-\\[32px\\] {
            border-radius: 48px;
        }
        
        .zoom-wrapper {
            padding: 3rem;
        }
    }

    /* Prevent horizontal scrollbar flicker on standard screens */
    @media screen and (max-width: 1100px) {
        .zoom-wrapper {
            min-width: 100%;
            overflow-x: auto;
        }
    }

    /* Enhanced glass effect for large screens */
    @media screen and (min-width: 1600px) {
        .glass-card {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.05) inset,
                0 50px 100px -20px rgba(0, 0, 0, 0.3);
        }
    }
</style>
