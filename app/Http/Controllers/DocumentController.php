<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Traits\EncryptDecryptTrait;

class DocumentController extends Controller
{
    use EncryptDecryptTrait;

    public function Convert(Request $request)
    {
        if ($request->ajax()) {
            $key = auth()->user()->key;
            // dump($request);
            $contents= file_get_contents($_FILES['file']['tmp_name']);
            $contentEncrypted = encrypt($contents, $key);
            dump('contentEncrypted');
            dump($contentEncrypted);
            $contentDecrypted = decrypt($contentEncrypted, $key);
            dump('contentDecrypted');
            header('Content-Disposition: attachment; filename="contentDecrypted.txt"');
            header('Content-Type: text/plain'); # Don't use application/force-download - it's not a real MIME type, and the Content-Disposition header is sufficient
            header('Content-Length: ' . strlen($contentDecrypted));
            header('Connection: close');
            dd($contentDecrypted);
        }
    } // end of convert file
} // end of controller
