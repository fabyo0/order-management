<div>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">{{ __('Products') }}</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-lg rounded-lg">
                <div class="p-6 bg-gray-50 border-b border-gray-300">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Manage Products</h3>
                        <a href="#"
                           class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                            Create Product
                        </a>
                    </div>

                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div class="relative col-span-1">
                                <input wire:model.live="searchColumns.name" type="text"
                                       placeholder="Search by name..."
                                       class="w-full text-sm rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pl-10"/>
                                <span class="absolute left-3 top-2.5 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round"
                                                                                         stroke-linejoin="round"
                                                                                         stroke-width="2"
                                                                                         d="M11 17a6 6 0 100-12 6 6 0 000 12zm0 0l4.5 4.5m-4.5-4.5H8"></path></svg>
                                </span>
                            </div>
                            <div>
                                <select wire:model.live="searchColumns.category_id"
                                        class="w-full text-sm rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option selected value="{{ null }}">{{ __('-- Choose category --') }}</option>
                                    @foreach($categories as $id => $category)
                                        <option value="{{ $id }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model.live="searchColumns.country_id"
                                        class="w-full text-sm rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option selected value="{{ null }}">{{ __('-- Choose country --') }}</option>
                                    @foreach($countries as $id => $country)
                                        <option value="{{ $id }}">{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <input wire:model.live.debounce="searchColumns.price.0" type="number" placeholder="From"
                                       class="w-56 text-sm rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
                                <input wire:model.live.debounce="searchColumns.price.1" type="number" placeholder="To"
                                       class="w-56 text-sm rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto mb-4 border border-gray-300 rounded-lg shadow-sm">
                        <table class="min-w-full bg-white divide-y divide-gray-300">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">
                                    <input type="checkbox"
                                           class="rounded focus:ring-indigo-500 focus:border-indigo-500">
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 text-sm uppercase">Name</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 text-sm uppercase">
                                    Categories
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 text-sm uppercase">Country
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Price</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" value="{{ $product->id }}" wire:model.live="selected"
                                               class="rounded focus:ring-indigo-500 focus:border-indigo-500">
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 text-sm">{{ $product->name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($product->categories as $category)
                                                <span
                                                    class="px-3 py-1 text-xs text-indigo-700 bg-indigo-200 rounded-full">{{ $category->name }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 text-sm">{{ $product->countryName }}</td>
                                    <td class="px-6 py-4 text-gray-800 text-sm">
                                        ${{ number_format($product->price / 100, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="#"
                                               class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-500 transition duration-150 ease-in-out shadow-md hover:shadow-lg">Edit</a>
                                            <button
                                                class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition duration-150 ease-in-out">
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
