<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as IPRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Models\Notification;
use Carbon\Carbon;



if (!function_exists('get_datetime_format')) {
    function get_datetime_format($date)
    {
        return date('M d, Y', strtotime($date));
    }
}

if (!function_exists('format_number')) {
    /**
     * Format a number to include commas and two decimal places.
     *
     * @param float|int|string $number
     * @return string
     */
    function format_number($number): string
    {
        // Convert to float to avoid type errors
        $number = floatval($number);
        
        return number_format($number, 2, '.', ',');
    }
}


if (! function_exists('getDocumentUrl')) {
    /**
     * Get the document URL based on the request IP.
     * If the request is from localhost, return the local URL.
     * If not, return a temporary URL from S3.
     *
     * @param  \App\Models\Document  $document
     * @return string
     */
    function getDocumentUrl($document)
    {
             // Return local asset URL for the document
            return asset($document);
     
    }
}

if (! function_exists('getFileUrl')) {
    /**
     * Get the document URL based on the request IP.
     * If the request is from localhost, return the local URL.
     * If not, return a temporary URL from S3.
     *
     * @param  \App\Models\Document  $document
     * @return string
     */
    

function getFileUrl($documentPath)
{
    // Generate and return the appropriate URL
         // For localhost, use asset to generate a local URL
        return asset($documentPath);
}
}

if (! function_exists('getFileUrlNow')) {
    /**
     * Get the document URL based on the request IP.
     * If the request is from localhost, return the local URL.
     * If not, return a temporary URL from S3.
     *
     * @param  \App\Models\Document  $document
     * @return string
     */
    

function getFileUrlNow($documentPath)
{
    // Generate and return the appropriate URL
         return public_path($documentPath);
}
}

if (! function_exists('getFilePath')) {
    /**
     * Get the document URL based on the request IP.
     * If the request is from localhost, return the local URL.
     * If not, return a temporary URL from S3.
     *
     * @param  \App\Models\Document  $document
     * @return string
     */
    

function getFilePath($documentPath)
{
    // Generate and return the appropriate URL
        return public_path($documentPath);
}
}

if (! function_exists('serveFile')) {
    /**
     * Get the document URL based on the request IP.
     * If the request is from localhost, return the local URL.
     * If not, return a temporary URL from S3.
     *
     * @param  \App\Models\Document  $document
     * @return string
     */
    

     function serveFile($fileName)
{
        // Return local asset URL for the document
        return asset($fileName);
}

}
if (! function_exists('htmldecode')) {
    /**
     * decode data.
     *
     * @return string|null
     */
    function htmldecode($string){
        return html_entity_decode($string ?? '');
    }
}

if (! function_exists('is_image')) {
    function is_image($path) {
        // Get file extension
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    
        // Check if the extension matches any common image types
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']);
    }
}

if (! function_exists('is_pdf')) {
    function is_pdf($path) {
        // Get file extension
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    
        // Check if the extension is 'pdf'
        return $extension == 'pdf';
    }
}

function is_office_document($path) {
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
}

function is_text_file($path) {
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return $extension == 'txt';
}

// Custom function to check file extensions
if (!function_exists('get_file_extension')) {
    /**
     * Get the file extension from a file name.
     *
     * @param string $path
     * @return string
     */
    function get_file_extension($path) {
        if (request()->ip() === '127.0.0.1' || request()->ip() === '::1') {

        $fileName = strtolower($path);  // Convert the filename to lowercase

        // Get the last 3 or 4 characters for file extensions (jpg, jpeg, png, etc.)
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION); 

        return $fileExtension;  // Output: jpg, png, etc.

} else {
    // Remove the query string if present (everything after ?)
    $fileName = strtok($path, '?');  // Get the part before the query string

    // Extract the file extension from the filename
    $fileExtension = pathinfo(strtolower($fileName), PATHINFO_EXTENSION); 

    return $fileExtension;  // Output: jpg, png, etc.
}

    }
}

if (!function_exists('getUnreadNotifications')) {
    /**
     * Get unread notifications for the authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getUnreadNotifications()
    {
        return Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->get();
    }
}

if (! function_exists('id_encode')) {
    /**
     * encode data.
     *
     * @return string|null
     */
    function id_encode($string){
        return base64_encode($string);
    }
}
if (! function_exists('id_decode')) {
    /**
     * decode data.
     *
     * @return string|null
     */
    function id_decode($string){
        return base64_decode(base64_encode(($string)));
    }
}

if (!function_exists('timeAgo')) {
    /**
     * Convert a datetime to a "time ago" format.
     *
     * @param  \Carbon\Carbon|string  $date
     * @return string
     */
    function timeAgo($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }
}