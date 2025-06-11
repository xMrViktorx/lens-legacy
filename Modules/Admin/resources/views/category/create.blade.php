@extends('admin::layouts.navigation')

@section('content')
    <div class="bg-white p-6 shadow-md sm:rounded-lg">
        <form method="POST" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Kategória neve <span class="text-red-700">*</span> <small>[{{ app()->getLocale() }}]</small></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('name')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="slug" class="block mb-2 text-sm font-medium text-gray-900">Kategória slug <span class="text-red-700">*</span></label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('slug')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Kategória leírása <small>[{{ app()->getLocale() }}]</small></label>
                <textarea id="description" name="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Kép feltöltése <span class="text-red-700">*</span></label>
                <input type="file" id="image" name="image" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('image')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Mentés</button>
        </form>
    </div>
@endsection
