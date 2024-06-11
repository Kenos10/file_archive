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
            <!-- FTP Settings -->
            <div class="w-full lg:max-w-2xl">
                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold">FTP Settings</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('update.ftp.settings') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="ftp_host" class="block text-gray-700">FTP Host:</label>
                                <input type="text" id="ftp_host" name="ftp_host"
                                    value="{{ old('ftp_host', $ftpSetting->ftp_host ?? '') }}"
                                    class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="ftp_username" class="block text-gray-700">FTP Username:</label>
                                <input type="text" id="ftp_username" name="ftp_username"
                                    value="{{ old('ftp_username', $ftpSetting->ftp_username ?? '') }}"
                                    class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="ftp_password" class="block text-gray-700">FTP Password:</label>
                                <input type="password" id="ftp_password" name="ftp_password"
                                    class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Update FTP
                                Settings</button>
                        </form>
                    </div>
                </div>

                <!-- File Sequence Number -->
                <div class="w-full lg:max-w-2xl">
                    <div class="bg-white shadow-md rounded-lg mb-6">
                        <div class="bg-gray-100 px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold">File Number</h3>
                        </div>
                        <div class="px-6 py-4">
                            <form id="fileNumberForm" method="POST" action="/file-format">
                                @csrf
                                <div class="mb-4">
                                    <label for="prefix" class="block text-gray-700">Prefix:</label>
                                    <select id="prefix" name="prefix"
                                        class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="none">None</option>
                                        <option value="string">String</option>
                                        <option value="date">Date</option>
                                    </select>
                                </div>
                                <div id="prefixDetails" class="mb-4 hidden">
                                    <label for="prefixValue" class="block text-gray-700">Value:</label>
                                    <input type="text" id="prefixValue" name="prefix_value"
                                        class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                </div>
                                <div id="prefixDateDetails" class="mb-4 hidden">
                                    <div class="mt-2">
                                        <div>
                                            <input type="checkbox" id="prefixYearOnly" name="prefix_year_only"
                                                value="1" class="mr-1">
                                            <label for="prefixYearOnly" class="text-gray-700">Year Only</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="prefixMonthOnly" name="prefix_month_only"
                                                value="1" class="mr-1">
                                            <label for="prefixMonthOnly" class="text-gray-700">Month Only</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="prefixDayOnly" name="prefix_day_only"
                                                value="1" class="mr-1">
                                            <label for="prefixDayOnly" class="text-gray-700">Day Only</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <input type="radio" id="prefixFullYear" name="prefix_year_format"
                                                value="full" class="mr-1">
                                            <label for="prefixFullYear" class="text-gray-700">Full Year (e.g.,
                                                2024)</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="prefixShortYear" name="prefix_year_format"
                                                value="short" class="mr-1">
                                            <label for="prefixShortYear" class="text-gray-700">Short Year (e.g.,
                                                24)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="suffix" class="block text-gray-700">Suffix:</label>
                                    <select id="suffix" name="suffix"
                                        class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="none">None</option>
                                        <option value="string">String</option>
                                        <option value="date">Date</option>
                                    </select>
                                </div>
                                <div id="suffixDetails" class="mb-4 hidden">
                                    <label for="suffixValue" class="block text-gray-700">Value:</label>
                                    <input type="text" id="suffixValue" name="suffix_value"
                                        class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                </div>
                                <div id="suffixDateDetails" class="mb-4 hidden">
                                    <div class="mt-2">
                                        <div>
                                            <input type="checkbox" id="suffixYearOnly" name="suffix_year_only"
                                                value="1" class="mr-1">
                                            <label for="suffixYearOnly" class="text-gray-700">Year Only</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="suffixMonthOnly" name="suffix_month_only"
                                                value="1" class="mr-1">
                                            <label for="suffixMonthOnly" class="text-gray-700">Month Only</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="suffixDayOnly" name="suffix_day_only"
                                                value="1" class="mr-1">
                                            <label for="suffixDayOnly" class="text-gray-700">Day Only</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <input type="radio" id="suffixFullYear" name="suffix_year_format"
                                                value="full" class="mr-1">
                                            <label for="suffixFullYear" class="text-gray-700">Full Year (e.g.,
                                                2024)</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="suffixShortYear" name="suffix_year_format"
                                                value="short" class="mr-1">
                                            <label for="suffixShortYear" class="text-gray-700">Short Year (e.g.,
                                                24)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="autoNumber" class="block text-gray-700">Auto Number:</label>
                                    <input type="hidden" name="auto_number" value="0"> <!-- Add this line -->
                                    <input type="checkbox" id="autoNumber" name="auto_number" value="1"
                                        class="mr-2 leading-tight">
                                </div>
                                <div id="starterNumberDetails" class="mb-4 hidden">
                                    <label for="starterNumber" class="block text-gray-700">Starter Number:</label>
                                    <input type="number" id="starterNumber" name="starter_number"
                                        class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                </div>
                                <div class="mb-4">
                                    <label for="includeHyphens" class="block text-gray-700">Include Hyphens:</label>
                                    <input type="checkbox" id="includeHyphens" name="include_hyphens"
                                        class="mr-2 leading-tight">
                                </div>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md">Save</button>
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
                                    <label for="casePrefix" class="block text-gray-700">Prefix:</label>
                                    <select id="casePrefix" name="prefix"
                                        class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="none">None</option>
                                        <option value="string">String</option>
                                        <option value="date">Date</option>
                                    </select>
                                </div>
                                <div id="casePrefixDetails" class="mb-4 hidden">
                                    <label for="casePrefixValue" class="block text-gray-700">Value:</label>
                                    <input type="text" id="casePrefixValue" name="prefix_value"
                                        class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                </div>
                                <div id="casePrefixDateDetails" class="mb-4 hidden">
                                    <div class="mt-2">
                                        <div>
                                            <input type="checkbox" id="casePrefixYearOnly" name="prefix_year_only"
                                                value="1" class="mr-1">
                                            <label for="casePrefixYearOnly" class="text-gray-700">Year Only</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="casePrefixMonthOnly" name="prefix_month_only"
                                                value="1" class="mr-1">
                                            <label for="casePrefixMonthOnly" class="text-gray-700">Month Only</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="casePrefixDayOnly" name="prefix_day_only"
                                                value="1" class="mr-1">
                                            <label for="casePrefixDayOnly" class="text-gray-700">Day Only</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <input type="radio" id="casePrefixFullYear" name="prefix_year_format"
                                                value="full" class="mr-1">
                                            <label for="casePrefixFullYear" class="text-gray-700">Full Year (e.g.,
                                                2024)</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="casePrefixShortYear" name="prefix_year_format"
                                                value="short" class="mr-1">
                                            <label for="casePrefixShortYear" class="text-gray-700">Short Year (e.g.,
                                                24)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="caseSuffix" class="block text-gray-700">Suffix:</label>
                                    <select id="caseSuffix" name="suffix"
                                        class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="none">None</option>
                                        <option value="string">String</option>
                                        <option value="date">Date</option>
                                    </select>
                                </div>
                                <div id="caseSuffixDetails" class="mb-4 hidden">
                                    <label for="caseSuffixValue" class="block text-gray-700">Value:</label>
                                    <input type="text" id="caseSuffixValue" name="suffix_value"
                                        class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                </div>
                                <div id="caseSuffixDateDetails" class="mb-4 hidden">
                                    <div class="mt-2">
                                        <div>
                                            <input type="checkbox" id="caseSuffixYearOnly" name="suffix_year_only"
                                                value="1" class="mr-1">
                                            <label for="caseSuffixYearOnly" class="text-gray-700">Year Only</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="caseSuffixMonthOnly" name="suffix_month_only"
                                                value="1" class="mr-1">
                                            <label for="caseSuffixMonthOnly" class="text-gray-700">Month Only</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="caseSuffixDayOnly" name="suffix_day_only"
                                                value="1" class="mr-1">
                                            <label for="caseSuffixDayOnly" class="text-gray-700">Day Only</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <input type="radio" id="caseSuffixFullYear" name="suffix_year_format"
                                                value="full" class="mr-1">
                                            <label for="caseSuffixFullYear" class="text-gray-700">Full Year (e.g.,
                                                2024)</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="caseSuffixShortYear" name="suffix_year_format"
                                                value="short" class="mr-1">
                                            <label for="caseSuffixShortYear" class="text-gray-700">Short Year (e.g.,
                                                24)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="caseAutoNumber" name="auto_number" value="1"
                                            class="mr-2">
                                        <label for="caseAutoNumber" class="text-gray-700">Autonumber</label>
                                    </div>
                                    <div id="caseAutoNumberDetails" class="mt-2 hidden">
                                        <label for="caseStarterNumber" class="block text-gray-700">Starter
                                            Number:</label>
                                        <input type="number" id="caseStarterNumber" name="starter_number"
                                            min="0"
                                            class="block w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="caseIncludeHyphens" name="include_hyphens"
                                            value="1" class="mr-2">
                                        <label for="caseIncludeHyphens" class="text-gray-700">Include Hyphens</label>
                                    </div>
                                </div>
                                <div class="flex justify-start mb-2">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
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

                                toggleVisibility(document.getElementById("casePrefix"), "casePrefixDetails", "casePrefixDateDetails");
                                toggleVisibility(document.getElementById("caseSuffix"), "caseSuffixDetails", "caseSuffixDateDetails");

                                const autoNumber = document.getElementById("autoNumber");
                                const autoNumberDetails = document.getElementById("starterNumberDetails");
                                autoNumber.addEventListener("change", function() {
                                    autoNumberDetails.classList.toggle("hidden", !this.checked);
                                });

                                const caseAutoNumber = document.getElementById("caseAutoNumber");
                                const caseAutoNumberDetails = document.getElementById("caseAutoNumberDetails");
                                caseAutoNumber.addEventListener("change", function() {
                                    caseAutoNumberDetails.classList.toggle("hidden", !this.checked);
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
