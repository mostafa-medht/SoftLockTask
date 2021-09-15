@extends('layouts.app')

@section('content')
    <div class="container-fluid pt-5">
        <h1 class="text-black-50">Welcome To File Converter (Encrypt & Decrypt)</h1>

        <div class="card mt-5">
            <div class="card-body">
                <form id="fileUpload" method="POST" enctype="multipart/form-data">
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
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group form-check">
                                <input type="checkbox" name="encrypt" class="form-check-input" id="encrypt">
                                <label class="form-check-label" for="encrypt">Encrypt File</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-check">
                                <input type="checkbox" name="decrypt" class="form-check-input" id="decrypt">
                                <label class="form-check-label" for="decrypt">Decrypt File</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <button type='button' id='convert' class="btn btn-light ml-2 ">Convert File</button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <a href="" class="btn btn-success btn-sm d-none" id="download">Download File</a>
                            <textarea id="output-to-download" cols="30" rows="10" hidden></textarea>
                        </div>
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
        $('#convert').on('click', function name(e) {
            // Check If one Of Check Box Selected
            var validity = checkboxCheck();
            if (!validity) {
                return;
            } // chexk if check box valid value
            if($("#encrypt").is(":checked")){
                senAjaxRequest("{{ route('file.encrypt') }}")
            } // send ajax with encrypt route
            else
            {
                senAjaxRequest("{{ route('file.decrypt') }}")
            } // send ajax with deccrpt value

        }); // end of click event (Convert Button)

        function checkboxCheck() {
            if ($("input[type='checkbox']:checked").length > 1 ){
                alert('Please Chosse Only one Of CheckBox');
                return false;
            }
            else if($("input[type='checkbox']:checked").length >0){
                // $('#convert').removeClass('d-none');
                return true;
            }
            else{
                alert('Please Check One Of CheckBox (Encrypt Or Decrypt)');
                return false;
            }
        } // Check For Check Boxs

        function senAjaxRequest(route) {
            // console.log(route);
            var files = $("input[name=file]")[0].files;
            var formData = new FormData();
            formData.append('file', files[0]);
            var route = route;
            $.ajax({
                type: 'POST',
                url: route,
                cache:false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(response) {
                    addTextToSave(response);
                    $('#download').removeClass('d-none');
                },
                error: function() {
                    $('#erorr').text('error');
                },
                complete: function() {
                    $('#erorr').text('');
                }
            });
        } // end of Send Ajax Request

        function addTextToSave(text) {
            $('#output-to-download').text(text);
        } // end of add text to save function

        function download(filename, text) {
            var element = document.getElementById('download');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename);
        } // end of download function

        document.getElementById("download").addEventListener("click", function(){
            // Generate download of hello.txt file with some content
            var text = document.getElementById("output-to-download").value;
            var filename = "ConvertedFile.txt";
            download(filename, text);
        }, false); // end of


    });
    </script>
    {{-- end of script --}}

@endsection
