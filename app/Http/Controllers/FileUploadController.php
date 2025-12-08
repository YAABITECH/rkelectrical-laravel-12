<?php

namespace App\Http\Controllers;

use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    use FileUploadTrait;

    public function upload(Request $request)
    {
        $metadata = [
            'prefix' => 'img',
            'sizes' => [
                ['width' => 1200, 'path' => ''],
                ['width' => 300, 'path' => 'thumb/']
            ]
        ];

        $fileName = $this->handleFileUpload($request, 'file', '/files/', 'image', $metadata);

        return response()->json([
            'location' => asset('/files/'.$fileName)
        ]);
    }
}
