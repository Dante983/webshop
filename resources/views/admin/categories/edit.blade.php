@extends('layouts.admin')

@section('title', 'Edit Category')
@section('header', 'Edit Category')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full rounded-md border-gray-300" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4" class="w-full rounded-md border-gray-300">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex items-center justify-end">
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 