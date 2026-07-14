<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'AR') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@300;400;500;600;700;850&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Fira Sans', sans-serif;
            background-color: #f8fafc;
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
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation Sidebar & Topbar Include -->
        @include('admin.layouts.navigation')

        <!-- Page Content Offset Area -->
        <div class="md:pl-64 pt-16 min-h-screen flex flex-col transition-all duration-300">
            @if (isset($header))
                <header class="bg-white border-b border-gray-100 py-4 px-6">
                    <div class="max-w-7xl mx-auto">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Main Content Canvas -->
            <main class="flex-1 p-4 md:p-8">
                {{ $slot }}
            </main>

            <!-- Page Footer -->
            <footer class="bg-white border-t border-gray-150 py-4 text-center text-xs text-gray-500 font-semibold mt-auto">
                Triumph Management &bull; {{ date('Y-m-d H:i:s T') }}
            </footer>
        </div>
    </div>

    <!-- Custom Confirmation Modal (Root Level) -->
    <div id="delete-confirm-modal" class="fixed -inset-1 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-60 transition-all duration-300" style="backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-150 max-w-md w-full p-6 mx-4 transform scale-95 transition-transform duration-300">
            <div class="flex items-center space-x-3 mb-4">
                <div class="p-3 bg-red-50 text-[#ef3b45] rounded-full border border-red-100">
                    <i class="fas fa-exclamation-triangle text-lg animate-pulse"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Confirm Deletion</h3>
            </div>
            <p id="delete-confirm-message" class="text-sm text-gray-500 mb-6 leading-relaxed">
                Are you sure you want to proceed? This action cannot be undone.
            </p>
            <div class="flex justify-end space-x-3">
                <button id="modal-cancel-btn" type="button" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition focus:outline-none">
                    No, Cancel
                </button>
                <button id="modal-confirm-btn" type="button" class="px-4 py-2.5 bg-[#ef3b45] hover:bg-red-750 text-white text-xs font-bold rounded-xl transition shadow-sm focus:outline-none">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let formToSubmit = null;

        // Auto-delegate individual delete forms across any loaded view
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.classList.contains('delete-user-form')) {
                e.preventDefault();
                formToSubmit = form;
                window.confirmDeleteModal.show('Are you sure you want to delete this user?');
            } else if (form.classList.contains('delete-household-form')) {
                e.preventDefault();
                formToSubmit = form;
                window.confirmDeleteModal.show('Are you sure you want to delete this household record?');
            }
        });

        const modal = document.getElementById('delete-confirm-modal');
        const msgEl = document.getElementById('delete-confirm-message');
        const cancelBtn = document.getElementById('modal-cancel-btn');
        const confirmBtn = document.getElementById('modal-confirm-btn');

        function showDeleteModal(message) {
            if (msgEl) msgEl.textContent = message;
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.offsetHeight; // Force layout paint to handle scale transition
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }
        }

        function hideDeleteModal() {
            if (modal) {
                modal.firstElementChild.classList.remove('scale-100');
                modal.firstElementChild.classList.add('scale-95');
                modal.offsetHeight; // Force layout paint
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    formToSubmit = null;
                }, 150);
            }
        }

        if (cancelBtn) cancelBtn.addEventListener('click', hideDeleteModal);
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
                hideDeleteModal();
            });
        }

        // Global interface for specific page overrides (like bulk deletes)
        window.confirmDeleteModal = {
            show: showDeleteModal,
            hide: hideDeleteModal,
            setForm: function (form) {
                formToSubmit = form;
            }
        };
    });
    </script>
</body>
</html>