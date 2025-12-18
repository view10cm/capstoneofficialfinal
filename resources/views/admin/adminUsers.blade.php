@extends('base')

@section('title', 'Caffe Arabica - User Management')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('admin.adminSidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <div class="bg-white shadow p-6">
                <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
            </div>

            <!-- Users Table Container -->
            <div class="flex-1 p-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-gray-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($user->role == 'Admin') bg-purple-100 text-purple-800
                                            @elseif($user->role == 'Staff') bg-blue-100 text-blue-800
                                            @elseif($user->role == 'Kitchen') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="relative inline-block w-40">
                                            <div class="relative">
                                                <!-- Status Badge with Dropdown Arrow -->
                                                <div id="status-badge-{{ $user->id }}" 
                                                     class="inline-flex items-center justify-between px-3 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-all duration-200 hover:shadow-md
                                                     @if($user->status == 'Activated') bg-green-100 text-green-800 border border-green-200 hover:bg-green-50
                                                     @else bg-red-100 text-red-800 border border-red-200 hover:bg-red-50
                                                     @endif">
                                                    <span>{{ $user->status }}</span>
                                                    <svg class="ml-2 w-4 h-4 transition-transform duration-200" 
                                                         id="dropdown-arrow-{{ $user->id }}"
                                                         fill="none" 
                                                         stroke="currentColor" 
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" 
                                                              stroke-linejoin="round" 
                                                              stroke-width="2" 
                                                              d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                                
                                                <!-- Hidden Dropdown -->
                                                <div id="status-dropdown-{{ $user->id }}" 
                                                     class="absolute z-10 hidden mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                                                    <button type="button" 
                                                            onclick="selectStatus({{ $user->id }}, 'Activated', '{{ $user->name }}', '{{ $user->role }}')"
                                                            class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center justify-between
                                                            @if($user->status == 'Activated') bg-green-50 text-green-700 font-medium @else text-gray-700 @endif">
                                                        <span>Activated</span>
                                                        @if($user->status == 'Activated')
                                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                    <div class="border-t border-gray-100 my-1"></div>
                                                    <button type="button" 
                                                            onclick="selectStatus({{ $user->id }}, 'Deactivated', '{{ $user->name }}', '{{ $user->role }}')"
                                                            class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center justify-between
                                                            @if($user->status == 'Deactivated') bg-red-50 text-red-700 font-medium @else text-gray-700 @endif">
                                                        <span>Deactivated</span>
                                                        @if($user->status == 'Deactivated')
                                                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-500">
                                        @if($user->last_login)
                                            {{ \Carbon\Carbon::parse($user->last_login)->setTimezone(config('app.timezone', 'Asia/Manila'))->format('Y-m-d H:i') }}
                                        @else
                                            Never logged in
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination (Keep existing pagination code) -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <!-- ... existing pagination code ... -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="p-6">
                <div class="flex items-start mb-4">
                    <div class="flex-shrink-0 h-12 w-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900">Confirm Account Deactivation</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">
                                Are you sure you want to deactivate <span id="userName" class="font-bold text-gray-800"></span>'s account?
                            </p>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Role:</span> <span id="userRole" class="font-semibold"></span>
                                </p>
                                <p class="text-sm text-red-600 mt-2 font-medium">
                                    ⚠️ Deactivated accounts cannot log into the system.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <button id="cancelBtn" type="button" 
                            class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                        Cancel
                    </button>
                    <button id="confirmDeactivateBtn" type="button" 
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg text-sm font-medium hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm hover:shadow transition-all duration-200">
                        Deactivate Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Animation for dropdown */
        .dropdown-enter {
            opacity: 0;
            transform: translateY(-10px);
        }
        .dropdown-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 200ms, transform 200ms;
        }
        .dropdown-leave {
            opacity: 1;
            transform: translateY(0);
        }
        .dropdown-leave-active {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 200ms, transform 200ms;
        }
        
        /* Modal animation */
        .modal-show {
            opacity: 1;
            transform: scale(1);
        }
    </style>

    <script>
    let currentUserId = null;
    let currentUserName = null;
    let currentUserRole = null;
    let currentStatus = null;
    let openDropdownId = null;
    
    // Toggle dropdown visibility
    function toggleDropdown(userId) {
        const dropdown = document.getElementById(`status-dropdown-${userId}`);
        const arrow = document.getElementById(`dropdown-arrow-${userId}`);
        const badge = document.getElementById(`status-badge-${userId}`);
        
        // Close any other open dropdown
        if (openDropdownId && openDropdownId !== userId) {
            closeDropdown(openDropdownId);
        }
        
        if (dropdown.classList.contains('hidden')) {
            // Open dropdown
            dropdown.classList.remove('hidden');
            arrow.classList.add('rotate-180');
            badge.classList.add('shadow-md');
            openDropdownId = userId;
            
            // Add animation classes
            dropdown.classList.add('dropdown-enter-active');
            setTimeout(() => {
                dropdown.classList.remove('dropdown-enter-active');
            }, 200);
        } else {
            closeDropdown(userId);
        }
    }
    
    function closeDropdown(userId) {
        const dropdown = document.getElementById(`status-dropdown-${userId}`);
        const arrow = document.getElementById(`dropdown-arrow-${userId}`);
        const badge = document.getElementById(`status-badge-${userId}`);
        
        if (dropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('dropdown-leave-active');
            setTimeout(() => {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('dropdown-leave-active');
            }, 200);
        }
        
        if (arrow) arrow.classList.remove('rotate-180');
        if (badge) badge.classList.remove('shadow-md');
        openDropdownId = null;
    }
    
    // Handle status selection
    function selectStatus(userId, newStatus, userName, userRole) {
        const badge = document.getElementById(`status-badge-${userId}`);
        const currentStatus = badge.textContent.trim();
        
        // Close dropdown
        closeDropdown(userId);
        
        // If status didn't change, do nothing
        if (newStatus === currentStatus) {
            return;
        }
        
        // If changing from Activated to Deactivated, show confirmation modal
        if (currentStatus === 'Activated' && newStatus === 'Deactivated') {
            currentUserId = userId;
            currentUserName = userName;
            currentUserRole = userRole;
            
            // Show modal with user details
            document.getElementById('userName').textContent = userName;
            document.getElementById('userRole').textContent = userRole;
            
            const modal = document.getElementById('confirmationModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.add('modal-show');
                modalContent.style.opacity = '1';
                modalContent.style.transform = 'scale(1)';
            }, 10);
        } else {
            // For other changes (Deactivated to Activated), update immediately
            updateUserStatus(userId, newStatus);
        }
    }
    
    // Update user status via AJAX
    function updateUserStatus(userId, newStatus) {
        const badge = document.getElementById(`status-badge-${userId}`);
        
        // Update UI immediately for better UX
        badge.innerHTML = `<span>${newStatus}</span>
                          <svg class="ml-2 w-4 h-4 transition-transform duration-200" 
                               id="dropdown-arrow-${userId}"
                               fill="none" 
                               stroke="currentColor" 
                               viewBox="0 0 24 24">
                            <path stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="2" 
                                  d="M19 9l-7 7-7-7" />
                          </svg>`;
        
        if (newStatus === 'Activated') {
            badge.className = 'inline-flex items-center justify-between px-3 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-all duration-200 hover:shadow-md bg-green-100 text-green-800 border border-green-200 hover:bg-green-50';
        } else {
            badge.className = 'inline-flex items-center justify-between px-3 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-all duration-200 hover:shadow-md bg-red-100 text-red-800 border border-red-200 hover:bg-red-50';
        }
        
        // Reattach click event to the badge
        badge.onclick = () => toggleDropdown(userId);
        
        // Make AJAX call to update the user status in the database
        fetch("{{ route('admin.users.updateStatus', ['user' => 'USER_ID']) }}".replace('USER_ID', userId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Status updated successfully:', data);
            showNotification(`User status updated to ${newStatus} successfully!`, 'success');
        })
        .catch(error => {
            console.error('Error updating status:', error);
            // Revert changes on error
            showNotification('Failed to update user status. Please try again.', 'error');
        });
    }
    
    // Show notification
    function showNotification(message, type) {
        // Remove any existing notification
        const existingNotification = document.querySelector('.notification-toast');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification-toast fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium z-50 transform transition-all duration-300 translate-x-full ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Modal event handlers
    document.getElementById('cancelBtn').addEventListener('click', function() {
        closeModal();
    });
    
    document.getElementById('confirmDeactivateBtn').addEventListener('click', function() {
        if (currentUserId) {
            // Update status to Deactivated
            updateUserStatus(currentUserId, 'Deactivated');
            closeModal();
        }
    });
    
    // Close modal
    function closeModal() {
        const modal = document.getElementById('confirmationModal');
        const modalContent = document.getElementById('modalContent');
        
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            currentUserId = null;
            currentUserName = null;
            currentUserRole = null;
        }, 300);
    }
    
    // Close modal when clicking outside
    document.getElementById('confirmationModal').addEventListener('click', function(e) {
        if (e.target.id === 'confirmationModal') {
            closeModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('confirmationModal').classList.contains('hidden')) {
            closeModal();
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (openDropdownId && !e.target.closest(`#status-badge-${openDropdownId}`) && !e.target.closest(`#status-dropdown-${openDropdownId}`)) {
            closeDropdown(openDropdownId);
        }
    });
    
    // Initialize click events for badges
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($users as $user)
            document.getElementById(`status-badge-{{ $user->id }}`).onclick = () => toggleDropdown({{ $user->id }});
        @endforeach
    });
    </script>
@endsection