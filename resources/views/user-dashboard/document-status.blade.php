<x-app-layout>
    <div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-6xl mx-auto space-y-6">
            
            <!-- Header Banner -->
            <div class="rounded-2xl bg-[#0e1e3a] text-white p-6 md:p-8 shadow-sm border border-gray-800 flex justify-between items-center">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#ef3b45] font-bold">Document Hub</span>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-1">Verification Status</h1>
                    <p class="text-blue-200 text-xs mt-1">Review the status of your uploaded verification documents.</p>
                </div>
                <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-gray-300">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
            </div>

            <!-- Main Tab Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
                <!-- Navigation Tabs -->
                <div class="bg-gray-50 border-b border-gray-150 px-6 pt-4">
                    <ul class="flex space-x-2 -mb-px">
                        <li>
                            <a id="pending-tab" href="#pending"
                               class="tab-link inline-flex items-center px-4 py-3 border-b-2 text-sm font-bold transition-all duration-200 hover:text-[#ef3b45] focus:outline-none 
                                      {{ $activeTab === 'pending' ? 'border-[#ef3b45] text-[#0e1e3a]' : 'border-transparent text-gray-400' }}">
                                <i class="fas fa-clock mr-2 text-xs"></i> Pending Documents
                            </a>
                        </li>
                        <li>
                            <a id="approved-tab" href="#approved"
                               class="tab-link inline-flex items-center px-4 py-3 border-b-2 text-sm font-bold transition-all duration-200 hover:text-[#ef3b45] focus:outline-none 
                                      {{ $activeTab === 'approved' ? 'border-[#ef3b45] text-[#0e1e3a]' : 'border-transparent text-gray-400' }}">
                                <i class="fas fa-check-circle mr-2 text-xs text-green-500"></i> Approved
                            </a>
                        </li>
                        <li>
                            <a id="rejected-tab" href="#rejected"
                               class="tab-link inline-flex items-center px-4 py-3 border-b-2 text-sm font-bold transition-all duration-200 hover:text-[#ef3b45] focus:outline-none 
                                      {{ $activeTab === 'rejected' ? 'border-[#ef3b45] text-[#0e1e3a]' : 'border-transparent text-gray-400' }}">
                                <i class="fas fa-times-circle mr-2 text-xs text-red-500"></i> Rejected
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Panels Contents -->
                <div class="p-6 bg-white">
                    <div id="pending" class="tab-panel {{ $activeTab === 'pending' ? '' : 'hidden' }}">
                        @include('user-dashboard.partials.document-status-tab', ['status' => 'pending'])
                    </div>
                    <div id="approved" class="tab-panel {{ $activeTab === 'approved' ? '' : 'hidden' }}">
                        @include('user-dashboard.partials.document-status-tab', ['status' => 'verified'])
                    </div>
                    <div id="rejected" class="tab-panel {{ $activeTab === 'rejected' ? '' : 'hidden' }}">
                        @include('user-dashboard.partials.document-status-tab', ['status' => 'rejected'])
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic JS Tab Controller -->
        <script>
        // Check window location hash to activate correct tab initially
        var hash = window.location.hash.substr(1);
        var activeTab = hash ? hash : 'pending';

        // Set verified string matching backend logic variable if approved hash is checked
        var tabId = activeTab;

        // Hide all initially and show current selected tab
        document.querySelectorAll('.tab-panel').forEach(function(panel) {
            panel.classList.add('hidden');
        });
        
        var currentPanel = document.getElementById(tabId);
        if (currentPanel) {
            currentPanel.classList.remove('hidden');
        }

        // Apply visual active status indicators to active tab link
        document.querySelectorAll('.tab-link').forEach(function(link) {
            link.classList.remove('border-[#ef3b45]', 'text-[#0e1e3a]');
            link.classList.add('border-transparent', 'text-gray-400');
        });
        
        var activeLink = document.getElementById(activeTab + '-tab');
        if (activeLink) {
            activeLink.classList.remove('border-transparent', 'text-gray-400');
            activeLink.classList.add('border-[#ef3b45]', 'text-[#0e1e3a]');
        }

        // Tab click event listeners
        document.querySelectorAll('.tab-link').forEach(function(tab) {
            tab.addEventListener('click', function(event) {
                event.preventDefault(); 

                // Reset all links styling
                document.querySelectorAll('.tab-link').forEach(function(link) {
                    link.classList.remove('border-[#ef3b45]', 'text-[#0e1e3a]');
                    link.classList.add('border-transparent', 'text-gray-400');
                });

                // Set active link styling
                this.classList.remove('border-transparent', 'text-gray-400');
                this.classList.add('border-[#ef3b45]', 'text-[#0e1e3a]');

                // Hide all tab panels
                document.querySelectorAll('.tab-panel').forEach(function(panel) {
                    panel.classList.add('hidden');
                });

                // Show selected panel
                var targetPanelId = this.getAttribute('href').substr(1);
                var targetPanel = document.getElementById(targetPanelId);
                if (targetPanel) {
                    targetPanel.classList.remove('hidden');
                }
            });
        });
        </script>
    </div>
</x-app-layout>