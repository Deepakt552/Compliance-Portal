<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AR') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Assets Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: "Montserrat", sans-serif;
        }
        body {
            background-color: #f9fafb;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #0e1e3a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ef3b45;
        }

        /* Toast Keyframe Animations */
        @keyframes slideIn {
            from {
                transform: translate(100%, 20px);
                opacity: 0;
            }
            to {
                transform: translate(0, 0);
                opacity: 1;
            }
        }
        .alert-toast {
            animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .hide-toast-animation {
            transform: translate(100%, 20px) !important;
            opacity: 0 !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation bar -->
        @include('layouts.navigation')

        <!-- Page Heading Header -->
        @if (isset($header))
            <header class="bg-white border-b border-gray-150 shadow-sm py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content Canvas -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Page Footer -->
        <footer class="bg-white border-t border-gray-150 py-4 text-center text-xs text-gray-500 font-semibold mt-auto">
            Triumph Management &bull; {{ date('Y-m-d H:i:s T') }}
        </footer>
    </div>

    <!-- Notification Toast System (Session Triggered) -->
    @if(session('success') || session('error'))
        <div class="alert-toast fixed bottom-6 right-6 z-50 w-full max-w-sm">
            <input type="checkbox" class="hidden" id="toast-closer">
            <label for="toast-closer" 
                   class="close cursor-pointer flex items-center justify-between w-full p-4 rounded-2xl shadow-2xl border transition duration-200 
                          {{ session('error') ? 'bg-red-600 text-white border-red-500 hover:bg-red-700' : 'bg-green-600 text-white border-green-500 hover:bg-green-700' }}">
                <div class="flex items-center space-x-3">
                    <i class="fas {{ session('error') ? 'fa-exclamation-circle' : 'fa-check-circle' }} text-lg"></i>
                    <span class="text-sm font-semibold">{{ session('success') ?? session('error') }}</span>
                </div>
                <i class="fas fa-times text-sm opacity-70 hover:opacity-100"></i>
            </label>
        </div>

        <script>
            // Function to close the toast automatically after 6 seconds
            function autoCloseToast() {
                var toast = document.querySelector('.alert-toast');
                if (toast) {
                    toast.classList.add('hide-toast-animation');
                    setTimeout(function() {
                        toast.style.display = 'none';
                    }, 300);
                }
            }
            setTimeout(autoCloseToast, 6000);
        </script>
    @endif

    @auth
    {{-- ══════════════════════════════════════════════════════════════
         Rejected Documents Premium Popup Notification Modal
    ══════════════════════════════════════════════════════════════ --}}

    {{-- Extra styles for the modal animations --}}
    <style>
        @keyframes modalBackdropIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes modalCardIn {
            from { opacity: 0; transform: scale(0.88) translateY(24px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes modalBackdropOut {
            from { opacity: 1; }
            to   { opacity: 0; }
        }
        @keyframes modalCardOut {
            from { opacity: 1; transform: scale(1) translateY(0); }
            to   { opacity: 0; transform: scale(0.88) translateY(24px); }
        }
        @keyframes warningPulse {
            0%, 100% { transform: scale(1);   box-shadow: 0 0 0 0 rgba(239,59,69,0.4); }
            50%       { transform: scale(1.08); box-shadow: 0 0 0 10px rgba(239,59,69,0); }
        }
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%     { transform: translateX(-5px); }
            40%     { transform: translateX(5px); }
            60%     { transform: translateX(-3px); }
            80%     { transform: translateX(3px); }
        }
        #rejectionModal.modal-show {
            animation: modalBackdropIn 0.25s ease forwards;
        }
        #rejectionModal.modal-show .rejection-card {
            animation: modalCardIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        #rejectionModal.modal-hide {
            animation: modalBackdropOut 0.2s ease forwards;
        }
        #rejectionModal.modal-hide .rejection-card {
            animation: modalCardOut 0.2s ease forwards;
        }
        .rejection-warning-icon {
            animation: warningPulse 2s ease-in-out infinite;
        }
        .rejection-doc-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .rejection-doc-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(239,59,69,0.12);
        }
        #rejectionModal::-webkit-scrollbar { display: none; }
        #rejectionList::-webkit-scrollbar { width: 4px; }
        #rejectionList::-webkit-scrollbar-thumb { background: #fca5a5; border-radius: 4px; }
    </style>

    <div id="rejectionModal"
         class="fixed inset-0 z-[9999] items-center justify-center bg-black/70"
         style="display:none; backdrop-filter: blur(6px);">

        <div class="rejection-card relative w-full max-w-lg mx-4 rounded-3xl overflow-hidden shadow-2xl"
             style="background: #ffffff;">

            {{-- ── Decorative top gradient header ─────────────────────────── --}}
            <div class="relative overflow-hidden px-7 pt-7 pb-6"
                 style="background: linear-gradient(135deg, #1a0a0b 0%, #7b1520 45%, #ef3b45 100%);">

                {{-- Decorative blur orbs --}}
                <div class="absolute -top-8 -right-8 h-32 w-32 rounded-full opacity-20"
                     style="background: radial-gradient(circle, #ff6b6b, transparent); filter: blur(20px);"></div>
                <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full opacity-15"
                     style="background: radial-gradient(circle, #ffd700, transparent); filter: blur(15px);"></div>

                <div class="relative flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        {{-- Animated warning icon --}}
                        <div class="rejection-warning-icon h-14 w-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                             style="background: rgba(255,255,255,0.15); border: 1.5px solid rgba(255,255,255,0.25);">
                            <i class="fas fa-shield-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-red-200">Action Required</span>
                                <span id="rejectionCountBadge"
                                      class="text-[10px] font-extrabold bg-white text-red-600 px-2 py-0.5 rounded-full leading-none">0</span>
                            </div>
                            <h2 class="text-white font-extrabold text-xl leading-tight tracking-tight">Documents Rejected</h2>
                            <p class="text-red-200 text-xs mt-1 leading-relaxed">
                                The following documents require your attention. <br>Please re-upload them to continue.
                            </p>
                        </div>
                    </div>

                    {{-- Close button --}}
                    <button id="rejectionCloseBtn"
                            onclick="closeRejectionModal()"
                            class="flex-shrink-0 ml-3 h-9 w-9 rounded-xl flex items-center justify-center transition-all duration-150 text-white"
                            style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);"
                            onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                {{-- Bottom wave divider --}}
                <div class="absolute bottom-0 left-0 right-0 h-3 overflow-hidden">
                    <svg viewBox="0 0 400 12" preserveAspectRatio="none" class="w-full h-full">
                        <path d="M0,0 C100,12 300,0 400,8 L400,12 L0,12 Z" fill="white"/>
                    </svg>
                </div>
            </div>

            {{-- ── Document list body ──────────────────────────────────────── --}}
            <div class="px-5 py-4 max-h-64 overflow-y-auto space-y-2.5" id="rejectionList"></div>

            {{-- ── Footer actions ──────────────────────────────────────────── --}}
            <div class="px-5 pb-5 pt-2">
                {{-- Divider --}}
                <div class="border-t border-gray-100 mb-4"></div>

                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Primary CTA --}}
                    <a href="{{ route('document.status') }}#rejected"
                       class="flex-1 flex items-center justify-center space-x-2 text-white font-bold text-sm px-5 py-3 rounded-2xl transition-all duration-200 shadow-lg"
                       style="background: linear-gradient(135deg, #ef3b45, #c0272d); box-shadow: 0 4px 15px rgba(239,59,69,0.35);"
                       onmouseover="this.style.boxShadow='0 6px 20px rgba(239,59,69,0.5)'; this.style.transform='translateY(-1px)'"
                       onmouseout="this.style.boxShadow='0 4px 15px rgba(239,59,69,0.35)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-arrow-up-from-bracket text-sm"></i>
                        <span>Re-Upload Documents</span>
                    </a>

                    {{-- Dismiss --}}
                    <button onclick="closeRejectionModal()"
                            class="flex-shrink-0 flex items-center justify-center space-x-2 text-gray-500 hover:text-gray-700 font-semibold text-sm px-5 py-3 rounded-2xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all duration-150">
                        <i class="fas fa-clock text-xs"></i>
                        <span>Remind Me Later</span>
                    </button>
                </div>

                <p class="text-center text-[10px] text-gray-400 mt-3">
                    <i class="fas fa-bell mr-1"></i>
                    You'll be reminded again in 5 minutes if documents remain rejected.
                </p>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const STORAGE_KEY      = 'rejection_last_seen_count';
        const POLL_INTERVAL_MS = 5 * 60 * 1000; // 5 minutes

        // ── Build document card list ────────────────────────────────────────
        function buildRejectionList(documents) {
            const list = document.getElementById('rejectionList');
            const badge = document.getElementById('rejectionCountBadge');
            if (badge) badge.textContent = documents.length;
            list.innerHTML = '';

            const colors = ['#ef3b45','#e05555','#c0272d','#d94040'];

            documents.forEach(function(doc, idx) {
                const accentColor = colors[idx % colors.length];
                const initials = doc.member.split(' ').map(n => n[0]).join('').toUpperCase().slice(0,2);

                const item = document.createElement('div');
                item.className = 'rejection-doc-card rounded-2xl border border-red-100 overflow-hidden';
                item.style.background = '#fff9f9';
                item.innerHTML = `
                    <div style="display:flex; align-items:stretch;">
                        <div style="width:4px; background:${accentColor}; flex-shrink:0; border-radius:0;"></div>
                        <div style="padding:12px 14px; flex:1; min-width:0;">
                            <div style="display:flex; align-items:flex-start; gap:10px;">
                                <div style="height:32px; width:32px; border-radius:50%; background:${accentColor}; color:white; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:11px; flex-shrink:0;">
                                    ${initials}
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <p style="font-size:11px; font-weight:700; color:#1a1a2e; margin:0 0 2px; line-height:1.3;">${doc.document}</p>
                                    <p style="font-size:10px; color:#5c5fef; font-weight:600; margin:0 0 6px;">${doc.member}</p>
                                    ${doc.comments ? `
                                    <div style="background:#fff0f0; border:1px solid #fecaca; border-radius:10px; padding:6px 10px; margin-bottom:6px;">
                                        <p style="font-size:10px; color:#b91c1c; margin:0; line-height:1.5;">
                                            <i class="fas fa-quote-left" style="font-size:8px; margin-right:4px; opacity:0.7;"></i>
                                            ${doc.comments}
                                            <i class="fas fa-quote-right" style="font-size:8px; margin-left:4px; opacity:0.7;"></i>
                                        </p>
                                    </div>` : ''}
                                    <p style="font-size:9px; color:#9ca3af; margin:0;">
                                        <i class="far fa-clock" style="margin-right:3px;"></i>${doc.updated_at}
                                    </p>
                                </div>
                                <div style="flex-shrink:0;">
                                    <span style="display:inline-flex; align-items:center; background:#fee2e2; color:#ef3b45; font-size:9px; font-weight:700; padding:3px 8px; border-radius:20px; border:1px solid #fca5a5; text-transform:uppercase; letter-spacing:0.05em;">
                                        <i class="fas fa-times-circle" style="margin-right:4px; font-size:8px;"></i>Rejected
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                list.appendChild(item);
            });
        }

        // ── Show modal with animation ────────────────────────────────────────
        function showRejectionModal(documents) {
            buildRejectionList(documents);
            const modal = document.getElementById('rejectionModal');
            modal.classList.remove('modal-hide');
            modal.style.display = 'flex';
            // Force reflow then add show class
            void modal.offsetWidth;
            modal.classList.add('modal-show');
        }

        // ── Close with animation ─────────────────────────────────────────────
        window.closeRejectionModal = function() {
            const modal = document.getElementById('rejectionModal');
            modal.classList.remove('modal-show');
            modal.classList.add('modal-hide');
            setTimeout(function() {
                modal.style.display = 'none';
                modal.classList.remove('modal-hide');
            }, 220);
            const count = document.getElementById('rejectionList').children.length;
            sessionStorage.setItem(STORAGE_KEY, count);
        };

        // ── Poll server for rejected docs ────────────────────────────────────
        function checkRejections() {
            fetch('{{ route("user.rejected.documents") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.count > 0) {
                    const lastSeen = parseInt(sessionStorage.getItem(STORAGE_KEY) || '0', 10);
                    if (data.count > lastSeen) {
                        showRejectionModal(data.documents);
                    }
                }
            })
            .catch(function() { /* silent fail */ });
        }

        // Fire on page load + every 5 minutes
        checkRejections();
        setInterval(checkRejections, POLL_INTERVAL_MS);
    })();
    </script>
    @endauth
</body>
</html>