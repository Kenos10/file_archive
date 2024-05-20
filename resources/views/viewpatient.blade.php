<x-app-layout>
    <div class="container">
        <h1 class="my-4">Patient Details</h1>

        <div class="alert-container">
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

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $patient->firstName }} {{ $patient->middleName }} {{ $patient->lastName }}</h5>
                <p class="card-text">
                    <strong>Hospital Record ID:</strong> {{ $patient->hospitalRecordId }}<br>
                    <strong>Case No:</strong> {{ $patient->caseNo }}<br>
                    <strong>Date of Birth:</strong> {{ $patient->dateOfBirth }}<br>
                    <strong>Zip Password:</strong> {{ $decryptedPassword }}
                </p>

                <hr>

                <div class="form-group">
                    <label>Drag and Drop Files Here</label>

                    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data"
                        class="dropzone" id="myDragAndDropUploader">
                        @csrf
                        <input type="hidden" class="form-control" name="hospitalRecordId" id="hospitalRecordId"
                            readonly value="{{ $patient->hospitalRecordId }}">
                    </form>

                    <h5 id="message"></h5>
                </div>

                {{-- <form action="{{ route('files.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" name="hospitalRecordId" id="hospitalRecordId" readonly
                        value="{{ $patient->hospitalRecordId }}">
                    <div class="form-group">
                        <label for="file">File</label>
                        <input type="file" name="file" class="dropzone" id="file">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block mt-3">Upload File</button>
                    </div>
                </form> --}}
            </div>
        </div>

        <hr>

        <div class="card my-2">
            <div class="card-body">
                <h5 class="card-title">Patient Records</h5>
                <form action="{{ route('archive.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="bi bi-archive-fill"></i> Zip</button>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>File No</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @foreach ($files as $file)
                                <tr>
                                    <td><input type="checkbox" name="files[]" value="{{ $file->id }}"></td>
                                    <td>{{ $file->fileNo }}</td>
                                    <td><a href="{{ route('view.file', $file->id) }}"> <i
                                                class="bi bi-eye fs-1"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        <div class="card my-2">
            <div class="card-body">
                <h5 class="card-title">Zip Files</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($zipfile as $zip)
                                <tr>
                                    <td>{{ $zip->created_at->format('F j, Y, h:i:s a') }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Zip Actions">
                                            <a class="btn btn-primary" href="{{ route('zip', $zip->id) }}">Download</a>
                                            <a class="btn btn-secondary"
                                                href="{{ route('store.zip', $zip->id) }}">Archive</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<x-app-layout>
    <script>
        var maxFilesizeVal = 12; // MB
        var maxFilesVal = 5;

        Dropzone.options.myDragAndDropUploader = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: maxFilesizeVal, // MB
            maxFiles: maxFilesVal,
            acceptedFiles: ".jpeg,.jpg,.png,.webp,.csv,.txt,.xlsx,.xls,.ppt,.pptx,.doc,.docx,.pdf",
            addRemoveLinks: false,
            timeout: 60000,
            dictDefaultMessage: "Drop your files here or click to upload",
            dictFallbackMessage: "Your browser doesn't support drag and drop file uploads.",
            dictFileTooBig: "File is too big. Max filesize: " + maxFilesizeVal + "MB.",
            dictInvalidFileType: "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.",
            dictMaxFilesExceeded: "You can only upload up to " + maxFilesVal + " files.",
            maxfilesexceeded: function(file) {
                this.removeFile(file);
            },
            sending: function(file, xhr, formData) {
                document.getElementById('message').innerText = 'File Uploading...';
                // Append the hospitalRecordId to the form data
                formData.append('hospitalRecordId', document.getElementById('hospitalRecordId').value);
            },
            success: function(file, response) {
                document.getElementById('message').innerText = 'File has been uploaded successfully.';
                // console.log(response);
                // Refresh the page after successful upload
                setTimeout(function() {
                    location.reload();
                }, 1000); // Optional delay for user feedback
            },
            error: function(file, response) {
                document.getElementById('message').innerText = 'Something went wrong: ' + response;
                // console.log(response);
            }
        };
    </script>
</x-app-layout>
