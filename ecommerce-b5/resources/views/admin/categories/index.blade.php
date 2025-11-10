<x-app-layout>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product Category') }}
            </h2>
            <button onclick="openAddModal()" class="text-white bg-blue-600 px-4 py-2 rounded-md">Add New Category</button>
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
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Product</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stock</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->products_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->total_stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ "Rp " . number_format($category->total_price, 0, ",", "."); }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openEditModal({{ $category->id }}, '{{ old('name', $category->name) }}', '{{ old('description', $category->description) }}')" class="text-white mr-3 bg-indigo-600 p-1 rounded-md">Edit</button>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline delete-category-form" data-category-id="{{ $category->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openDeleteCategoryModal({{ $category->id }})" class="text-white mr-3 bg-red-600 p-1 rounded-md">Delete</button>
                                    </form>
                                    <!-- Delete Category Confirmation Modal -->
                                    <div id="deleteCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                                        <div class="flex justify-center items-center min-h-screen">
                                            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                                                <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
                                                <p class="mb-4">Are you sure you want to delete this category?</p>
                                                <div class="flex justify-end space-x-2">
                                                    <button type="button" onclick="closeDeleteCategoryModal()" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                                                    <button type="button" onclick="submitDeleteCategoryForm()" class="px-4 py-2 bg-red-600 text-white rounded-md">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex justify-center items-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-lg font-semibold mb-4">Add New Category</h3>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex justify-center items-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-lg font-semibold mb-4">Edit Category</h3>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="editName" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="editDescription" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id, name, description) {
            document.getElementById('editForm').action = `/dashboard/categories/${id}`;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        let deleteCategoryId = null;
        function openDeleteCategoryModal(categoryId) {
            deleteCategoryId = categoryId;
            document.getElementById('deleteCategoryModal').classList.remove('hidden');
        }
        function closeDeleteCategoryModal() {
            deleteCategoryId = null;
            document.getElementById('deleteCategoryModal').classList.add('hidden');
        }
        function submitDeleteCategoryForm() {
            if (deleteCategoryId) {
                const form = document.querySelector(`form.delete-category-form[data-category-id='${deleteCategoryId}']`);
                if (form) form.submit();
            }
            closeDeleteCategoryModal();
        }
    </script>
</x-app-layout>
