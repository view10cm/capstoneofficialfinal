@extends('base')

@section('title', 'Caffe Arabica - Order History')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('admin.adminSidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col p-6">
            <h1 class="text-2xl font-bold text-gray-800">Order History</h1>
            <!-- Add your order history content here -->
        </div>
    </div>
@endsection