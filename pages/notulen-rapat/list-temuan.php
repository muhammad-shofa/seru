<?php
include "../../service/connection.php";
include "../../service/select.php";
include "../../service/insert.php";
include "../../service/update.php";
include "../../service/delete.php";
session_start();

// check login
if ($_SESSION["is_login"] == false) {
    header("location: ../../index.php");
}

// logout
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location: ../../index.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Seru | Notulen Rapat</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .dataTables_filter {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .dt-buttons {
            margin-right: 10px;
            margin-left: 10px;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include "../../layout/sidebar.php" ?>


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- navbar -->
                <?php include "../../layout/navbar.php" ?>

                <!-- Begin Page Content -->
                <div class="container-fluid" id="mainDashboard">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Notulen Rapat List Temuan</h1>
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
                                    <table class="table table-bordered text-dark" id="temuanTableNotulenRapat"
                                        width="100%" cellspacing="0">
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
                                                <th>Prioritas</th>
                                                <th>Keterangan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
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
                                                <input type="hidden" id="update_temuan_id" name="temuan_id">
                                                <div class="form-floating">
                                                    <label for="update_dokumentasi_tl">Tindak Lanjut
                                                        Perbaikan :
                                                    </label>
                                                    <textarea class="form-control" id="update_dokumentasi_tl"
                                                        name="dokumentasi_tl"
                                                        style="height: 200px; resize: none;"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status :</label>
                                                    <select class="form-control select2" name="status"
                                                        id="update_status" style="width: 100%;">
                                                        <option value="OPEN">OPEN</option>
                                                        <option value="CLOSE">CLOSE</option>
                                                        <option value="ON_PROGRESS">ON PROGRESS</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="update_dokumentasi_gambar"
                                                        class="form-label">Dokumentasi :</label>
                                                    <input class="form-control" type="file"
                                                        id="update_dokumentasi_gambar" name="dokumentasi_gambar">
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

                            <!-- Modal edit -->
                            <div class="modal fade" id="modalEdit">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="formEdit" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <!-- method="post" -->
                                                <input type="hidden" id="edit_temuan_id" name="temuan_id">
                                                <div class="form-group">
                                                    <label for="edit_tanggal">Tanggal :</label>
                                                    <input type="date" class="form-control" id="edit_tanggal"
                                                        name="tanggal">
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_sumber_temuan">Sumber Temuan :</label>
                                                    <select class="form-control select2" name="sumber_temuan"
                                                        id="edit_sumber_temuan" style="width: 100%;">
                                                        <option value="MWT">MWT</option>
                                                        <option value="MOD">MOD</option>
                                                        <option value="PATUH">PATUH</option>
                                                        <option value="NOTULEN_RAPAT">NOTULEN RAPAT</option>
                                                        <option value="LAINNYA">LAINNYA</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_temuan">Temuan :</label>
                                                    <input type="text" class="form-control" id="edit_temuan"
                                                        name="temuan">
                                                </div>
                                                <div class="form-floating">
                                                    <label for="edit_rekomendasi_tindak_lanjut">Rekomendasi Tindak
                                                        Lanjut :
                                                    </label>
                                                    <textarea class="form-control" id="edit_rekomendasi_tindak_lanjut"
                                                        name="rekomendasi_tindak_lanjut"
                                                        style="height: 200px; resize: none;"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_status">Status :</label>
                                                    <select class="form-control select2" name="status" id="edit_status"
                                                        style="width: 100%;">
                                                        <option value="OPEN">OPEN</option>
                                                        <option value="CLOSE">CLOSE</option>
                                                        <option value="ON_PROGRESS">ON PROGRESS</option>
                                                    </select>
                                                </div>
                                                <!-- rubahlah pic di bawah ini enjadi checkbox -->
                                                <div class="form-group">
                                                    <label for="edit_pic">PIC :</label>
                                                    <?php
                                                    // Array pilihan PIC yang tersedia
                                                    $pic_options = ['RSD', 'PMS', 'HSSE', 'SSGA', 'QQ', 'FLEET', 'ALL'];

                                                    foreach ($pic_options as $pic) {
                                                        echo '<input type="checkbox" name="pic[]" value="' . $pic . '" id="edit_' . $pic . '">';
                                                        echo '<label class="form-check-label mr-2" for="edit_' . $pic . '">' . $pic . '</label><br>';
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_deadline">Deadline :</label>
                                                    <input type="date" class="form-control" id="edit_deadline"
                                                        name="deadline">
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_dokumentasi_tl">Dokumentasi TL :</label>
                                                    <input type="text" class="form-control" id="edit_dokumentasi_tl"
                                                        name="dokumentasi_tl">
                                                </div>
                                                <div class="form-floating">
                                                    <label for="edit_keterangan">Keterangan :
                                                    </label>
                                                    <textarea class="form-control" id="edit_keterangan"
                                                        name="keterangan"
                                                        style="height: 200px; resize: none;"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-primary" name="simpanEdit"
                                                    id="simpanEdit">Simpan Edit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- Modal edit End -->

                            <!-- <div class="update-container">

                            </div> -->
                        </div>
                    </div>
                    <!-- End of listTemuan -->
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
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/chart-area-demo.js"></script>
    <script src="../../js/demo/chart-pie-demo.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Buttons Extension JS -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <!-- JSZip for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <!-- Button for Excel export -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>

    <!-- Page level custom scripts -->
    <!-- <script src="../js/demo/datatables-demo.js"></script> -->

    <script>
        $(document).ready(function () {
            var tableListTemuanNotulenRapat = $('#temuanTableNotulenRapat').DataTable({
                "ajax": "../../service/ajax/ajax-temuan.php?sumber=notulen_rapat",
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
                    "data": "deadline_tw"
                },
                {
                    "data": "dokumentasi_tl"
                },
                {
                    "data": "prioritas"
                },
                {
                    "data": "keterangan"
                },
                {
                    "data": "action_edit",
                    "orderable": true,
                    "searchable": true
                }
                ],
                dom: '<"d-flex justify-content-end"fB>rtip', // Posisi tombol di sebelah search bar
                buttons: [
                    {
                        text: 'Unduh Excel',
                        className: 'btn btn-success',
                        action: function (e, dt, node, config) {
                            window.location.href = '../../service/export/export-excel-dashboard.php';
                        }
                    }
                ],
                "responsive": true
            });

            // Menampilkan modal Update
            $('#temuanTableNotulenRapat').on('click', '.update', function () {
                let temuan_id = $(this).data('temuan_id');
                $.ajax({
                    url: '../../service/ajax/ajax-temuan.php?temuan_id=' + temuan_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#update_temuan_id').val(data.temuan_id);
                        $('#update_dokumentasi_tl').val(data.dokumentasi_tl);
                        $('#update_status').val(data.status);
                        // $('#update_dokumentasi_gambar').val(data.dokumentasi_gambar);
                        $('#modalUpdate').modal('show');
                    }
                });
            });

            // Simpan update Dokumentasi TL
            $('#simpanUpdate').click(function () {
                var formData = new FormData($('#formUpdate')[0]);

                formData.append('simpanUpdate', true);

                $.ajax({
                    url: '../../service/ajax/ajax-temuan.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modalUpdate').modal('hide');
                        tableListTemuanNotulenRapat.ajax.reload();
                        $('#formUpdate')[0].reset();
                        alert(response);
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Ambil data dan Menampilkan modal Edit
            $('#temuanTableNotulenRapat').on('click', '.edit', function () {
                let temuan_id = $(this).data('temuan_id');
                $.ajax({
                    url: '../../service/ajax/ajax-temuan.php?temuan_id=' + temuan_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#edit_temuan_id').val(data.temuan_id);
                        $('#edit_tanggal').val(data.tanggal);
                        $('#edit_sumber_temuan').val(data.sumber_temuan);
                        $('#edit_temuan').val(data.temuan);
                        $('#edit_rekomendasi_tindak_lanjut').val(data.rekomendasi_tindak_lanjut);
                        $('#edit_status').val(data.status);
                        $('#edit_deadline').val(data.deadline);
                        $('#edit_dokumentasi_tl').val(data.dokumentasi_tl);
                        $('#edit_keterangan').val(data.keterangan);

                        // Proses untuk checkbox PIC
                        let picArray = data.pic.split(','); // Memecah string PIC menjadi array
                        $('input[name="pic[]"]').prop('checked', false); // Uncheck semua checkbox PIC

                        // Centang checkbox berdasarkan nilai yang tersimpan
                        picArray.forEach(function (pic) {
                            $('#edit_' + pic).prop('checked', true);
                        });

                        $('#modalEdit').modal('show'); // tampilkan modal Edit
                    }
                });
            });

            // Simpan Edit
            $('#simpanEdit').click(function () {
                var data = $('#formEdit').serialize();
                $.ajax({
                    url: '../../service/ajax/ajax-temuan.php',
                    type: 'PUT',
                    data: data,
                    success: function (response) {
                        $('#modalEdit').modal('hide');
                        tableListTemuanNotulenRapat.ajax.reload();
                        alert(response);
                    }
                });
            });

        });

    </script>

</body>

</html>