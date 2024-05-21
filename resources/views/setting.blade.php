<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-8">
        <div class="flex lg:ps-[10%]">
            <div class="w-full max-w-2xl">
                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold">File Sequence Number</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('update.starting.value') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="starting_value" class="block text-gray-700 font-medium mb-2">Starting
                                    Value</label>
                                <input id="starting_value" type="number"
                                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('starting_value') border-red-500 @enderror"
                                    name="starting_value"
                                    placeholder="{{ $startingValue ? 'Current: ' . $startingValue : 'No starting value set' }}"
                                    required autocomplete="starting_value" autofocus>
                                @error('starting_value')
                                    <span class="text-red-500 text-sm mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex justify-start">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Set
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold">Drive Letter</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('update.storage.path') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="storage_path" class="block text-gray-700 font-medium mb-2">Drive</label>
                                <select id="storage_path"
                                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('storage_path') border-red-500 @enderror"
                                    name="storage_path" required autofocus>
                                    @if ($storage)
                                        <option disabled selected>Current: {{ $storage->path }}</option>
                                    @else
                                        <option disabled selected>No storage path set</option>
                                    @endif
                                </select>
                                @error('storage_path')
                                    <span class="text-red-500 text-sm mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex justify-start">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Set
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
