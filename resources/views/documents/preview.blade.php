<style>
/* Wrapper to make iframe responsive */
.responsive-iframe-wrapper {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* Aspect ratio for 16:9 */
    overflow: hidden;
    max-width: 100%;
}

.responsive-iframe-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* For Mobile view */
@media (max-width: 768px) {
    .responsive-iframe-wrapper {
        padding-bottom: 75%; /* Adjust the aspect ratio for mobile screens if necessary */
    }
}

/* For Desktop view */
@media (min-width: 1200px) {
    .responsive-iframe-wrapper {
        padding-bottom: 56.25%; /* 16:9 aspect ratio for desktop */
        max-height: 600px; /* Set max height for desktop */
    }
}

/* For screens between 768px to 1200px (tablets or small desktops) */
@media (min-width: 768px) and (max-width: 1199px) {
    .responsive-iframe-wrapper {
        padding-bottom: 60%; /* Adjust aspect ratio if you want it slightly taller */
    }
}


</style>
<?php

$filePath = getFileUrl($document['document_url']); // Get the file's storage path (use storage_path if storing locally)

// Check if the file is an image
if (in_array(get_file_extension($filePath), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'])) {
    echo '<img src="' . $filePath . '" class="img-fluid img_style" alt="Image">';
}

// Check if the file is a PDF
elseif (in_array(get_file_extension($filePath), ['pdf'])) {
    echo '<div class="responsive-iframe-wrapper">
            <iframe src="' . $filePath . '" class="responsive-iframe" frameborder="0"></iframe>
          </div>';
}

// Check if the file is a Word or Excel document
elseif(in_array(get_file_extension($filePath), ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])){
    $encodedFilePath = urlencode($filePath);
     echo '<div class="responsive-iframe-wrapper">
    <iframe src="https://docs.google.com/gview?url=' . $encodedFilePath . '&embedded=true" class="responsive-iframe" frameborder="0"></iframe>
    </div>';
}

// Check if the file is a text file
elseif(in_array(get_file_extension($filePath), ['txt'])){
    $encodedFilePath = urlencode($filePath);
    echo '<iframe src="' . $encodedFilePath . '" class="responsive-iframe" frameborder="0"></iframe>';
}
// For unsupported file types
else {
    if(isset($document['is_download']) && $document['is_download'] == 1){
        echo '<p class="text-muted">No preview available for this file type. <a class="btn btn-primary" target="_blank" href="' . getDocumentUrl($filePath) . '">Download File</a></p>';
    } else {
        echo '<p class="text-muted">No preview available for this file type.</p>';
    }
}
?>
