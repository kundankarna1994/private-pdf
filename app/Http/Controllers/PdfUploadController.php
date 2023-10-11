<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfUploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $path = $request->file("file")->store("private");
        return to_route("render", ['path' => $path]);
    }
}
