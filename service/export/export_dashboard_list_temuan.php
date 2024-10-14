<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Excel</title>
</head>

<body>
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=pengaduan-masyarakat.xls");
    ?>

    <center>LIST TINDAK LANJUT TEMUAN DI LINGKUNGAN FT TUBAN</center>

    Data Update : <?= date("l, d F Y") ?>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Sumber Temuan</th>
            <th>Temuan</th>
            <th>Rekomendasi Tindak Lanjut</th>
            <th>Status</th>
            <th>PIC</th>
            <th>Deadline</th>
            <th>Dokumentasi TL</th>
            <th>Keterangan</th>
            <th>Last Update</th>
            <th>Dokumentasi</th>
        </tr>
        <?php
        include "../service/connection.php";
        include "../service/select.php";
        $sql_pengaduan = $connected->query($select->selectTable($tableName = "temuan"));
        if ($sql_pengaduan->num_rows > 0) {
            $no = 1;

            while ($d = mysqli_fetch_array($sql_pengaduan)) {
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $d['tanggal'] ?></td>
                    <td><?= $d['sumber_temuan'] ?></td>
                    <td><?= $d['temuan'] ?></td>
                    <td><?= $d['rekomendasi_tindak_lanjut'] ?></td>
                    <td><?= $d['status'] ?></td>
                    <td><?= $d['pic'] ?></td>
                    <td><?= $d['deadline'] ?></td>
                    <td><?= $d['dokumentasi_tl'] ?></td>
                    <td><?= $d['keterangan'] ?></td>
                    <!-- last update belum ada datanya -->
                    <td><?= $d['last_update'] ?></td>
                    <td><?= $d['dokumentasi_gambar'] ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</body>

</html>