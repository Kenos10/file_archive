<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Patient') }}
        </h2>
    </x-slot>

    <div class="mx-auto my-20 p-10 bg-white shadow w-10/12">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form action="{{ route('patients.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/2 px-2">
                    <div class="mb-3">
                        <label for="hospitalRecordId" class="block text-sm font-medium text-gray-700">Hospital Record
                            ID</label>
                        <input type="text" name="hospitalRecordId" id="hospitalRecordId"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Enter Hospital Record ID">
                    </div>
                    <div class="mb-3">
                        <label for="caseNo" class="block text-sm font-medium text-gray-700">Case No</label>
                        <input type="number" name="caseNo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Enter Case No">
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="firstName" id="firstName"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Enter First Name">
                    </div>
                    <div class="mb-3">
                        <label for="middleName" class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" name="middleName" id="middleName"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Enter Middle Name">
                    </div>
                </div>
                <div class="w-full md:w-1/2 px-2">
                    <div class="mb-3">
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="lastName" id="lastName"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Enter Last Name">
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" name="dateOfBirth" id="dob"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <button type="submit"
                        class="py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Submit</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
