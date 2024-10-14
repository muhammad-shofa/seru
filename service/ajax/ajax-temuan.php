<?php
include "../connection.php";
include "../select.php";
include "../insert.php";
include "../update.php";
include "../delete.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // temuan
    if (isset($_GET["temuan_id"])) {
        $temuan_id = $_GET["temuan_id"];
        $stmt = $connected->prepare($select->selectTable($table_name = "temuan", $fields = "*", $condition = "WHERE temuan_id = ?"));
        $stmt->bind_param("i", $temuan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        $result = $connected->query($select->selectTable($table_name = "temuan", $fields = "*", $condition = ""));

        $data = [];

        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $i++;
            $row['no'] = $i;

            // Mengubah format tanggal
            if (isset($row['tanggal'])) {
                $row['tanggal'] = date("m/d/Y", strtotime($row['tanggal']));
            }

            // Mengecek jika kolom dokumentasi_tl kosong
            if (empty($row['dokumentasi_tl'])) {
                $row['dokumentasi_tl'] = '<button type="button" class="update btn btn-primary btn-sm" data-temuan_id="' . $row['temuan_id'] . '" data-toggle="modal">Update</button>';
            }

            $row['action'] = '<button type="button" class="edit btn btn-primary" data-temuan_id="' . $row['temuan_id'] . '" data-toggle="modal"><i class="fas fa-pen"></i></button>
            <button type="button" class="delete btn btn-danger" data-temuan_id="' . $row['temuan_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

            $data[] = $row;
        }

        echo json_encode(["data" => $data]);
    }

} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['simpanUpdate'])) {
        // Ambil data dari form
        $temuan_id = $_POST['temuan_id'];
        $rekomendasi_tindak_lanjut = $_POST['rekomendasi_tindak_lanjut'];
        $status = $_POST['status'];
        $image_dokumentasi = $_FILES['dokumentasi_tl']['name'];
        $tmp = $_FILES['dokumentasi_tl']['tmp_name'];

        // Tentukan lokasi penyimpanan file menggunakan path absolut
        $location = "../../uploads/" . basename($image_dokumentasi);

        // Pindahkan file ke folder tujuan
        if (move_uploaded_file($tmp, $location)) {
            // Jika file berhasil diupload, lanjutkan untuk update data di database
            $stmt = $connected->prepare($update->selectTable($tableName = "temuan", $condition = "rekomendasi_tindak_lanjut = ?, status = ?, dokumentasi_tl = ? WHERE temuan_id = ?"));
            $stmt->bind_param("sssi", $rekomendasi_tindak_lanjut, $status, $location, $temuan_id);

            if ($stmt->execute()) {
                echo "Berhasil mengupdate data dan file.";
            } else {
                echo "Gagal mengupdate: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Gagal mengunggah file.";
        }
    } else {
        echo "tidak ditemukan submitUpdate";
    }
}
