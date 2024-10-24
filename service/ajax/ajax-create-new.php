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
        // if (isset($_GET["temuan_id"]) && $_GET["temuan_id"] == $_GET['temuan_id']) {
        //     echo 'ini dari notulen_rapat dengan id' . $_GET['temuan_id'];
        // }
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

            // Ubah tanggal ke TW
            $row['deadline_tw'] = getTriwulan($row['deadline']);

            // Menambahkan tombol aksi hapus
            $row['action_create_new'] = '<button type="button" class="save btn btn-success" data-temuan_id="' . $row['temuan_id'] . '" data-toggle="modal"><i class="fas fa-save"></i></button>
            <button type="button" class="delete btn btn-danger" data-temuan_id="' . $row['temuan_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

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

            // Ubah tanggal ke TW
            $row['deadline_tw'] = getTriwulan($row['deadline']);

            // Menambahkan tombol aksi hapus
            $row['action_create_new'] = '<button type="button" class="delete btn btn-danger" data-temuan_id="' . $row['temuan_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

            $data[] = $row;
        }

        // Mengirim data ke DataTables dalam format JSON
        echo json_encode(["data" => $data]);
    }

} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_GET["sumber"]) && $_GET["sumber"] == "notulen_rapat") {

        // cek apakah ada
        if (isset($_GET["sumber"]) && $_GET["sumber"] == "notulen_rapat" && isset($_GET['action']) && $_GET['action'] == "save") {

            $get_data = $connected->query($select->selectTable($table_name = "temuan", $fields = "*", $condition = "WHERE sumber_temuan == 'NOTULEN_RAPAT'"));

            if ($get_data->num_rows > 0) {
                $data_notulen = $get_data->fetch_assoc(); // Ambil semua data notulen rapat dari tabel temuan
                // $no_notulen = $data_notulen['no_notulen'] + 1;
                

                $stmt->prepare($connected->query($insert->selectTable($table_name = "list_dokumen", $condition = "(no_notulen, tanggal, perihal, draft_url, dokumen_url) VALUES (?, ?, ?, ?, ?)")));
                $stmt->bind_param("issss", $no_notulen);
            }

            echo "tes aja";
        } else {
            // Simpan Update Dokumentasi TL
            // if (isset($_POST['simpanUpdate'])) {
            //     // Ambil data dari form
            //     $temuan_id = $_POST['temuan_id'];
            //     $dokumentasi_tl = $_POST['dokumentasi_tl'];
            //     $status = $_POST['status'];

            //     if (isset($_FILES['dokumentasi_gambar']) && $_FILES['dokumentasi_gambar']['error'] != UPLOAD_ERR_NO_FILE) {
            //         $dokumentasi_gambar = $_FILES['dokumentasi_gambar']['name'];
            //         $tmp = $_FILES['dokumentasi_gambar']['tmp_name'];

            //         // lokasi gambar
            //         $location = "../../img/uploads/" . basename($dokumentasi_gambar);

            //         // Pindahkan file ke folder tujuan
            //         if (move_uploaded_file($tmp, $location)) {
            //             $stmt = $connected->prepare($update->selectTable($tableName = "temuan", $condition = "status = ?, dokumentasi_tl = ?, dokumentasi_gambar = ? WHERE temuan_id = ?"));
            //             $stmt->bind_param("sssi", $status, $dokumentasi_tl, $location, $temuan_id);

            //             if ($stmt->execute()) {
            //                 echo "Berhasil mengupdate data dan file.";
            //             } else {
            //                 echo "Gagal mengupdate: " . $stmt->error;
            //             }

            //             $stmt->close();
            //         } else {
            //             echo "Gagal mengunggah file.";
            //         }
            //     } else {
            //         $stmt = $connected->prepare($update->selectTable($tableName = "temuan", $condition = "status = ?, dokumentasi_tl = ? WHERE temuan_id = ?"));
            //         $stmt->bind_param("ssi", $status, $dokumentasi_tl, $temuan_id);

            //         if ($stmt->execute()) {
            //             echo "Berhasil mengupdate data.";
            //         } else {
            //             echo "Gagal mengupdate: " . $stmt->error;
            //         }
            //         $stmt->close();
            //     }
            // } else {


            // Tambah data berdasarkan sumber= khusus
            $tambah_tanggal = $_POST['tambah_tanggal'];
            $tambah_sumber_temuan = "NOTULEN_RAPAT";
            $tambah_fungsi = $_POST['tambah_fungsi'];
            $tambah_temuan = $_POST['tambah_temuan'];
            $tambah_rekomendasi_tindak_lanjut = $_POST['tambah_rekomendasi_tindak_lanjut'];
            $tambah_status = $_POST['tambah_status'];

            // Ubah array PIC ke string
            $pic_array = $_POST['tambah_pic'];
            $tambah_pic = implode(',', $pic_array);

            $tambah_deadline = $_POST['tambah_deadline'];
            $tambah_dokumentasi_tl = null;
            $tambah_dokumentasi_gambar = null;
            $tambah_prioritas = $_POST['tambah_prioritas'];
            $tambah_keterangan = $_POST['tambah_keterangan'];

            $stmt = $connected->prepare($insert->selectTable($table_name = "temuan", $condition = "(tanggal, sumber_temuan, fungsi, temuan, rekomendasi_tindak_lanjut, status, pic, deadline, dokumentasi_tl, dokumentasi_gambar, prioritas, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"));
            $stmt->bind_param("ssssssssssss", $tambah_tanggal, $tambah_sumber_temuan, $tambah_fungsi, $tambah_temuan, $tambah_rekomendasi_tindak_lanjut, $tambah_status, $tambah_pic, $tambah_deadline, $tambah_dokumentasi_tl, $tambah_dokumentasi_gambar, $tambah_prioritas, $tambah_keterangan);

            if ($stmt->execute()) {
                echo "Berhasil menambahkan data baru";
            } else {
                echo "Gagal menambahkan data baru" . $stmt->error;
            }

            $stmt->close();
        }

    } else {
        // Tambah data jika tidak ada sumber= khusus
        $tambah_tanggal = $_POST['tambah_tanggal'];
        $tambah_sumber_temuan = $_POST['tambah_sumber_temuan'];
        $tambah_temuan = $_POST['tambah_temuan'];
        $tambah_rekomendasi_tindak_lanjut = $_POST['tambah_rekomendasi_tindak_lanjut'];
        $tambah_status = $_POST['tambah_status'];

        // Ubah array PIC ke string
        $pic_array = $_POST['tambah_pic'];
        $tambah_pic = implode(',', $pic_array);

        $tambah_deadline = $_POST['tambah_deadline'];
        $tambah_dokumentasi_tl = null;
        $tambah_dokumentasi_gambar = null;
        $tambah_prioritas = $_POST['tambah_prioritas'];
        $tambah_keterangan = $_POST['tambah_keterangan'];

        $stmt = $connected->prepare($insert->selectTable($table_name = "temuan", $condition = "(tanggal, sumber_temuan, temuan, rekomendasi_tindak_lanjut, status, pic, deadline, dokumentasi_tl, dokumentasi_gambar, prioritas, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"));
        $stmt->bind_param("sssssssssss", $tambah_tanggal, $tambah_sumber_temuan, $tambah_temuan, $tambah_rekomendasi_tindak_lanjut, $tambah_status, $tambah_pic, $tambah_deadline, $tambah_dokumentasi_tl, $tambah_dokumentasi_gambar, $tambah_prioritas, $tambah_keterangan);

        if ($stmt->execute()) {
            echo "Berhasil menambahkan data baru";
        } else {
            echo "Gagal menambahkan data baru" . $stmt->error;
        }

        $stmt->close();
    }
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

// Function ubah tanggal ke string TW
function getTriwulan($tanggalInput)
{
    // Mengambil tahun dan bulan dari input tanggal
    $date = new DateTime($tanggalInput);
    $bulan = (int) $date->format('m');
    $tahun = $date->format('Y');

    // Menentukan triwulan berdasarkan bulan
    if ($bulan >= 1 && $bulan <= 3) {
        $triwulan = "TW I";
    } elseif ($bulan >= 4 && $bulan <= 6) {
        $triwulan = "TW II";
    } elseif ($bulan >= 7 && $bulan <= 9) {
        $triwulan = "TW III";
    } else {
        $triwulan = "TW IV";
    }

    // Menggabungkan triwulan dan tahun
    return $triwulan . " " . $tahun;
}
