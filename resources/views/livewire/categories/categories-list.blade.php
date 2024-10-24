<div>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-gray-800">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-md rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <x-primary-button wire:click="openModal" class="mb-6">
                        {{ __('Add Category') }}
                    </x-primary-button>

                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full border divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 w-10 text-left">
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-semibold tracking-wide text-gray-600 uppercase">{{ __('Name') }}</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-semibold tracking-wide text-gray-600 uppercase">{{ __('Slug') }}</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-semibold tracking-wide text-gray-600 uppercase">{{ __('Active') }}</span>
                                </th>
                                <th class="px-6 py-3 w-56">
                                </th>
                            </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                            @if($categories->isNotEmpty())
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <button class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 256 256">
                                                    <path fill="none" d="M0 0h256v256H0z"/>
                                                    <path fill="none" stroke="#000" stroke-linecap="round"
                                                          stroke-linejoin="round" stroke-width="16"
                                                          d="M156.3 203.7 128 232l-28.3-28.3M128 160v72M99.7 52.3 128 24l28.3 28.3M128 96V24M52.3 156.3 24 128l28.3-28.3M96 128H24M203.7 99.7 232 128l-28.3 28.3M160 128h72"/>
                                                </svg>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            {{ $category->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            {{ $category->slug }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="relative inline-block w-10 align-middle">
                                                <input type="checkbox" name="toggle"
                                                       class="toggle-checkbox block absolute w-6 h-6 bg-white rounded-full border-4 cursor-pointer focus:outline-none"/>
                                                <label for="toggle"
                                                       class="block overflow-hidden h-6 bg-gray-300 rounded-full cursor-pointer toggle-label"></label>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            <x-primary-button>
                                                {{ __('Edit') }}
                                            </x-primary-button>
                                            <button
                                                class="ml-2 px-4 py-2 text-xs text-red-600 uppercase bg-red-200 rounded-md border border-transparent hover:bg-red-300">
                                                {{ __('Delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('No categories found.') }}
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    {!! $categories->links(data: ['scrollTo' => false]) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal Form -->
    <div class="@if (!$this->categoryForm->showModal) hidden @endif fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75">
        <div class="w-full max-w-lg bg-white rounded-lg shadow-lg">
            <form wire:submit.prevent="save" class="w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center border-b pb-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Create Category</h3>
                        <svg wire:click.prevent="$set('showModal', false)"
                             class="w-6 h-6 text-gray-600 cursor-pointer hover:text-gray-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z" />
                        </svg>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="category.name">Name</label>
                        <input wire:model.live.debounce.500ms="categoryForm.name" id="name"
                               class="mt-2 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" />
                        @error('categoryForm.name')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="category.slug">Slug</label>
                        <input wire:model.live.debounce.500ms="categoryForm.slug" id="slug"
                               class="mt-2 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" />
                        @error('categoryForm.slug')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click.prevent="$set('categoryForm.showModal', false)"
                                class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                            Close
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded hover:bg-blue-600">
                            Create
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
