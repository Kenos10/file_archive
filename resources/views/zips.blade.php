<x-app-layout>
    <x-slot name="header">
        <div class="flex w-100 justify-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Zips') }}
            </h2>
            <form method="GET" action="{{ route('archives.index') }}" class="flex space-x-2 align-items-center">
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

    <div class="alert-container my-2">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover my-5">
            <thead>
                <tr>
                    <th scope="col">
                        Hospital Record ID</th>
                    <th scope="col">
                        Date Zip</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($zips as $zip)
                    <tr class="fs-5 align-middle">
                        <td>{{ $zip->hospitalRecordId }}</td>
                        <td>{{ $zip->created_at->format('F j, Y, h:i:s a') }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Zip Actions">
                                <a class="btn btn-primary" href="{{ route('zip', $zip->id) }}">Download</a>
                                <a class="btn btn-secondary" href="{{ route('store.zip', $zip->id) }}">Archive</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $zips->links() }}
</x-app-layout>
