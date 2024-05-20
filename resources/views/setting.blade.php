<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">File Sequence Number</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('update.starting.value') }}">
                            @csrf

                            <div class="form-group row mb-2">
                                <label for="starting_value" class="col-md-4 col-form-label text-md-right">Starting
                                    Value</label>

                                <div class="col-md-6">
                                    <input id="starting_value" type="number"
                                        class="form-control @error('starting_value') is-invalid @enderror"
                                        name="starting_value"
                                        placeholder="{{ $startingValue ? 'Current: ' . $startingValue : 'No starting value set' }}"
                                        required autocomplete="starting_value" autofocus>

                                    @error('starting_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Set
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        Drive Letter
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('update.storage.path') }}">
                            @csrf
                            <div class="form-group row mb-2">
                                <label for="storage_path" class="col-md-4 col-form-label text-md-right">Drive</label>
                                <div class="col-md-6">
                                    <select id="storage_path"
                                        class="form-control @error('storage_path') is-invalid @enderror"
                                        name="storage_path" required autofocus>
                                        @if ($storage)
                                            <option disabled selected>Current: {{ $storage->path }}</option>
                                        @else
                                            <option disabled selected>No storage path set</option>
                                        @endif
                                    </select>
                                    @error('storage_path')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Set</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var selectElement = document.getElementById("storage_path");
            var driveLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            for (var i = 0; i < driveLetters.length; i++) {
                var option = document.createElement("option");
                option.value = driveLetters[i] + ":/";
                option.text = driveLetters[i] + ":";
                selectElement.add(option);
            }
        });
    </script>
</x-app-layout>
