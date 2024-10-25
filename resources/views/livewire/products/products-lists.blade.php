<div>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-lg rounded-lg">
                <div class="p-6 bg-gray-100 border-b border-gray-200">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-700">Manage Products</h3>
                        <a href="#" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 transition-colors">
                            Create Product
                        </a>
                    </div>

                    <div class="overflow-x-auto mb-4 border border-gray-200 rounded-lg shadow-sm">
                        <table class="min-w-full bg-white divide-y divide-gray-200">
                            <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                    <input type="checkbox" class="rounded focus:ring-indigo-500 focus:border-indigo-500">
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 text-sm uppercase">
                                    Name
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 text-sm uppercase">
                                    Categories
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 text-sm uppercase">
                                    Country
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 text-sm uppercase">
                                    Price
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                    Actions
                                </th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-100">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" value="{{ $product->id }}" wire:model.live="selected" class="rounded focus:ring-indigo-500 focus:border-indigo-500">
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 text-sm">
                                        {{ $product->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($product->categories as $category)
                                                <span class="px-3 py-1 text-xs text-indigo-700 bg-indigo-200 rounded-full">{{ $category->name }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 text-sm">
                                        {{ $product->country->name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 text-sm">
                                        ${{ number_format($product->price / 100, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="#" class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-400 transition-colors">
                                                Edit
                                            </a>
                                            <button class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $products->links(data:(['scrollTo' => false])) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
