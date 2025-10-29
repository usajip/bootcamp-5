<x-app-layout>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Product') }}
            </h2>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-[800px] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">Name:</label>
                            <input type="text" name="name" id="name" value="{{ $product->name }}" class="w-full border border-gray-300 p-2 rounded-md" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">Description:</label>
                            <textarea name="description" id="description" class="w-full border border-gray-300 p-2 rounded-md" required>{{ $product->description }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="stock" class="block text-gray-700">Stock:</label>
                            <input type="number" name="stock" id="stock" value="{{ $product->stock }}" class="w-full border border-gray-300 p-2 rounded-md" min="0" required>
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-gray-700">Price:</label>
                            <input type="text" name="price" id="price" value="{{ $product->price }}" class="w-full border border-gray-300 p-2 rounded-md" placeholder="Rp 0" required autocomplete="off">
                        </div>
                        {{-- show current product image --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Current Image:</label>
                            <img id="current-image" src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover mb-4">
                            <img id="preview-image" src="#" alt="Image Preview" class="w-32 h-32 object-cover mb-4 hidden" />
                        </div>
                        <div class="mb-4">
                            <label for="image" class="block text-gray-700">Image:</label>
                            <input type="file" name="image" id="image" accept="image/*" class="w-full border border-gray-300 p-2 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="product_category_id" class="block text-gray-700">Category:</label>
                            <select name="product_category_id" id="product_category_id" class="w-full border border-gray-300 p-2 rounded-md" required>
                                <option value="" disabled selected>Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->product_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="text-white bg-blue-600 px-4 py-2 rounded-md">Create Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/format-rupiah.js"></script>
    <script>
        document.getElementById('image').addEventListener('change', function(event) {
            const [file] = event.target.files;
            const preview = document.getElementById('preview-image');
            const current = document.getElementById('current-image');
            if (file) {
                if (file.size > 1048576) { // 1MB in bytes
                    alert('File size must be less than 1MB.');
                    event.target.value = '';
                    preview.src = '#';
                    preview.classList.add('hidden');
                    current.classList.remove('hidden');
                    return;
                }
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
                current.classList.add('hidden');
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
                current.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>