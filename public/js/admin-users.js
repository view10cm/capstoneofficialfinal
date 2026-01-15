// public/js/admin-users.js

// Pagination variables
let allUsers = [];
let currentPage = 1;
const itemsPerPage = 7;

let currentUserId = null;
let currentUserName = null;
let currentUserRole = null;
let openDropdownId = null;

// Display a specific page of users
function displayPage(pageNumber) {
    const tableBody = document.getElementById('usersTableBody');
    const totalPages = Math.ceil(allUsers.length / itemsPerPage);
    
    if (pageNumber < 1) pageNumber = 1;
    if (pageNumber > totalPages) pageNumber = totalPages;
    
    currentPage = pageNumber;
    
    const startIndex = (pageNumber - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageItems = allUsers.slice(startIndex, endIndex);
    
    tableBody.innerHTML = '';
    
    pageItems.forEach(user => {
        const row = createUserRow(user);
        tableBody.appendChild(row);
    });
    
    updatePaginationControls();
}

// Create user row element
function createUserRow(user) {
    const row = document.createElement('tr');
    row.className = 'user-row hover:bg-gray-50';
    row.setAttribute('data-name', user.name.toLowerCase());
    row.setAttribute('data-email', user.email.toLowerCase());
    row.setAttribute('data-status', user.status);
    
    const statusBadgeClass = user.status === 'Activated' ? 
        'bg-green-100 text-green-800 border-green-200 hover:bg-green-50' : 
        'bg-red-100 text-red-800 border-red-200 hover:bg-red-50';
    
    const lastLogin = user.last_login ? 
        new Date(user.last_login).toLocaleString('en-US', { 
            year: 'numeric', month: '2-digit', day: '2-digit', 
            hour: '2-digit', minute: '2-digit', hour12: false 
        }).replace(',', '') : 'Never logged in';
    
    let roleClass = 'bg-gray-100 text-gray-800';
    if (user.role === 'Admin') roleClass = 'bg-purple-100 text-purple-800';
    else if (user.role === 'Staff') roleClass = 'bg-blue-100 text-blue-800';
    else if (user.role === 'Kitchen') roleClass = 'bg-yellow-100 text-yellow-800';
    
    row.innerHTML = `
        <td class="py-4 px-6 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                    <span class="text-gray-600 font-medium">${user.name.charAt(0).toUpperCase()}</span>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">${user.name}</div>
                </div>
            </div>
        </td>
        <td class="py-4 px-6 whitespace-nowrap">
            <div class="text-sm text-gray-900">${user.email}</div>
        </td>
        <td class="py-4 px-6 whitespace-nowrap">
            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${roleClass}">
                ${user.role}
            </span>
        </td>
        <td class="py-4 px-6 whitespace-nowrap">
            <div class="relative inline-block w-40">
                <div class="relative">
                    <div id="status-badge-${user.id}" 
                         onclick="toggleDropdown(${user.id})"
                         class="inline-flex items-center justify-between px-3 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-all duration-200 hover:shadow-md ${statusBadgeClass} border">
                        <span>${user.status}</span>
                        <svg class="ml-2 w-4 h-4 transition-transform duration-200" 
                             id="dropdown-arrow-${user.id}"
                             fill="none" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="2" 
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div id="status-dropdown-${user.id}" 
                         class="absolute z-10 hidden mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                        <button type="button" 
                                onclick="selectStatus(${user.id}, 'Activated', '${user.name}', '${user.role}')"
                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center justify-between ${user.status === 'Activated' ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700'}">
                            <span>Activated</span>
                            ${user.status === 'Activated' ? '<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>' : ''}
                        </button>
                        <div class="border-t border-gray-100 my-1"></div>
                        <button type="button" 
                                onclick="selectStatus(${user.id}, 'Deactivated', '${user.name}', '${user.role}')"
                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center justify-between ${user.status === 'Deactivated' ? 'bg-red-50 text-red-700 font-medium' : 'text-gray-700'}">
                            <span>Deactivated</span>
                            ${user.status === 'Deactivated' ? '<svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>' : ''}
                        </button>
                    </div>
                </div>
            </div>
        </td>
        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-500">
            ${lastLogin}
        </td>
    `;
    
    return row;
}

// Update pagination controls
function updatePaginationControls() {
    const totalPages = Math.ceil(allUsers.length / itemsPerPage);
    
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const currentPageSpan = document.getElementById('currentPageNum');
    const totalPagesSpan = document.getElementById('totalPagesNum');
    
    if (currentPageSpan) currentPageSpan.textContent = currentPage;
    if (totalPagesSpan) totalPagesSpan.textContent = totalPages;
    
    if (prevBtn) {
        if (currentPage === 1) {
            prevBtn.disabled = true;
            prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevBtn.disabled = false;
            prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
    
    if (nextBtn) {
        if (currentPage >= totalPages) {
            nextBtn.disabled = true;
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            nextBtn.disabled = false;
            nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

// Navigation functions
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayPage(currentPage);
    }
}

function nextPage() {
    const totalPages = Math.ceil(allUsers.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayPage(currentPage);
    }
}

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
    
    // Find the user in the data arrays and update
    const updateUserInArray = (array) => {
        const userIndex = array.findIndex(u => u.id === userId);
        if (userIndex !== -1) {
            array[userIndex].status = newStatus;
        }
    };
    
    updateUserInArray(allUsers);
    if (window.baseUsersData) {
        updateUserInArray(window.baseUsersData);
    }
    
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
    
    // Refresh the current page display to show updated status
    displayPage(currentPage);
    
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

// Create new user via AJAX
function createNewUser() {
    const form = document.getElementById('addUserForm');
    const formData = new FormData(form);
    
    // Clear previous errors
    clearFormErrors();
    
    // Show loading state
    const createBtn = document.getElementById('createAccountBtn');
    const spinner = document.getElementById('createAccountSpinner');
    const btnText = document.getElementById('createAccountText');
    
    spinner.classList.remove('hidden');
    btnText.textContent = 'Creating...';
    createBtn.disabled = true;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    fetch('/admin/users/create', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json().then(data => ({ status: response.status, body: data })))
    .then(({ status, body }) => {
        if (status === 201 || status === 200) {
            // Success
            showNotification('Account created successfully!', 'success');
            closeAddUserModal();
            addUserToTable(body.user);
            
            // Reset form
            form.reset();
        } else if (status === 422) {
            // Validation errors
            handleValidationErrors(body.errors);
        } else {
            // Other errors
            throw new Error(body.message || 'Failed to create user');
        }
    })
    .catch(error => {
        console.error('Error creating user:', error);
        showNotification(error.message || 'Failed to create user. Please try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        spinner.classList.add('hidden');
        btnText.textContent = 'Create Account';
        createBtn.disabled = false;
    });
}

// Add new user to the table
function addUserToTable(user) {
    // Add user to base data
    if (!window.baseUsersData) {
        window.baseUsersData = [];
    }
    window.baseUsersData.unshift(user);
    
    // Reset allUsers to show all users including the new one
    allUsers = [...window.baseUsersData];
    
    // Reset to first page and display
    currentPage = 1;
    displayPage(1);
}

// Update total user count
function updateTotalCount(increment) {
    // This function is kept for compatibility but not used in current implementation
    // Total count is dynamically calculated from allUsers.length
}

// Handle validation errors
function handleValidationErrors(errors) {
    for (const field in errors) {
        const errorElement = document.getElementById(`${field}Error`);
        if (errorElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove('hidden');
            
            // Add error styling to input
            const inputElement = document.getElementById(`newUser${field.charAt(0).toUpperCase() + field.slice(1)}`);
            if (inputElement) {
                inputElement.classList.add('border-red-500');
                inputElement.classList.remove('border-gray-300');
            }
        }
    }
}

// Clear form errors
function clearFormErrors() {
    const errorElements = document.querySelectorAll('[id$="Error"]');
    errorElements.forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
    
    const inputs = document.querySelectorAll('#addUserForm input');
    inputs.forEach(input => {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-300');
    });
}

// Apply filters to the table
function applyFilters() {
    searchUsers();
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

// Add New User Modal Functions
function addNewUser() {
    clearFormErrors();
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
    const form = document.getElementById('addUserForm');
    
    // Reset form
    form.reset();
    clearFormErrors();
    
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Search and Filter Functions
function searchUsers() {
    const searchInput = document.getElementById('searchInput');
    const filter = searchInput.value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter');
    const selectedStatus = statusFilter.value;
    
    // Get base users (stored during page load)
    const baseUsers = window.baseUsersData || allUsers;
    
    // Filter users based on search and status
    const filteredUsers = baseUsers.filter(user => {
        const nameMatch = user.name.toLowerCase().includes(filter);
        const emailMatch = user.email.toLowerCase().includes(filter);
        const statusMatch = !selectedStatus || user.status === selectedStatus;
        
        return (nameMatch || emailMatch) && statusMatch;
    });
    
    // Update allUsers with filtered results
    allUsers = filteredUsers;
    
    // Reset to first page and display
    currentPage = 1;
    displayPage(1);
}

function filterUsers() {
    searchUsers(); // Use unified search function
}

// Initialize event listeners
function initUserManagement() {
    // Modal event handlers
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmDeactivateBtn = document.getElementById('confirmDeactivateBtn');
    const modal = document.getElementById('confirmationModal');
    const addUserForm = document.getElementById('addUserForm');
    
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
    
    // Add User Form submission
    if (addUserForm) {
        addUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            createNewUser();
        });
    }
    
    // Close modals when clicking outside
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target.id === 'confirmationModal') {
                closeModal();
            }
        });
    }
    
    const addUserModal = document.getElementById('addUserModal');
    if (addUserModal) {
        addUserModal.addEventListener('click', function(e) {
            if (e.target.id === 'addUserModal') {
                closeAddUserModal();
            }
        });
    }
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (modal && !modal.classList.contains('hidden')) {
                closeModal();
            }
            if (addUserModal && !addUserModal.classList.contains('hidden')) {
                closeAddUserModal();
            }
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