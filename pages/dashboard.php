<?php
include "../service/connection.php";
include "../service/select.php";
include "../service/insert.php";
include "../service/update.php";
include "../service/delete.php";
session_start();

// check login
if ($_SESSION["is_login"] == false) {
    header("location: ../index.php");
}


// zzzzzzzzzzzzz
// ZZZZZZZZZZZZZZZZZZ
// if (isset($_POST['submitUpdate'])) {
//     $rekomendasi_tindak_lanjut = $_POST['rekomendasi_tindak_lanjut'];
//     $status = $_POST['status'];
//     $image_dokumentasi = $_FILES['dokumentasi_tl']['name'];
//     $tmp = $_FILES['dokumentasi_tl']['tmp_name'];

//     $stmt = $connected->prepare("UPDATE temuan SET rekomendasi_tindak_lanjut = ?, status = ?, dokumentasi_tl = ? WHERE temuan_id = ?");
//     $stmt->bind_param("sssi", $rekomendasi_tindak_lanjut, $status, $image_dokumentasi, $temuan_id);

//     $location = "../uploads/" . basename($image_dokumentasi); // Lokasi penyimpanan file

//     if ($stmt->execute()) {
//         echo "Berhasil mengupdate execute";
//         if (move_uploaded_file($tmp, $location)) {
//             echo "Berhasil mengupdate ke db dan memindah file ke folder";
//             // echo "<script>alert('data berhasil ditambahkan');window.location.replace('index.php')</script>";
//         } else {
//             echo "Gagal memindahkan file ke folder";
//         }
//     } else {
//         echo "Gagal mengupdate: " . $stmt->error;
//     }
// } else {
//     echo "tidak ditemukan submitUpdate";
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Seru | Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include "../layout/sidebar.php" ?>


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- navbar -->
                <?php include "../layout/navbar.php" ?>

                <!-- Begin Page Content -->
                <div class="container-fluid" id="mainDashboard">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="../service/export/export_dashboard_list_temuan.php"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Begin of listTemuan -->
                    <div class="p-2" id="listTemuanContent">
                        <!-- DataTales -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">List Temuan</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-dark" id="temuanTable" width="100%"
                                        cellspacing="0">
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
                                                <!-- <th>Aksi</th> -->
                                            </tr>
                                        </thead>
                                        <!-- <tfoot>
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
                                            </tr>
                                        </tfoot> -->
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Modal update -->
                            <div class="modal fade" id="modalUpdate">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Update</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="formUpdate" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <!-- method="post" -->
                                                <input type="text" id="update_temuan_id" name="temuan_id">
                                                <div class="form-floating">
                                                    <label for="update_rekomendasi_tindak_lanjut">Tindak Lanjut
                                                        Perbaikan
                                                    </label>
                                                    <textarea class="form-control"
                                                        placeholder="Tindak lanjut perbaikan ..."
                                                        id="update_rekomendasi_tindak_lanjut"
                                                        name="rekomendasi_tindak_lanjut"
                                                        style="height: 200px; resize: none;"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control select2" name="status"
                                                        id="update_status" style="width: 100%;">
                                                        <option value="OPEN">OPEN</option>
                                                        <option value="CLOSE">CLOSE</option>
                                                        <option value="ON_PROGRESS">ON PROGRESS</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="update_dokumentasi_tl"
                                                        class="form-label">Dokumentasi</label>
                                                    <input class="form-control" type="file" id="update_dokumentasi_tl"
                                                        name="dokumentasi_tl">
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-primary" name="simpanUpdate"
                                                    id="simpanUpdate">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- Modal update End -->

                            <!-- <div class="update-container">

                            </div> -->
                        </div>
                    </div>

                    <!-- End of listTemuan -->

                    <div class="p-2" id="createNewContent" style="display:none;">
                        <h2>Ini create new main dashboard</h2>
                    </div>
                </div>

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Seru 2024</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <!-- <script src="../js/demo/datatables-demo.js"></script> -->

    <script>
        $(document).ready(function () {
            var table = $('#temuanTable').DataTable({
                "ajax": "../service/ajax/ajax-temuan.php",
                "columns": [{
                    "data": "no"
                },
                {
                    "data": "tanggal"
                },
                {
                    "data": "sumber_temuan"
                },
                {
                    "data": "temuan"
                },
                {
                    "data": "rekomendasi_tindak_lanjut"
                },
                {
                    "data": "status"
                },
                {
                    "data": "pic"
                },
                {
                    "data": "deadline"
                },
                {
                    "data": "dokumentasi_tl"
                },
                {
                    "data": "keterangan"
                }
                    // {
                    //     "data": "action",
                    //     "orderable": true,
                    //     "searchable": true
                    // }
                ],
                "responsive": true
            });

            // Menampilkan modal Update
            $('#temuanTable').on('click', '.update', function () {
                let temuan_id = $(this).data('temuan_id');
                $.ajax({
                    url: '../service/ajax/ajax-temuan.php?temuan_id=' + temuan_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#update_temuan_id').val(data.temuan_id);
                        $('#update_rekomendasi_tindak_lanjut').val(data.rekomendasi_tindak_lanjut);
                        $('#update_status').val(data.status);
                        $('#update_dokumentasi_tl').val(data.dokumentasi_tl);
                        $('#modalUpdate').modal('show');
                    }
                });
            });




            $('#simpanUpdate').click(function () {
                var formData = new FormData($('#formUpdate')[0]);

                formData.append('simpanUpdate', true);

                $.ajax({
                    url: '../service/ajax/ajax-temuan.php',
                    type: 'POST', // Karena `PUT` secara teknis lebih kompleks untuk pengiriman form, gunakan `POST` dengan
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modalUpdate').modal('hide');
                        table.ajax.reload();
                        $('#formUpdate')[0].reset();
                        alert(response);
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText); // Debugging untuk melihat error
                    }
                });
            });

            // simpan tandai dibaca
            // $('#simpanUpdate').click(function () {
            //     var data = $('#formUpdate').serialize();
            //     $.ajax({
            //         url: '../service/ajax/ajax-temuan.php',
            //         type: 'POST',
            //         data: data,
            //         success: function (response) {
            //             $('#modalUpdate').modal('hide');
            //             table.ajax.reload();
            //             alert(response);
            //         }
            //     });
            // });



            // Menyimpan update
            // $('#simpanUpdate').click(function () {
            // var dataUpdate = $('#formUpdate').serialize();
            // $.ajax({
            // url: '../service/ajax/ajax-temuan.php',
            // type: 'PUT',
            // dataUpdate: dataUpdate,
            // success: function (response) {
            // $('#modalUpdate').modal('hide');
            // table.ajax.reload();
            // $('#formUpdate')[0].reset();
            // alert(response);
            // }
            // });
            // });

            // Menampilkan modal Edit User
            // $('#pengguna_table').on('click', '.edit', function () {
            // let user_id = $(this).data('user_id');
            // $.ajax({
            // url: '../service/ajax/ajax-pengguna.php?user_id=' + user_id,
            // type: 'GET',
            // dataType: 'json',
            // success: function (data) {
            // $('#edit_user_id').val(data.user_id);
            // $('#edit_username').val(data.username);
            // $('#edit_nama_lengkap').val(data.nama_lengkap);
            // $('#edit_email').val(data.email);
            // $('#edit_tanggal_lahir').val(data.tanggal_lahir);
            // $('#edit_jenis_kelamin').val(data.jenis_kelamin);
            // $('#edit_role').val(data.role);
            // $('#modalEdit').modal('show');
            // }
            // });
            // });

            // Simpan edit user
            // $('#simpanEdit').click(function () {
            // var data = $('#formEdit').serialize();
            // $.ajax({
            // url: '../service/ajax/ajax-pengguna.php',
            // type: 'PUT',
            // data: data,
            // success: function (response) {
            // $('#modalEdit').modal('hide');
            // table.ajax.reload();
            // alert(response);
            // }
            // });
            // });

            // Delete User
            // $('#pengguna_table').on('click', '.delete', function () {
            // var user_id = $(this).data('user_id');
            // if (confirm('Kamu yakin ingin menghapus pengguna ini?')) {
            // $.ajax({
            // url: '../service/ajax/ajax-pengguna.php',
            // type: 'DELETE',
            // data: {
            // user_id: user_id
            // },
            // success: function (response) {
            // table.ajax.reload();
            // alert(response);
            // }
            // });
            // }
            // });

        });

    </script>

</body>

</html>