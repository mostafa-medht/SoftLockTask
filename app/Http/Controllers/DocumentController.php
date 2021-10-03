<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Http\Traits\EncryptDecryptTrait;

class DocumentController extends Controller
{
    use EncryptDecryptTrait;

    public function __construct()
    {
        $this->middleware('auth');

    } //end of constructor

    public function convertIndex()
    {
        return view('convert');
    } // end of convert index

    public function convert(Request $request)
    {

        $key = auth()->user()->key;
        // dump($request);
        $content = file_get_contents($_FILES['file']['tmp_name']);
        dump($content);
        $fileName = $_FILES['file']['name'];
        dump($fileName);
        $contentEncrypted = encrypt($content, $key);
        dump('contentEncrypted');
        dump($contentEncrypted);
        $contentDecrypted = decrypt($contentEncrypted, $key);
        dump('contentDecrypted');
        // header('Content-Disposition: attachment; filename="contentDecrypted.txt"');
        // header('Content-Type: text/plain'); # Don't use application/force-download - it's not a real MIME type, and the Content-Disposition header is sufficient
        // header('Content-Length: ' . strlen($contentDecrypted));
        // header('Connection: close');
        dd($contentDecrypted);
    } // end of convert file

    public function uploadLargeFiles(Request $request)
    {
        $key = auth()->user()->key;

        // $filePart = $_FILES['file'];
        // dump($filePart['name']);
        // dump($filePart->ge);
        // $size = Storage::size($filePart->getClientOriginalName());
        // dump($filePart['size']/1024);
        // $getfilePartContent = file_get_contents($filePart);
        // dd($getfilePartContent);
        // (A) FUNCTION TO FORMULATE SERVER RESPONSE
        function verbose($ok=1,$info=""){
            // THROW A 400 ERROR ON FAILURE
            if ($ok==0) { http_response_code(400); }
            die(json_encode(["ok"=>$ok, "info"=>$info]));
        }
        // (C) UPLOAD DESTINATION
        // ! CHANGE FOLDER IF REQUIRED !
        $filePath = public_path("chunks");
        if (!file_exists($filePath)) {
            if (!mkdir($filePath, 0777, true)) {
                verbose(0, "Failed to create $filePath");
            }
        }
        // (B) INVALID UPLOAD
        if (empty($_FILES) || $_FILES['file']['error']) {
            verbose(0, "Failed to move uploaded file.");
        }

        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
        $filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;

        // (D) DEAL WITH CHUNKS
        $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
        $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
        $out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
        if ($out) {
        $in = @fopen($_FILES['file']['tmp_name'], "rb");
        if ($in) {
            while ($buff = fread($in, 4096)) { fwrite($out, $buff); }
        } else {
            verbose(0, "Failed to open input stream");
        }
        @fclose($in);
        @fclose($out);
        @unlink($_FILES['file']['tmp_name']);
        } else {
        verbose(0, "Failed to open output stream");
        }

        // (E) CHECK IF FILE HAS BEEN UPLOADED
        if (!$chunks || $chunk == $chunks - 1) {
            rename("{$filePath}.part", $filePath);
        }
        verbose(1, "Upload OK");
        // $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        // if (!$receiver->isUploaded()) {
        //    return response()->json("there is some thing wrong");
        // }
        // $fileReceived = $receiver->receive(); // receive file
        // // dd(file_get_contents($fileReceived->getFile()));
        // $filePart = $fileReceived->getFile();
        // // dd($filePart);
        // $getfilePartContent = file_get_contents($fileReceived->getFile());
        // // dump($getfilePartContent);
        // $contentEncrypted = encrypt($getfilePartContent, $key);
        // dump($contentEncrypted);

        // if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
        //     // $fileName = $_FILES['file']['name'];
        //     $file = $fileReceived->getFile(); // get file
        //     // $content = file_get_contents($file);
        //     // $contentEncrypted = encrypt($content, $key);
        //     $extension = $file->getClientOriginalExtension();
        //     $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
        //     $fileName .= ".".uniqid('', true).".".$extension; // a unique file name

        //     // $fileDestination = $fileName;
        //     // $pathTO = public_path()."\\uploads\\";

        //     $disk = Storage::disk(config('filesystems.default'));
        //     // $convertedFile = fopen($pathTO.$fileName, "wb") or die("Unable to open file!");
        //     // fwrite($convertedFile, $contentEncrypted);
        //     // fclose($convertedFile);
        //     $pathFrom = $disk->putFileAs('videos', $file, $fileName);
        //     // return redirect()->route('file.forceDonwload', $pathTO.$fileName);
        //     // move_uploaded_file($pathFrom.$fileName, $pathTO.$fileName);
        //     // delete chunked file
        //     unlink($file->getPathname());
        //     return [
        //         'path' => asset('storage/' . $pathFrom),
        //         'filename' => $fileName
        //     ];
        // }
        // // otherwise return percentage informatoin
        // $handler = $fileReceived->handler();
        // return [
        //     'done' => $handler->getPercentageDone(),
        //     'status' => true
        // ];

    } // end of upload large file

    public function convertToEncrypt(Request $request)
    {
        // dd($request);
        $key = auth()->user()->key;
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileExtention = explode('.', $fileName);
        $fileActualExtention = strtolower(end($fileExtention));
        $content = file_get_contents($_FILES['file']['tmp_name']);
        $filePath = public_path()."/uploads/";
        $contentEncrypted = encrypt($content, $key);


            if ($fileError === 0) { // handle file error
                if ($fileSize < 10000000) { // handle file size
                    $newfileNameWithExt = $fileExtention[0].".".uniqid('', true).".".$fileActualExtention;
                    $fileDestination = $filePath.$newfileNameWithExt;
                    $convertedFile = fopen($fileDestination, "wb") or die("Unable to open file!");
                    fwrite($convertedFile, $contentEncrypted);
                    fclose($convertedFile);
                    // return redirect()->route('file.forceDonwload', $newfileNameWithExt);
                    return  response($newfileNameWithExt);
                }else{
                    return response("Your File Is Too Big");
                }
            }else {
                return response("There was an error uploading your file!");
            }
        // }
        // else {
        //     echo "You Can Not Upload Files Of This Type! ";
        // }

    } // end of convert to encrypt

    public function convertToDecrypt(Request $request)
    {
        $key = auth()->user()->key;
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileExtention = explode('.', $fileName);
        $fileActualExtention = strtolower(end($fileExtention));
        $content = file_get_contents($_FILES['file']['tmp_name']);
        $filePath = public_path()."/uploads/";
        $contentDecrypted = decrypt($content, $key);

        if ($fileError === 0) { // handle file error
            if ($fileSize < 10000000) { // handle file size
                $newfileNameWithExt = $fileExtention[0].".".uniqid('', true).".".$fileActualExtention;
                $fileDestination = $filePath.$newfileNameWithExt;
                $convertedFile = fopen($fileDestination, "wb") or die("Unable to open file!");
                fwrite($convertedFile, $contentDecrypted);
                fclose($convertedFile);
                return  response($newfileNameWithExt);
                // return redirect()->route('file.forceDonwload', $newfileNameWithExt);
            }else{
                return response("Your File Is Too Big");
            }
        }else {
            return response("There was an error uploading your file!");
        }
        // }else {
        //     echo "You Can Not Upload Files Of This Type! ";
        // }

    } // end of convert to decrypt

    public static function forceDonwload(string $newfileNameWithExt = null)
    {
        $pathToFile = public_path("uploads\\".$newfileNameWithExt);
        return response()->download($pathToFile, $name?? null, $headers??[])->deleteFileAfterSend();
    } // end of force download

    public function encrypt(Request $request)
    {
        $key = auth()->user()->key;
        $contents= file_get_contents($request->file);
        $contentEncrypted = encrypt($contents, $key);
        return $contentEncrypted;
    } // end of encrypt file

    public function decrypt(Request $request)
    {
        $key = auth()->user()->key;
        $contents= file_get_contents($request->file);
        $contentDecrypted = decrypt($contents, $key);
        return $contentDecrypted;
    }
} // end of controller
