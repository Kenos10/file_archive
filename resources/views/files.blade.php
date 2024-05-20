<x-app-layout>
    <x-slot name="header">
        <div class="flex w-100 justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                {{ __('Files') }}
            </h2>

            <form method="GET" action="{{ route('files.index') }}" class="flex space-x-2">
                <div class="w-full max-w-xs">
                    <input type="text" name="search"
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                        placeholder="Search">
                </div>
                <div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="table-responsive">
        <table class="table table-striped table-hover my-5">
            <thead>
                <tr>
                    <th scope="col">
                        Hospital Record ID</th>
                    <th scope="col">
                        File No</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($files as $file)
                    <tr class="fs-5 align-middle">
                        <td>{{ $file->hospitalRecordId }}</td>
                        <td>{{ $file->fileNo }}</td>
                        <td>
                            <a href="{{ route('patients.show', $file->hospitalRecordId) }}">
                                <i class="bi bi-eye fs-1"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $files->links() }}
</x-app-layout>
