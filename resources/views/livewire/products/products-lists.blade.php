<div>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">{{ __('Products') }}</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-md rounded-lg">
                <div class="p-6 bg-gray-50 border-b border-gray-300">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Manage Products</h3>
                        <a href="#"
                           class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Create Product') }}
                        </a>
                    </div>

                    @if(count($selected) > 0)
                        <div class="flex items-center justify-start mb-4">
                            <button type="button"
                                    wire:click="deleteConfirm('deleteSelected')"
                                    wire:loading.attr="disabled"
                                    @disabled(! $this->selectedCount)
                                    class="px-4 py-2 text-xs font-semibold text-red-600 uppercase bg-red-100 rounded-md hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Delete Selected
                            </button>
                        </div>
                    @endif

                    <!-- Filtreler ve arama alanları -->
                    <div class="mb-6">


                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div class="relative col-span-1">
                                <input wire:model.live.500ms="searchColumns.name" type="text"
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
                                        id="selectCategory"
                                        class="w-full text-sm rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option selected value="{{ null }}">{{ __('-- Choose Category --') }}</option>
                                    @foreach($categories as $id => $category)
                                        <option value="{{ $id }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model.live="searchColumns.country_id"
                                        id="selectCountry"
                                        class="w-full text-sm rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option selected value="{{ null }}">{{ __('-- Choose Country --') }}</option>
                                    @foreach($countries as $id => $country)
                                        <option value="{{ $id }}">{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <input wire:model.live.debounce="searchColumns.price.0" type="number" placeholder="From"
                                       class="w-full text-sm rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
                                <input wire:model.live.debounce="searchColumns.price.1" type="number" placeholder="To"
                                       class="w-full text-sm rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
                            </div>
                        </div>


                        <div class="mb-2 space-x-2">
                            <!-- Export xls/csv/pdf -->
                            <button
                                wire:click="export('csv')"
                                class="px-4 py-2 text-sm font-semibold text-white bg-red-500 rounded-lg hover:bg-red-600 transition duration-150 ease-in-out shadow-md">
                                Csv
                            </button>
                            <button
                                wire:click="export('xlsx')"
                                class="px-4 py-2 text-sm font-semibold text-white bg-green-500 rounded-lg hover:bg-green-600 transition duration-150 ease-in-out shadow-md">
                                Xlsx
                            </button>
                            <button
                                wire:click="export('pdf')"
                                class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-150 ease-in-out shadow-md">
                                Pdf
                            </button>
                        </div>



                    </div>

                    <!-- Ürün tablosu -->
                    <div class="overflow-x-auto mb-4 border border-gray-300 rounded-lg shadow-sm">
                        <table class="min-w-full bg-white divide-y divide-gray-300">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left bg-gray-50">
                                    <input type="checkbox" wire:model.live="selectAll"
                                           class="rounded focus:ring-indigo-500 focus:border-indigo-500">
                                </th>
                                <th wire:click="sortByColumn('products.name')" class="px-6 py-3 text-left bg-gray-50">
                                    <span class="text-xs font-medium tracking-wider leading-4 text-gray-500 uppercase">Name</span>
                                    @if ($sortColumn == 'products.name')
                                        @include('svg.sort-' . $sortDirection)
                                    @else
                                        @include('svg.sort')
                                    @endif
                                </th>
                                <th class="px-6 py-3 text-left bg-gray-50">
                                    <span class="text-xs font-medium tracking-wider leading-4 text-gray-500 uppercase">Categories</span>
                                </th>
                                <th wire:click="sortByColumn('countryName')" class="px-6 py-3 text-left bg-gray-50">
                                    <span class="text-xs font-medium tracking-wider leading-4 text-gray-500 uppercase">Country</span>
                                    @if ($sortColumn == 'countryName')
                                        @include('svg.sort-' . $sortDirection)
                                    @else
                                        @include('svg.sort')
                                    @endif
                                </th>
                                <th wire:click="sortByColumn('price')" class="px-6 py-3 w-32 text-left bg-gray-50">
                                    <span class="text-xs font-medium tracking-wider leading-4 text-gray-500 uppercase">Price</span>
                                    @if ($sortColumn == 'price')
                                        @include('svg.sort-' . $sortDirection)
                                    @else
                                        @include('svg.sort')
                                    @endif
                                </th>
                                <th class="px-6 py-3 text-left bg-gray-50">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4">
                                        <input type="checkbox"
                                               value="{{ $product->id }}"
                                               wire:model.live="selected"
                                               class="rounded focus:ring-indigo-500 focus:border-indigo-500"
                                        >
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
                                               class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-500 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                                                Edit
                                            </a>
                                            <button wire:click="deleteConfirm('delete', {{ $product->id }})"
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
