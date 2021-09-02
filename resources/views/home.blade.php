@extends('layouts.app')

@section('content')
    <div class="container-fluid pt-5">
        <h1 class="text-black-50">Welcome To File Converter (Encrypt & Decrypt)</h1>

        <div class="card mt-5">
            <div class="card-body">
                <form action="{{ route('file.convert') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file">Please Select File</label>
                                <input type="file" name="file" class="form-control-file btn btn-light" id="fileinput">
                            </div>
                        </div>
                        <div class="col-md-6 mt-3 pt-3">
                            <div class="form-group">
                                <button type='button' id='btnLoad' class="btn btn-light ml-4">Load File</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type='button' id='convert' class="btn btn-light ml-2">Convert FIle</button>
                    </div>
                </form>
                {{-- end of form --}}
            </div>
            {{-- end of card body --}}
            <div class="card-footer py-0 my-0">
                <h6 id="fileinfo"></h6>
                <p class="erorr"></p>
            </div>
        </div>
        {{-- end of row --}}
    </div>
@endsection


@section('scripts')
    <script>
        document.getElementById("btnLoad").addEventListener("click", function showFileSize() {
        // (Can't use `typeof FileReader === "function"` because apparently it
        // comes back as "object" on some browsers. So just see if it's there
        // at all.)
            if (!window.FileReader) { // This is VERY unlikely, browser support is near-universal
                console.log("The file API isn't supported on this browser yet.");
                return;
            }

            var input = document.getElementById('fileinput'); // get file input
            if (!input.files) { // This is VERY unlikely, browser support is near-universal
                console.error("This browser doesn't seem to support the `files` property of file inputs.");
            } else if (!input.files[0]) {
                addPara("Please select a file before clicking 'Load'");
            } else {
                var file = input.files[0];
                var extention = file.name.split('.').pop();
                addPara("\tFile Name: " + file.name +
                        "\tFile Size: " + (file.size)/1000 + " KBytes In Size "
                        + "\tFile Extension: " + extention);
            }
        }); // add event addEventListener to load button

    function addPara(info) {
        var fileinfo = document.getElementById("fileinfo");
        fileinfo.innerHTML = info;
    } // file info function


    $(document).ready(function(){

    // jQuery methods go here...
        $('#convert').on('click', function name(params) {
            var file = $('#fileinput').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                    type: 'POST',
                    url: "{{ route('file.convert') }}",
                    data: sendData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // getDocuments(response);
                        console.log(response);
                        // $('.items-from-ajax-load').fadeOut(250);
                    },
                    error: function() {
                        $('#erorr').text('error');
                    },
                    complete: function() {
                        $('#erorr').text('');
                    }
                });
        });
    });
    </script>
    {{-- end of script --}}

@endsection
