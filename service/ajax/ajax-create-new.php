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
    } else if (isset($_GET["sumber"]) && $_GET["sumber"] == "notulen_rapat") {
        // Khusus menampilkan data dengan sumber_temuan NOTULEN_RAPAT
        $result = $connected->query($select->selectTable($table_name = "temuan", $fields = "*", $condition = "WHERE sumber_temuan = 'NOTULEN_RAPAT'"));

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
            } else {
                $value_dokumentasi_tl = $row['dokumentasi_tl'];
                if ($row['dokumentasi_gambar'] == NULL) {
                    $row['dokumentasi_tl'] = $value_dokumentasi_tl;
                } else {
                    $file_path = $row['dokumentasi_gambar'];
                    $file_name = basename($file_path);
                    $row['dokumentasi_tl'] = $value_dokumentasi_tl . '<a href="download.php?temuan_id=' . $row['temuan_id'] . '" class="btn btn-success btn-sm" download title="Download ' . $file_name . '">
                        <i class="fas fa-download"></i></a>';
                }
            }

            // Menambahkan tombol aksi hapus
            $row['action_create_new'] = '<button type="button" class="delete btn btn-danger" data-temuan_id="' . $row['temuan_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

            $data[] = $row;
        }

        // Mengirim data ke DataTables dalam format JSON
        echo json_encode(["data" => $data]);

    } else {
        // Menampilkan semua data jika tidak ada ID temuan dan sumber_temuan khusus
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
            } else {
                $value_dokumentasi_tl = $row['dokumentasi_tl'];
                if ($row['dokumentasi_gambar'] == NULL) {
                    $row['dokumentasi_tl'] = $value_dokumentasi_tl;
                } else {
                    $file_path = $row['dokumentasi_gambar'];
                    $file_name = basename($file_path);
                    $row['dokumentasi_tl'] = $value_dokumentasi_tl . '<a href="download.php?temuan_id=' . $row['temuan_id'] . '" class="btn btn-success btn-sm" download title="Download ' . $file_name . '">
                    <i class="fas fa-download"></i></a>';
                }
            }

            // Menambahkan tombol aksi hapus
            $row['action_create_new'] = '<button type="button" class="delete btn btn-danger" data-temuan_id="' . $row['temuan_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

            $data[] = $row;
        }

        // Mengirim data ke DataTables dalam format JSON
        echo json_encode(["data" => $data]);
    }

} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tambah_tanggal = $_POST['tambah_tanggal'];
    $tambah_sumber_temuan = $_POST['tambah_sumber_temuan'];
    $tambah_temuan = $_POST['tambah_temuan'];
    $tambah_rekomendasi_tindak_lanjut = $_POST['tambah_rekomendasi_tindak_lanjut'];
    $tambah_status = $_POST['tambah_status'];
    $tambah_pic = $_POST['tambah_pic'];
    $tambah_deadline = $_POST['tambah_deadline'];
    $tambah_dokumentasi_tl = null;
    $tambah_dokumentasi_gambar = null;
    $tambah_keterangan = $_POST['tambah_keterangan'];


    $stmt = $connected->prepare($insert->selectTable($table_name = "temuan", $condition = "(tanggal, sumber_temuan, temuan, rekomendasi_tindak_lanjut, status, pic, deadline, dokumentasi_tl, dokumentasi_gambar, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"));
    $stmt->bind_param("ssssssssss", $tambah_tanggal, $tambah_sumber_temuan, $tambah_temuan, $tambah_rekomendasi_tindak_lanjut, $tambah_status, $tambah_pic, $tambah_deadline, $tambah_dokumentasi_tl, $tambah_dokumentasi_gambar, $tambah_keterangan);

    if ($stmt->execute()) {
        echo "Berhasil menambahkan data baru";
    } else {
        echo "Gagal menambahkan data baru" . $stmt->error;
    }

    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Delete temuan
    parse_str(file_get_contents("php://input"), $data);
    $temuan_id = $data["temuan_id"];

    $stmt = $connected->prepare($delete->select_table($table_name = "temuan", $condition = "WHERE temuan_id = ?"));
    $stmt->bind_param("i", $temuan_id);

    if ($stmt->execute()) {
        echo "Berhasil menghapus temuan";
    } else {
        echo "Gagal menghapus temuan: " . $stmt->error;
    }

    $stmt->close();
}