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

                    <!-- Orange-themed Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($users->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-white cursor-not-allowed">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Previous
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-amber-50 hover:border-amber-300 hover:text-amber-700 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Previous
                                </a>
                            @endif
                            
                            @if($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-amber-50 hover:border-amber-300 hover:text-amber-700 transition-all duration-200">
                                    Next
                                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-white cursor-not-allowed">
                                    Next
                                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                        
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium text-amber-700">{{ $users->firstItem() }}</span> to 
                                    <span class="font-medium text-amber-700">{{ $users->lastItem() }}</span> of 
                                    <span class="font-medium text-amber-700">{{ $users->total() }}</span> users
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px" aria-label="Pagination">
                                    <!-- Previous Page Link -->
                                    @if($users->onFirstPage())
                                        <span class="relative inline-flex items-center px-3 py-2 rounded-l-lg border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                            <span class="ml-1 hidden sm:inline">Previous</span>
                                        </span>
                                    @else
                                        <a href="{{ $users->previousPageUrl() }}" 
                                           class="relative inline-flex items-center px-3 py-2 rounded-l-lg border border-gray-300 bg-white text-sm font-medium text-gray-600 hover:bg-amber-50 hover:border-amber-300 hover:text-amber-700 transition-all duration-200 group">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                            <span class="ml-1 hidden sm:inline">Previous</span>
                                        </a>
                                    @endif
                                    
                                    <!-- Page Numbers -->
                                    @php
                                        $current = $users->currentPage();
                                        $last = $users->lastPage();
                                        $start = max(1, $current - 2);
                                        $end = min($last, $current + 2);
                                        
                                        // Adjust if we're near the beginning
                                        if ($current <= 3) {
                                            $end = min($last, 5);
                                        }
                                        
                                        // Adjust if we're near the end
                                        if ($current >= $last - 2) {
                                            $start = max(1, $last - 4);
                                        }
                                    @endphp
                                    
                                    <!-- First Page -->
                                    @if($start > 1)
                                        <a href="{{ $users->url(1) }}" 
                                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-amber-50 hover:border-amber-300 hover:text-amber-700 transition-all duration-200">
                                            1
                                        </a>
                                        @if($start > 2)
                                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500">
                                                ...
                                            </span>
                                        @endif
                                    @endif
                                    
                                    <!-- Page Links -->
                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $current)
                                            <span class="relative inline-flex items-center px-4 py-2 border border-amber-500 bg-amber-50 text-sm font-medium text-amber-700">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $users->url($page) }}" 
                                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-amber-50 hover:border-amber-300 hover:text-amber-700 transition-all duration-200">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor
                                    
                                    <!-- Last Page -->
                                    @if($end < $last)
                                        @if($end < $last - 1)
                                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500">
                                                ...
                                            </span>
                                        @endif
                                        <a href="{{ $users->url($last) }}" 
                                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-amber-50 hover:border-amber-300 hover:text-amber-700 transition-all duration-200">
                                            {{ $last }}
                                        </a>
                                    @endif
                                    
                                    <!-- Next Page Link -->
                                    @if($users->hasMorePages())
                                        <a href="{{ $users->nextPageUrl() }}" 
                                           class="relative inline-flex items-center px-3 py-2 rounded-r-lg border border-gray-300 bg-white text-sm font-medium text-gray-600 hover:bg-amber-50 hover:border-amber-300 hover:text-amber-700 transition-all duration-200 group">
                                            <span class="mr-1 hidden sm:inline">Next</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-3 py-2 rounded-r-lg border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="mr-1 hidden sm:inline">Next</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
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
    </style>

    <!-- Include external JavaScript file -->
    <script src="{{ asset('js/admin-users.js') }}"></script>
@endsection