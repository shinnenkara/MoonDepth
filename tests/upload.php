<?php
if (isset($_FILES['file'])) {
    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    } else {
        $fi = new FilesystemIterator("uploads", FilesystemIterator::SKIP_DOTS);
        $fcount = iterator_count($fi);
        $file = $_FILES['file'];
        $file_name = explode('.', $file['name']);
        $file['name'] = ($fcount + 1) . "." . $file_name[1];
        move_uploaded_file($file['tmp_name'], 'uploads/' . $file['name']);
        echo "Uploaded";
    }
} else {
    echo "no file";
}
echo "<br>";
if (isset($_POST['check'])) {
    echo "it's checked";
} else {
    echo "no checked";
}
?>