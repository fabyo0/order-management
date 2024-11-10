<div
    class="@if (!$this->showModal) hidden @endif fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg">
        <form wire:submit.prevent="save" class="w-full">
            <div class="p-6">
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Create Category</h3>
                    <svg wire:click.prevent="$set('showModal', false)"
                         class="w-6 h-6 text-gray-600 cursor-pointer hover:text-gray-800"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                        <path
                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
                    </svg>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="category.name">Name</label>
                    <input wire:model.live.debounce.500ms="name" id="name"
                           class="mt-2 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"/>
                    @error('name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="category.slug">Slug</label>
                    <input wire:model.live.debounce.500ms="slug" id="slug"
                           class="mt-2 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring focus:ring-blue-300"/>
                    @error('slug')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click.prevent="$set('showModal', false)"
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
