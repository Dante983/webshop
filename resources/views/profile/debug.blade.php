<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Account - Debug') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3>Debug Information</h3>
                    <p>User ID: {{ $user->id }}</p>
                    <p>User Name: {{ $user->name }}</p>
                    <p>User Email: {{ $user->email }}</p>
                    
                    <h3 class="mt-4">Orders Information</h3>
                    <p>Order Count: {{ $orders->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>