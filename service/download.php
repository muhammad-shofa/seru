<?php
include "connection.php";
include "select.php";

// Cek apakah id temuan dikirim
if (isset($_GET['temuan_id'])) {

    // Mengambil id temuan
    $temuan_id = $_GET['temuan_id'];

    // Ambil path file dari database berdasarkan temuan_id
    $stmt = $connected->prepare("SELECT dokumentasi_gambar FROM temuan WHERE temuan_id = ?");
    $stmt->bind_param("i", $temuan_id);
    $file_path = "";
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $file_path = $data['dokumentasi_gambar'];
        // $stmt->bind_result($file_path);
        // $stmt->fetch();
        // $stmt->close();

        // Pastikan file path benar
        if (!empty($file_path)) {
            // Jika file path relatif, ubah menjadi path absolut (sesuaikan dengan lokasi file di server)
            $full_path = $_SERVER['DOCUMENT_ROOT'] . 'img/uploads/' . $file_path; // Sesuaikan lokasi file

            // Cek apakah file ada
            if (file_exists($full_path)) {
                // Set header sesuai dengan tipe file
                $file_extension = strtolower(pathinfo($full_path, PATHINFO_EXTENSION));

                switch ($file_extension) {
                    case "jpg":
                    case "jpeg":
                        header('Content-Type: image/jpeg');
                        break;
                    case "png":
                        header('Content-Type: image/png');
                        break;
                    case "gif":
                        header('Content-Type: image/gif');
                        break;
                    default:
                        header('Content-Type: application/octet-stream');
                }

                // Set header untuk mendownload file
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename="' . basename($full_path) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($full_path));

                // Baca file dan kirim ke output
                readfile($full_path);
                exit;
            } else {
                echo "File tidak ditemukan.";
            }
        } else {
            echo "File path tidak tersedia.";
        }
    } else {
        echo "Gagal melakukan eksekusi query";
    }

} else {
    echo "ID temuan tidak ditemukan.";
}