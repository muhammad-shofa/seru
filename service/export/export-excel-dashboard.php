<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eksport Excel Dashboard</title>
</head>

<body>
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data Pegawai.xls");
    ?>

    <center>
        <h2><b>LIST TINDAK LANJUT TEMUAN DI LINGKUNGAN FT TUBAN</b></h2>
    </center>

    <p>Data Update : <?= date('d-m-Y') ?></p>

    <table border="1">
        <thead>
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
        </thead>
        <tbody>
            <?php
            include "../../service/connection.php";
            include "../../service/select.php";
            $sql_temuan = $connected->query($select->selectTable($tableName = "temuan"));
            if ($sql_temuan->num_rows > 0) {
                $no = 1;

                while ($d = mysqli_fetch_array($sql_temuan)) {
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
                        <td><?= $d['pic'] ?></td>
                        <td><img src="<?= $d['dokumentasi_gambar'] ?>" alt="" width="250px"></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</body>

</html>