<?php
include "../connection.php";
include "../select.php";

// Cek apakah id temuan dikirim
if (isset($_GET['temuan_id'])) {

    // Mengambil id temuan
    $temuan_id = $_GET['temuan_id'];

    // Ambil path file dari database berdasarkan temuan_id
    $stmt = $connected->prepare("SELECT dokumentasi_tl FROM temuan WHERE temuan_id = ?");
    $stmt->bind_param("i", $temuan_id);
    $stmt->execute();
    $stmt->bind_result($file_path);
    $stmt->fetch();
    $stmt->close();

    // Cek apakah file ada
    if (file_exists($file_path)) {
        // Set header untuk mendownload file
        header('Content-Description: File Transfer');
        header('header("Content-Type: image/jpeg")');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));

        // Baca file dan kirim ke output
        readfile($file_path);
        exit;
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "ID temuan tidak ditemukan.";
}