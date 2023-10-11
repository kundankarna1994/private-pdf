<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfRenderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $filePath = $request->query("path");
        $path = Storage::url("app/" . $filePath);
        $file = base64_encode(Storage::get($filePath));
        return view("render")->with([
            "path" => $path,
            'file' => $file
        ]);
    }
}
