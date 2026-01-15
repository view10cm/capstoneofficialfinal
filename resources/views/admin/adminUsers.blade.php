@extends('base')

@section('title', 'Caffe Arabica - User Management')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('admin.adminSidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Header -->
            <div class="bg-white p-5 shadow-sm shadow-gray-500/50">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-6">
                        <div class="text-center lg:text-left">
                            <h1 class="text-3xl font-bold text-gray-900">Users</h1>
                            <p class="text-gray-500 text-sm">Manage users account on your system</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table Container -->
            <div class="flex-1 p-5">
                <!-- Header with Controls -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Left side: User Text -->
                            <div class="flex items-center mb-4 lg:mb-0">
                                <div class="bg-white p-3 rounded-lg mr-3">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 13C11.2091 13 13 11.2091 13 9C13 6.79086 11.2091 5 9 5C6.79086 5 5 6.79086 5 9C5 11.2091 6.79086 13 9 13Z" stroke="#E67809" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M16 19C16 15.686 12.866 13 9 13C5.134 13 2 15.686 2 19M15 13C15.6684 13 16.3261 12.8324 16.9131 12.5127C17.5 12.193 17.9975 11.7313 18.3601 11.1698C18.7227 10.6083 18.9388 9.96494 18.9886 9.29841C19.0385 8.63189 18.9205 7.9635 18.6456 7.3543C18.3706 6.7451 17.9473 6.21453 17.4144 5.81105C16.8816 5.40757 16.2561 5.14404 15.5952 5.04456C14.9342 4.94507 14.2589 5.01279 13.6309 5.24154C13.0028 5.47028 12.4421 5.85275 12 6.354" stroke="#E67809" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M22 19.0001C22 15.6861 18.866 13.0001 15 13.0001C14.193 13.0001 12.897 12.7071 12 11.7651" stroke="#E67809" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                </div>
                            <div>    
                                <h1 class="text-2xl font-bold text-gray-800">Users</h1>
                                {{-- <span class="ml-2 px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">
                                    {{ $users->total() }} total
                                </span> --}}
                            </div>
                            </div>

                            <!-- Right side: Search, Filter, and Add Button -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Search Bar -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           id="searchInput"
                                           placeholder="Search users by name or email..." 
                                           class="pl-10 pr-4 py-2.5 w-full sm:w-64 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors"
                                           onkeyup="searchUsers()">
                                </div>

                                <!-- Status Filter Dropdown -->
                                <div class="relative">
                                    <select id="statusFilter" 
                                            onchange="filterUsers()"
                                            class="appearance-none pl-4 pr-10 py-2.5 w-full sm:w-48 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors bg-white cursor-pointer">
                                        <option value="">All Status</option>
                                        <option value="Activated">Activated</option>
                                        <option value="Deactivated">Deactivated</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Add New User Button -->
                                <button onclick="addNewUser()"
                                        class="flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-lg font-medium hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-sm hover:shadow transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add New User
                                </button>
                            </div>
                        </div>
                    </div>

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
                            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                                @foreach($users as $user)
                                <tr class="user-row hover:bg-gray-50" 
                                    data-name="{{ strtolower($user->name) }}"
                                    data-email="{{ strtolower($user->email) }}"
                                    data-status="{{ $user->status }}">
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

                    <!-- Orange-themed Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between w-full">
                            <button id="prevPage" onclick="previousPage()" 
                                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:text-amber-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                <span>Previous</span>
                            </button>
                            
                            <div class="text-sm text-gray-700">
                                Page <span id="currentPageNum" class="font-semibold text-amber-700">1</span> of 
                                <span id="totalPagesNum" class="font-semibold">1</span>
                            </div>
                            
                            <button id="nextPage" onclick="nextPage()" 
                                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:text-amber-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:text-gray-700">
                                <span>Next</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Initialize pagination on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Store initial users from blade
        allUsers = @json($users);
        window.baseUsersData = @json($users);
        displayPage(1);
    });
    </script>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-transparent bg-opacity-50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
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

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-transparent bg-opacity-50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="addUserModalContent">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Add New User</h3>
                    <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Add User Form -->
                <form id="addUserForm">
                    @csrf
                    <div class="space-y-4">
                        <!-- Name Field -->
                        <div>
                            <label for="newUserName" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" 
                                   id="newUserName" 
                                   name="name"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors"
                                   placeholder="Enter full name">
                            <div id="nameError" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <!-- Email Field -->
                        <div>
                            <label for="newUserEmail" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" 
                                   id="newUserEmail" 
                                   name="email"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors"
                                   placeholder="Enter email address">
                            <div id="emailError" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <!-- Password Field -->
                        <div>
                            <label for="newUserPassword" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" 
                                   id="newUserPassword" 
                                   name="password"
                                   required
                                   minlength="6"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors"
                                   placeholder="Enter password (min. 6 characters)">
                            <div id="passwordError" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <!-- Confirm Password Field -->
                        <div>
                            <label for="newUserPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" 
                                   id="newUserPasswordConfirmation" 
                                   name="password_confirmation"
                                   required
                                   minlength="6"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors"
                                   placeholder="Confirm password">
                            <div id="passwordConfirmationError" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <!-- Role Information -->
                        <div class="p-3 bg-amber-50 rounded-lg border border-amber-100">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium text-amber-700">Note:</span> New users will automatically be assigned the <span class="font-semibold text-amber-800">Staff</span> role.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Form Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-100">
                        <button type="button" 
                                onclick="closeAddUserModal()"
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                id="createAccountBtn"
                                class="px-5 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-lg text-sm font-medium hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-sm hover:shadow transition-all duration-200 flex items-center">
                            <svg id="createAccountSpinner" class="hidden w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="createAccountText">Create Account</span>
                        </button>
                    </div>
                </form>
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
        
        /* Orange-themed pagination enhancements */
        .pagination-link {
            transition: all 0.2s ease-in-out;
        }

        .pagination-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(249, 115, 22, 0.1);
        }

        .current-page {
            position: relative;
            overflow: hidden;
        }

        .current-page::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #f97316, transparent);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 0.7;
            }
            50% {
                opacity: 1;
            }
        }

        /* Mobile pagination buttons */
        @media (max-width: 640px) {
            .pagination-sm {
                font-size: 0.875rem;
                padding: 0.5rem 0.75rem;
            }
        }

        /* Form validation styles */
        .border-red-500 {
            border-color: #ef4444 !important;
        }

        .border-green-500 {
            border-color: #10b981 !important;
        }
    </style>

    <!-- Include external JavaScript file -->
    <script src="{{ asset('js/admin-users.js') }}"></script>
@endsection