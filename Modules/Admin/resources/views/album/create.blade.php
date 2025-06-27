@extends('admin::layouts.navigation')

@section('content')
    <div class="bg-white p-6 shadow-md sm:rounded-lg">
        <form method="POST" action="{{ route('admin.album.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Album neve <small>[{{ app()->getLocale() }}]</small><span class="text-red-700">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('name')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="slug" class="block mb-2 text-sm font-medium text-gray-900">Album slug <span class="text-red-700">*</span></label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('slug')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Státusz <span class="text-red-700">*</span></label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Aktív</option>
                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inaktív</option>
                </select>
                @error('status')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="position" class="block mb-2 text-sm font-medium text-gray-900">Pozíció <span class="text-red-700">*</span></label>
                <input type="number" id="position" name="position" value="{{ old('position') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('position')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Album leírása <small>[{{ app()->getLocale() }}]</small></label>
                <textarea id="description" name="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Kategória <span class="text-red-700">*</span></label>
                <select id="category_id" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="" disabled selected>Válassz kategóriát</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="cover_image" class="block mb-2 text-sm font-medium text-gray-900">Borító kép</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" onchange="previewCoverImage(event)">
                @error('cover_image')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
                <div id="coverImagePreview" class="mt-4 flex flex-wrap gap-4">
                    @if (old('cover_image'))
                        <img src="{{ old('cover_image') }}" class="max-w-xs rounded-lg shadow-md" style="max-height: 150px;">
                    @endif
                </div>
            </div>
            <div class="mb-6">
                <label for="images" class="block mb-2 text-sm font-medium text-gray-900">Képek feltöltése</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" onchange="previewImages(event)">
                @error('images')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
                <div id="imagePreviews" class="mt-4 flex flex-wrap gap-4"></div>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Mentés</button>
        </form>
    </div>

    <script>
        function previewImages(event) {
            const imagePreviews = document.getElementById('imagePreviews');
            imagePreviews.innerHTML = ''; // Clear previous previews
            const files = event.target.files;

            if (files) {
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('max-w-xs', 'rounded-lg', 'shadow-md');
                        img.style.maxHeight = '150px';
                        imagePreviews.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        function previewCoverImage(event) {
            const coverImagePreview = document.getElementById('coverImagePreview');
            coverImagePreview.innerHTML = ''; // Clear previous previews
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('max-w-xs', 'rounded-lg', 'shadow-md');
                    img.style.maxHeight = '150px';
                    coverImagePreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
