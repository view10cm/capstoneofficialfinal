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
                                        <div class="relative">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($user->status == 'Activated') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $user->status }}
                                            </span>
                                            <select class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                                    onchange="updateStatus(this, {{ $user->id }})"
                                                    data-current-status="{{ $user->status }}">
                                                <option value="Activated" {{ $user->status == 'Activated' ? 'selected' : '' }}>Activated</option>
                                                <option value="Deactivated" {{ $user->status == 'Deactivated' ? 'selected' : '' }}>Deactivated</option>
                                            </select>
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

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($users->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-white cursor-not-allowed">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif
                            
                            @if($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-white cursor-not-allowed">
                                    Next
                                </span>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $users->firstItem() }}</span> to 
                                    <span class="font-medium">{{ $users->lastItem() }}</span> of 
                                    <span class="font-medium">{{ $users->total() }}</span> users
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    @if($users->onFirstPage())
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Previous</span>
                                            Previous
                                        </span>
                                    @else
                                        <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            Previous
                                        </a>
                                    @endif
                                    
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                        Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                                    </span>
                                    
                                    @if($users->hasMorePages())
                                        <a href="{{ $users->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            Next
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Next</span>
                                            Next
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

    <script>
    function updateStatus(select, userId) {
        const status = select.value;
        const badge = select.previousElementSibling;
        const currentStatus = select.getAttribute('data-current-status');
        
        // Don't do anything if status didn't change
        if (status === currentStatus) {
            select.value = currentStatus; // Reset to original value
            return;
        }
        
        // Update badge text and color
        badge.textContent = status;
        
        if (status === 'Activated') {
            badge.className = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
        } else {
            badge.className = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
        }
        
        // Update the current status attribute
        select.setAttribute('data-current-status', status);
        
        // Make AJAX call to update the user status in the database
        fetch("{{ route('admin.users.updateStatus', ['user' => 'USER_ID']) }}".replace('USER_ID', userId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: status
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
            // Show success message (optional)
            showNotification('User status updated successfully!', 'success');
        })
        .catch(error => {
            console.error('Error updating status:', error);
            // Revert changes on error
            select.value = currentStatus;
            badge.textContent = currentStatus;
            if (currentStatus === 'Activated') {
                badge.className = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
            } else {
                badge.className = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
            }
            
            // Show error message
            showNotification('Failed to update user status. Please try again.', 'error');
        });
    }
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    </script>
@endsection