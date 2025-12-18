@extends('base')

@section('title', 'Caffe Arabica - Dashboard')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('admin.adminSidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col p-6">
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <!-- Dashboard cards/statistics can go here -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Quick Stats</h3>
                    <!-- Add dashboard content -->
                </div>
            </div>
        </div>
    </div>
@endsection