// public/js/admin-users.js

let currentUserId = null;
let currentUserName = null;
let currentUserRole = null;
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
    
    // Update the data-status attribute for filtering
    const row = document.querySelector(`.user-row[data-user-id="${userId}"]`);
    if (!row) {
        // Try to find row by checking all rows for this user
        const allRows = document.querySelectorAll('.user-row');
        allRows.forEach(r => {
            if (r.querySelector(`#status-badge-${userId}`)) {
                r.setAttribute('data-status', newStatus);
                
                // Reapply filters if any are active
                const statusFilter = document.getElementById('statusFilter');
                const selectedStatus = statusFilter.value;
                
                if (selectedStatus && selectedStatus !== newStatus) {
                    r.style.display = 'none';
                }
            }
        });
    } else {
        row.setAttribute('data-status', newStatus);
        
        // Reapply filters if any are active
        const statusFilter = document.getElementById('statusFilter');
        const selectedStatus = statusFilter.value;
        
        if (selectedStatus && selectedStatus !== newStatus) {
            row.style.display = 'none';
        }
    }
    
    // Make AJAX call to update the user status in the database
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    fetch(`/admin/users/${userId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
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

// Initialize event listeners
function initUserManagement() {
    // Modal event handlers
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmDeactivateBtn = document.getElementById('confirmDeactivateBtn');
    const modal = document.getElementById('confirmationModal');
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }
    
    if (confirmDeactivateBtn) {
        confirmDeactivateBtn.addEventListener('click', function() {
            if (currentUserId) {
                // Update status to Deactivated
                updateUserStatus(currentUserId, 'Deactivated');
                closeModal();
            }
        });
    }
    
    // Close modal when clicking outside
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target.id === 'confirmationModal') {
                closeModal();
            }
        });
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
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
    const statusBadges = document.querySelectorAll('[id^="status-badge-"]');
    statusBadges.forEach(badge => {
        const userId = badge.id.replace('status-badge-', '');
        badge.onclick = () => toggleDropdown(userId);
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initUserManagement);

        // Search and Filter Functions
        function searchUsers() {
            const searchInput = document.getElementById('searchInput');
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('.user-row');
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                
                if (name.includes(filter) || email.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        function filterUsers() {
            const statusFilter = document.getElementById('statusFilter');
            const selectedStatus = statusFilter.value;
            const rows = document.querySelectorAll('.user-row');
            
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                
                if (!selectedStatus || status === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update the search input if it has value
            const searchInput = document.getElementById('searchInput');
            if (searchInput.value) {
                searchUsers(); // Reapply search filter after status filter
            }
        }
        
        // Update status and refresh filter display
        function updateUserStatusAndRefresh(userId, newStatus) {
            // First update the user status via AJAX
            updateUserStatus(userId, newStatus);
            
            // Then update the data-status attribute for filtering
            const row = document.querySelector(`.user-row[data-user-id="${userId}"]`);
            if (row) {
                row.setAttribute('data-status', newStatus);
                
                // Reapply filters if any are active
                const statusFilter = document.getElementById('statusFilter');
                const selectedStatus = statusFilter.value;
                
                if (selectedStatus && selectedStatus !== newStatus) {
                    row.style.display = 'none';
                }
            }
        }
        
        // Add New User Modal Functions
        function addNewUser() {
            const modal = document.getElementById('addUserModal');
            const modalContent = document.getElementById('addUserModalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.add('modal-show');
                modalContent.style.opacity = '1';
                modalContent.style.transform = 'scale(1)';
            }, 10);
        }
        
        function closeAddUserModal() {
            const modal = document.getElementById('addUserModal');
            const modalContent = document.getElementById('addUserModalContent');
            
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
        
        // Close modal when clicking outside
        document.getElementById('addUserModal').addEventListener('click', function(e) {
            if (e.target.id === 'addUserModal') {
                closeAddUserModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('addUserModal').classList.contains('hidden')) {
                closeAddUserModal();
            }
        });
        
        // Initialize status filter based on URL parameter if present
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');
            
            if (statusParam && (statusParam === 'Activated' || statusParam === 'Deactivated')) {
                const statusFilter = document.getElementById('statusFilter');
                statusFilter.value = statusParam;
                filterUsers();
            }
        });