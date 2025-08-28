<!-- kode untuk addproduct -->

<?php
function handleProductImageUpload($file) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . uniqid() . '_' . basename($file['name']);
    
    // Cek Apakah Gambar/Bukan
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return ['success' => false, 'error' => 'Files are not images'];
    }
    
    // Cek ukuran file maks 2Mb
    if ($file['size'] > 2000000) {
        return ['success' => false, 'error' => 'File size is too large'];
    }
    
    // Format File
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        return ['success' => false, 'error' => 'Only JPG, JPEG, PNG & GIF formats are allowed'];
    }
    
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => true, 'file_path' => $targetFile];
    } else {
        return ['success' => false, 'error' => 'Error upload file'];
    }
}
?>