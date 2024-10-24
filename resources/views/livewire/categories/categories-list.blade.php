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

                            <tbody wire:sortable="updateOrder" class="bg-white divide-y divide-gray-200">
                            @if($categories->isNotEmpty() && $categories->count())
                                @foreach($categories as $category)
                                    <tr wire:sortable.item="{{ $category->id }}" wire:key="{{ $loop->index }}">
                                        <td class="px-6 py-4">
                                            <button
                                                wire:sortable.handle
                                                class="text-gray-400 cursor-move hover:text-gray-600">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 256 256">
                                                    <path fill="none" d="M0 0h256v256H0z"/>
                                                    <path fill="none" stroke="#000" stroke-linecap="round"
                                                          stroke-linejoin="round" stroke-width="16"
                                                          d="M156.3 203.7 128 232l-28.3-28.3M128 160v72M99.7 52.3 128 24l28.3 28.3M128 96V24M52.3 156.3 24 128l28.3-28.3M96 128H24M203.7 99.7 232 128l-28.3 28.3M160 128h72"/>
                                                </svg>
                                            </button>
                                        </td>

                                        {{-- Inline Edit Start --}}
                                        <td class="@if($editedCategoryId !== $category->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <x-text-input wire:model.live.debounce="name" id="name" class="py-2 pr-4 pl-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400" />
                                            @error('name')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="@if($editedCategoryId !== $category->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <x-text-input wire:model="slug" id="slug" class="py-2 pr-4 pl-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400" />
                                            @error('slug')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        {{-- Inline Edit End --}}

                                        {{-- Show Category Name/Slug Start --}}
                                        <td class="@if($editedCategoryId === $category->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $category->name }}
                                        </td>
                                        <td class="@if($editedCategoryId === $category->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $category->slug }}
                                        </td>
                                        {{-- Show Category Name/Slug End --}}

                                        <td class="px-6">
                                            <div class="inline-block relative mr-2 w-10 align-middle transition duration-200 ease-in select-none">
                                                <input wire:model="active.{{ $category->id }}" wire:click="toggleIsActive({{ $category->id }})" type="checkbox" name="toggle" id="{{ $loop->index.$category->id }}" class="block absolute w-6 h-6 bg-white rounded-full border-4 appearance-none cursor-pointer focus:outline-none toggle-checkbox" />
                                                <label for="{{ $loop->index.$category->id }}" class="block overflow-hidden h-6 bg-gray-300 rounded-full cursor-pointer toggle-label"></label>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            @if($editedCategoryId === $category->id)
                                                <x-primary-button wire:click="updateCategory({{ $category->id }})">
                                                    Save
                                                </x-primary-button>
                                                <x-primary-button wire:click="cancelCategoryEdit">
                                                    Cancel
                                                </x-primary-button>
                                            @else
                                                <x-primary-button wire:click="editCategory({{ $category->id }})">
                                                    Edit
                                                </x-primary-button>
                                                <button
                                                    wire:confirm
                                                    wire:click="delete({{  $category->id }})" class="px-4 py-2 text-xs text-red-500 uppercase bg-red-200 rounded-md border border-transparent hover:text-red-700 hover:bg-red-300">
                                                    Delete
                                                </button>
                                            @endif
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
                    {!! $links !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal Form -->
    <x-category-modal/>
</div>
