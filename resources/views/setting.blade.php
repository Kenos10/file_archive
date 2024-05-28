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

                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold">Storage</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('update.storage.path') }}">
                            @csrf
                            <div class="mb-4">
                                <input id="storage_path"
                                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('storage_path') border-red-500 @enderror"
                                    type="text" name="storage_path" required autofocus
                                    value="{{ $storage ? $storage->path : '' }}"
                                    placeholder="{{ $storage ? $storage->path : 'No storage path set' }}">
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


                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold">Case Number</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('update.case.number.format') }}">
                            @csrf
                            <div class="mb-4">
                                <input id="case_number_format" type="text"
                                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('case_number_format') border-red-500 @enderror"
                                    name="case_number_format" placeholder="Enter date format (e.g., DD-MM-YYYY)"
                                    required autocomplete="off">
                                @error('case_number_format')
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
</x-app-layout>Z
