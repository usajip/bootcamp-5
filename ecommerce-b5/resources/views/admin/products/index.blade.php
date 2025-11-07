<x-app-layout>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}" class="text-white bg-blue-600 px-4 py-2 rounded-md">Add New Product</a>
        </div>
    </header>

    <div class="py-12">
        @include('layouts.return_info')
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Category Table -->
                    <table class="w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ "Rp " . number_format($product->price, 0, ",", "."); }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-white mr-3 bg-indigo-600 p-1 rounded-md">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline delete-form" data-product-id="{{ $product->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openDeleteModal({{ $product->id }})" class="text-white mr-3 bg-red-600 p-1 rounded-md">Delete</button>
                                    </form>
                                    <!-- Delete Confirmation Modal -->
                                    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                                        <div class="flex justify-center items-center min-h-screen">
                                            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                                                <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
                                                <p class="mb-4">Are you sure you want to delete this product?</p>
                                                <div class="flex justify-end space-x-2">
                                                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                                                    <button type="button" onclick="submitDeleteForm()" class="px-4 py-2 bg-red-600 text-white rounded-md">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        let deleteFormId = null;
                                        function openDeleteModal(productId) {
                                            deleteFormId = productId;
                                            document.getElementById('deleteModal').classList.remove('hidden');
                                        }
                                        function closeDeleteModal() {
                                            deleteFormId = null;
                                            document.getElementById('deleteModal').classList.add('hidden');
                                        }
                                        function submitDeleteForm() {
                                            if (deleteFormId) {
                                                const form = document.querySelector(`form.delete-form[data-product-id='${deleteFormId}']`);
                                                if (form) form.submit();
                                            }
                                            closeDeleteModal();
                                        }
                                    </script>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>