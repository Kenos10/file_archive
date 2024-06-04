<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-8">
        <div class="alert-container w-10/12 mx-auto">
            @if ($message = Session::get('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="flex flex-col lg:flex-row lg:justify-center">
            <!-- File Sequence Number -->
            <div class="w-full lg:max-w-2xl">
                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold">File Sequence Number</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('update.starting.value') }}">
                            @csrf
                            <div class="mb-4">
                                <input id="starting_value" type="number" name="starting_value" class="w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('starting_value') border-red-500 @enderror" placeholder="{{ $startingValue ? 'Current: ' . $startingValue : 'No starting value set' }}" required autocomplete="starting_value" autofocus>
                                @error('starting_value')
                                    <span class="text-red-500 text-sm mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex justify-start">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Set</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Storage -->
                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold">Storage</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('update.storage.path') }}">
                            @csrf
                            <div class="mb-4">
                                <input id="storage_path" type="text" name="storage_path" class="w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('storage_path') border-red-500 @enderror" value="{{ $storage ? $storage->path : '' }}" placeholder="{{ $storage ? $storage->path : 'No storage path set' }}" required autofocus>
                                @error('storage_path')
                                    <span class="text-red-500 text-sm mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex justify-start">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Set</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Case Number -->
                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold">Case Number</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form id="caseNumberForm" method="POST" action="/case-format">
                            @csrf
                            <div class="mb-4">
                                <label for="prefix" class="block text-gray-700">Prefix:</label>
                                <select id="prefix" name="prefix" class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="none">None</option>
                                    <option value="string">String</option>
                                    <option value="date">Date</option>
                                </select>
                            </div>
                            <div id="prefixDetails" class="mb-4 hidden">
                                <label for="prefixValue" class="block text-gray-700">Value:</label>
                                <input type="text" id="prefixValue" name="prefix_value" class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>
                            <div id="prefixDateDetails" class="mb-4 hidden">
                                <div class="mt-2">
                                    <div>
                                        <input type="checkbox" id="prefixYearOnly" name="prefix_year_only" value="1" class="mr-1">
                                        <label for="prefixYearOnly" class="text-gray-700">Year Only</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="prefixMonthOnly" name="prefix_month_only" value="1" class="mr-1">
                                        <label for="prefixMonthOnly" class="text-gray-700">Month Only</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="prefixDayOnly" name="prefix_day_only" value="1" class="mr-1">
                                        <label for="prefixDayOnly" class="text-gray-700">Day Only</label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div>
                                        <input type="radio" id="prefixFullYear" name="prefix_year_format" value="full" class="mr-1">
                                        <label for="prefixFullYear" class="text-gray-700">Full Year (e.g., 2024)</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="prefixShortYear" name="prefix_year_format" value="short" class="mr-1">
                                        <label for="prefixShortYear" class="text-gray-700">Short Year (e.g., 24)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="suffix" class="block text-gray-700">Suffix:</label>
                                <select id="suffix" name="suffix" class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="none">None</option>
                                    <option value="string">String</option>
                                    <option value="date">Date</option>
                                </select>
                            </div>
                            <div id="suffixDetails" class="mb-4 hidden">
                                <label for="suffixValue" class="block text-gray-700">Value:</label>
                                <input type="text" id="suffixValue" name="suffix_value" class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>
                            <div id="suffixDateDetails" class="mb-4 hidden">
                                <div class="mt-2">
                                    <div>
                                        <input type="checkbox" id="suffixYearOnly" name="suffix_year_only" value="1" class="mr-1">
                                        <label for="suffixYearOnly" class="text-gray-700">Year Only</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="suffixMonthOnly" name="suffix_month_only" value="1" class="mr-1">
                                        <label for="suffixMonthOnly" class="text-gray-700">Month Only</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="suffixDayOnly" name="suffix_day_only" value="1" class="mr-1">
                                        <label for="suffixDayOnly" class="text-gray-700">Day Only</label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div>
                                        <input type="radio" id="suffixFullYear" name="suffix_year_format" value="full" class="mr-1">
                                        <label for="suffixFullYear" class="text-gray-700">Full Year (e.g., 2024)</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="suffixShortYear" name="suffix_year_format" value="short" class="mr-1">
                                        <label for="suffixShortYear" class="text-gray-700">Short Year (e.g., 24)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="autoNumber" name="auto_number" value="1" class="mr-2">
                                    <label for="autoNumber" class="text-gray-700">Autonumber</label>
                                </div>
                                <div id="autoNumberDetails" class="mt-2 hidden">
                                    <label for="starterNumber" class="block text-gray-700">Starter Number:</label>
                                    <input type="number" id="starterNumber" name="starter_number" min="0" class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="includeHyphens" name="include_hyphens" value="1" class="mr-2">
                                    <label for="includeHyphens" class="text-gray-700">Include Hyphens</label>
                                </div>
                            </div>
                            <div class="flex justify-start mb-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <x-slot name="scripts">
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const toggleVisibility = (selectElement, detailsId, dateDetailsId) => {
                                selectElement.addEventListener("change", function() {
                                    const details = document.getElementById(detailsId);
                                    const dateDetails = document.getElementById(dateDetailsId);
                                    if (this.value === "string") {
                                        details.classList.remove("hidden");
                                        dateDetails.classList.add("hidden");
                                    } else if (this.value === "date") {
                                        details.classList.add("hidden");
                                        dateDetails.classList.remove("hidden");
                                    } else {
                                        details.classList.add("hidden");
                                        dateDetails.classList.add("hidden");
                                    }
                                });
                            };

                            toggleVisibility(document.getElementById("prefix"), "prefixDetails", "prefixDateDetails");
                            toggleVisibility(document.getElementById("suffix"), "suffixDetails", "suffixDateDetails");

                            document.getElementById("autoNumber").addEventListener("change", function() {
                                const autoNumberDetails = document.getElementById("autoNumberDetails");
                                autoNumberDetails.classList.toggle("hidden", !this.checked);
                            });

                            document.getElementById("caseNumberForm").addEventListener("submit", function(event) {
                                event.preventDefault();
                                this.submit();
                            });
                        });

                        function formatDate(date, type) {
                            const year = date.slice(0, 4);
                            const month = date.slice(5, 7);
                            const day = date.slice(8, 10);
                            const yearFormat = document.querySelector(`input[name="${type}YearFormat"]:checked`).value;
                            let formattedDate = "";

                            if (document.getElementById(`${type}YearOnly`).checked) {
                                formattedDate += yearFormat === 'short' ? year.slice(2) : year;
                            }
                            if (document.getElementById(`${type}MonthOnly`).checked) {
                                formattedDate += (formattedDate && document.getElementById('includeHyphens').checked ? '-' : '') + month;
                            }
                            if (document.getElementById(`${type}DayOnly`).checked) {
                                formattedDate += (formattedDate && document.getElementById('includeHyphens').checked ? '-' : '') + day;
                            }

                            return formattedDate;
                        }
                    </script>
                </x-slot>
            </div>
        </div>
    </div>
</x-app-layout>
