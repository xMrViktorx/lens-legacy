@extends('admin::layouts.navigation')

@section('content')
    <div class="bg-white p-6 shadow-md sm:rounded-lg">
        <form method="POST" action="{{ route('admin.category.update', $category->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Kategória neve <small>[{{ app()->getLocale() }}]</small><span class="text-red-700">*</span></label>
                <input type="text" id="name" name="name" value="{{ $category->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('name')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="slug" class="block mb-2 text-sm font-medium text-gray-900">Kategória slug <span class="text-red-700">*</span></label>
                <input type="text" id="slug" name="slug" value="{{ $category->slug }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('slug')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Státusz <span class="text-red-700">*</span></label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Aktív</option>
                    <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Inaktív</option>
                </select>
                @error('status')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="position" class="block mb-2 text-sm font-medium text-gray-900">Pozíció <span class="text-red-700">*</span></label>
                <input type="number" id="position" name="position" value="{{ $category->position }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('position')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Kategória leírása <small>[{{ app()->getLocale() }}]</small></label>
                <textarea id="description" name="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $category->description }}</textarea>
                @error('description')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Kép</label>
                <input type="file" id="image" name="image" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" onchange="previewImage(event)">
                @error('image')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
                <div class="mt-4">
                    <img id="imagePreview" src="{{ $category->image ? asset('storage/' . $category->image) : '' }}" alt="Image Preview" class="max-w-xs rounded-lg shadow-md {{ $category->image ? '' : 'hidden' }}">
                </div>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Mentés</button>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.classList.add('hidden');
            }
        }
    </script>
@endsection