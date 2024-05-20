<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Patient') }}
        </h2>
    </x-slot>

    <div class="mx-auto my-20 p-10 bg-white shadow w-10/12">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <div class="flex flex-wrap -mx-2">
            <div class="w-full md:w-1/2 px-2">
                <form action="{{ route('patients.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="hospitalRecordId" class="block text-sm font-medium text-gray-700">
                            Hospital Record ID
                        </label>
                        <input type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="hospitalRecordId" id="hospitalRecordId" placeholder="Enter Hospital Record ID">
                    </div>
                    <div class="mb-3">
                        <label for="caseNo" class="block text-sm font-medium text-gray-700">Case No</label>
                        <input type="number"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="caseNo" placeholder="Enter Case No">
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="firstName" id="firstName" placeholder="Enter First Name">
                    </div>
                    <div class="mb-3">
                        <label for="middleName" class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="middleName" id="middleName" placeholder="Enter Middle Name">
                    </div>
                </form>
            </div>
            <div class="w-full md:w-1/2 px-2">
                <form action="{{ route('patients.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="lastName" id="lastName" placeholder="Enter Last Name">
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="dateOfBirth" id="dob">
                    </div>
                    <button type="submit"
                        class="py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Submit</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
