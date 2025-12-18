@extends('base')

@section('title', 'Caffe Arabica - Inventory')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('admin.adminSidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col p-6">
            <h1 class="text-2xl font-bold text-gray-800">Inventory Management</h1>
            <!-- Add your inventory content here -->
        </div>
    </div>
@endsection