@extends('admin::layouts.navigation')

@section('content')
    <div class="bg-white p-6 shadow-md sm:rounded-lg">
        <form method="POST" action="{{ route('admin.album.update', $album->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Album neve <small>[{{ app()->getLocale() }}]</small><span class="text-red-700">*</span></label>
                <input type="text" id="name" name="name" value="{{ $album->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('name')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="slug" class="block mb-2 text-sm font-medium text-gray-900">Album slug <span class="text-red-700">*</span></label>
                <input type="text" id="slug" name="slug" value="{{ $album->slug }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('slug')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Album leírása <small>[{{ app()->getLocale() }}]</small></label>
                <textarea id="description" name="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $album->description }}</textarea>
                @error('description')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Kategória</label>
                <select id="category" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="" disabled>Válassz kategóriát</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $album->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-6">
                <label for="cover_image" class="block mb-2 text-sm font-medium text-gray-900">Borító kép</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" onchange="previewCoverImage(event)">
                @error('cover_image')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
                <div id="coverImagePreview" class="mt-4 flex flex-wrap gap-4">
                    @if ($album->cover_image)
                        <img src="{{ asset('storage/' . $album->cover_image) }}" class="max-w-xs rounded-lg shadow-md" style="max-height: 150px;">
                    @endif
                </div>
            </div>
            <div class="mb-6">
                <label for="images" class="block mb-2 text-sm font-medium text-gray-900">Képek feltöltése</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" onchange="previewImages(event)">
                @error('images')
                    <div class="text-red-700">{{ $message }}</div>
                @enderror
                <div id="existingImages" class="mt-4 flex flex-wrap gap-4">
                    @foreach ($images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image) }}" alt="Existing Image" class="max-w-xs rounded-lg shadow-md">
                            <button type="button" class="absolute top-0 right-0 bg-red-600 text-white p-3" onclick="removeImage('{{ $image }}')">X</button>
                        </div>
                    @endforeach
                </div>
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

        function removeImage(imagePath) {
            if (confirm('Biztosan törölni szeretnéd ezt a képet?')) {
                fetch('{{ route('admin.album.image.remove') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ image_path: imagePath })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert(data.message);
                    }
                });
            }
        }
    </script>
@endsection