<x-app-layout :title="'Transaction Detail'">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transaction Detail') }}
            </h2>
        </div>
    </header>

    <div class="py-12">
        @include('layouts.return_info')
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Transaction #{{ $transaction->id }}</h2>
                    <p class="mb-4"><strong>User:</strong> {{ $transaction->user->name }}</p>
                    <p class="mb-4"><strong>Date:</strong> {{ $transaction->created_at }}</p>
                    <p class="mb-4"><strong>Total:</strong> {{ "Rp " . number_format($transaction->total, 0, ",", "."); }}</p>
                    <p class="mb-4"><strong>Payment Method:</strong> {{ ucfirst($transaction->payment_method) }}</p>
                    <div class="flex items-center mb-4">
                        <p class="mr-4"><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                        @if(Auth::user()->role == 'admin')
                        <button type="button" onclick="openEditStatusModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Edit Status
                        </button>
                        @endif
                    </div>
                    @if(Auth::user()->role == 'admin')
                        <!-- Edit Status Modal -->
                        <div id="editStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Transaction Status</h3>
                                    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-4">
                                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                            <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                {{-- 'pending', 'completed', 'failed' --}}
                                                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                            </select>
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="closeEditStatusModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                                Cancel
                                            </button>
                                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                                Update Status
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            function openEditStatusModal() {
                                document.getElementById('editStatusModal').classList.remove('hidden');
                            }
                            
                            function closeEditStatusModal() {
                                document.getElementById('editStatusModal').classList.add('hidden');
                            }
                        </script>
                    @endif

                    <!-- Transaction Detail Table -->
                    <table class="w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaction->details as $detail)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ "Rp " . number_format($detail->price, 0, ",", "."); }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ "Rp " . number_format($detail->quantity * $detail->price, 0, ",", "."); }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End of Transaction Detail Table -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>