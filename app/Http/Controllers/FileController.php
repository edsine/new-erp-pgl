<?php

namespace App\Http\Controllers;

use App\Models\FileUrl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function serveFile($randomCode)
{
    // Retrieve the file URL entry using the random code
    $fileUrl = FileUrl::where('random_code', $randomCode)->first();

    // Check if the file URL was found
    if ($fileUrl) {
        // Delete the entry from the FileUrl table after it's been accessed
        $fileUrl->delete();

        // Redirect to the actual file URL (either local or S3)
       // return redirect($fileUrl->actual_url);
       // Get the actual file URL (either local or from S3)
       $actualUrl = $fileUrl->actual_url;

       // Check if the file is a local file or a remote file (from S3)
         // If it's a local file, serve it as a normal file
         //return response()->file($actualUrl);
         if (file_exists($actualUrl)) {
            return response()->file($actualUrl, [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-cache, no-store, must-revalidate', // prevent caching
                'Pragma' => 'no-cache', // prevent caching
                'Expires' => '0', // prevent caching
            ]);
        }
    
        return abort(404); // File not found
         //return redirect(asset($actualUrl));
          
       
    }

    // If the random code is not found, show an error
    abort(404, 'File not found');
}

    public function serveFiles($randomCode)
    {
        $decodedId = urldecode($randomCode); // Decode the encoded ID
        $fileUrl = DB::table('documents_manager')->where('id', $decodedId)->first();
    
    if ($fileUrl) {
        $actualUrl = $fileUrl->document_url;

       // Check if the file is a local file or a remote file (from S3)
        // If it's a local file, serve it as a normal file
        //return response()->file(asset($actualUrl));
        return redirect(asset($actualUrl));
           
       
        
    }

    // If the random code is not found, show an error
    abort(404, 'File not found');
    }
    
}
