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
                // $row['dokumentasi_tl_create_new'] = "p";
            } else {
                $value_dokumentasi_tl = $row['dokumentasi_tl'];
                $file_path = $row['dokumentasi_gambar'];
                $file_name = basename($file_path);
                $row['dokumentasi_tl'] = $value_dokumentasi_tl . '<a href="download.php?temuan_id=' . $row['temuan_id'] . '" class="btn btn-success btn-sm" download title="Download' . $file_name . '">
                <i class="fas fa-download"></i></a>';
            }



            $row['action_create_new'] = '<button type="button" class="delete btn btn-danger" data-temuan_id="' . $row['temuan_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';



            $data[] = $row;
        }

        echo json_encode(["data" => $data]);
    }

} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Simpan Update Dokumentasi TL
    if (isset($_POST['simpanUpdate'])) {
        // Ambil data dari form
        $temuan_id = $_POST['temuan_id'];
        $dokumentasi_tl = $_POST['dokumentasi_tl'];
        $status = $_POST['status'];

        if (!empty($_FILES['dokumentasi_gambar'])) {
            $dokumentasi_gambar = $_FILES['dokumentasi_gambar']['name'];
            $tmp = $_FILES['dokumentasi_gambar']['tmp_name'];

            // lokasi gambar
            $location = "../../uploads/" . basename($dokumentasi_gambar);

            // Pindahkan file ke folder tujuan
            if (move_uploaded_file($tmp, $location)) {
                $stmt = $connected->prepare($update->selectTable($tableName = "temuan", $condition = "status = ?, dokumentasi_tl = ?, dokumentasi_gambar = ? WHERE temuan_id = ?"));
                $stmt->bind_param("sssi", $status, $dokumentasi_tl, $location, $temuan_id);

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
            $stmt = $connected->prepare($update->selectTable($tableName = "temuan", $condition = "status = ?, dokumentasi_tl = ? WHERE temuan_id = ?"));
            $stmt->bind_param("ssi", $status, $dokumentasi_tl, $temuan_id);

            if ($stmt->execute()) {
                echo "Berhasil mengupdate data.";
            } else {
                echo "Gagal mengupdate: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        // if (isset($_POST['tambahTemuan']))
        $tambah_tanggal = $_POST['tambah_tanggal'];
        $tambah_sumber_temuan = $_POST['tambah_sumber_temuan'];
        $tambah_temuan = $_POST['tambah_temuan'];
        $tambah_rekomendasi_tindak_lanjut = $_POST['tambah_rekomendasi_tindak_lanjut'];
        $tambah_status = $_POST['tambah_status'];
        $tambah_pic = $_POST['tambah_pic'];
        $tambah_deadline = $_POST['tambah_deadline'];
        // $tambah_dokumentasi_tl = $_POST['tambah_dokumentasi_tl'];
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
    }

}

