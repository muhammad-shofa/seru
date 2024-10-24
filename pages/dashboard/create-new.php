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

    <title>Seru | Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Create New</h1>
                    </div>

                    <!-- Begin of Create New -->
                    <div class="p-2" id="createNewContent">
                        <!-- DataTales -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Create New</h6>

                                <!-- btn trigger modal tambah berita -->
                                <button type="button" class="btn btn-primary my-2" data-toggle="modal"
                                    data-target="#modalTambah">
                                    Tambah Temuan
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-dark" id="createNewTable" width="100%"
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

                            <!-- Modal create new start -->
                            <div class="modal fade" id="modalTambah">
                                <div class="modal-dialog">
                                    <div class="modal-content text-dark">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Tambah Temuan</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="formTambah" method="POST">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="tambah_tanggal">Tanggal :</label>
                                                    <input type="date" class="form-control" id="tambah_tanggal"
                                                        name="tambah_tanggal">
                                                </div>
                                                <div class="form-group">
                                                    <label for="tambah_sumber_temuan">Sumber Temuan :</label>
                                                    <select class="form-control select2" name="tambah_sumber_temuan"
                                                        id="tambah_sumber_temuan" style="width: 100%;">
                                                        <option value="MWT">MWT</option>
                                                        <option value="MOD">MOD</option>
                                                        <option value="PATUH">PATUH</option>
                                                        <option value="NOTULEN_RAPAT">NOTULEN RAPAT</option>
                                                        <option value="LAINNYA">LAINNYA</option>
                                                    </select>
                                                </div>
                                                <div class="form-floating">
                                                    <label for="tambah_temuan">
                                                        Temuan :
                                                    </label>
                                                    <textarea class="form-control" id="tambah_temuan"
                                                        name="tambah_temuan"
                                                        style="height: 85px; resize: none;"></textarea>
                                                </div>
                                                <div class="form-floating">
                                                    <label for="tambah_rekomendasi_tindak_lanjut">
                                                        Rekomendasi Tindak Lanjut :
                                                    </label>
                                                    <textarea class="form-control" id="tambah_rekomendasi_tindak_lanjut"
                                                        name="tambah_rekomendasi_tindak_lanjut"
                                                        style="height: 85px; resize: none;"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tambah_status">Status :</label>
                                                    <select class="form-control select2" name="tambah_status"
                                                        id="tambah_status" style="width: 100%;">
                                                        <option value="OPEN">OPEN</option>
                                                        <option value="CLOSE">CLOSE</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>PIC :</label><br>
                                                    <input type="checkbox" name="tambah_pic[]" value="RSD" id="rsd">
                                                    <label class="form-check-label mr-2" for="rsd">
                                                        RSD
                                                    </label>
                                                    <input type="checkbox" name="tambah_pic[]" value="PMS" id="pms">
                                                    <label class="form-check-label mr-2" for="pms">
                                                        PMS
                                                    </label>
                                                    <input type="checkbox" name="tambah_pic[]" value="HSSE" id="hsse">
                                                    <label class="form-check-label mr-2" for="hsse">
                                                        HSSE
                                                    </label>
                                                    <input type="checkbox" name="tambah_pic[]" value="SSGA" id="ssga">
                                                    <label class="form-check-label mr-2" for="ssga">
                                                        SSGA
                                                    </label>
                                                    <input type="checkbox" name="tambah_pic[]" value="QQ" id="qq">
                                                    <label class="form-check-label mr-2" for="qq">
                                                        QQ
                                                    </label>
                                                    <input type="checkbox" name="tambah_pic[]" value="FLEET" id="fleet">
                                                    <label class="form-check-label mr-2" for="fleet">
                                                        FLEET
                                                    </label>
                                                    <input type="checkbox" name="tambah_pic[]" value="ALL" id="all">
                                                    <label class="form-check-label mr-2" for="all">
                                                        ALL
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tambah_deadline">Deadline :</label>
                                                    <input type="date" class="form-control" id="tambah_deadline"
                                                        name="tambah_deadline">
                                                </div>
                                                <div class="form-group">
                                                    <label for="tambah_prioritas">Prioritas :</label>
                                                    <select class="form-control select2" name="tambah_prioritas"
                                                        id="tambah_prioritas" style="width: 100%;">
                                                        <option value="TINGGI">TINGGI</option>
                                                        <option value="SEDANG">SEDANG</option>
                                                        <option value="RENDAH">RENDAH</option>
                                                    </select>
                                                </div>
                                                <div class="form-floating">
                                                    <label for="tambah_keterangan">
                                                        Keterangan :
                                                    </label>
                                                    <textarea class="form-control" id="tambah_keterangan"
                                                        name="tambah_keterangan"
                                                        style="height: 85px; resize: none;"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-success" name="tambahTemuan"
                                                    id="tambahTemuan">Tambahkan</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- Modal create new End -->

                            <!-- <div class="update-container">

                            </div> -->
                        </div>
                    </div>
                    <!-- End of Create New -->

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

    <script>
        $(document).ready(function () {
            // AJAX TABLE CREATE NEW
            var tableListTemuan = $('#createNewTable').DataTable({
                "ajax": "../../service/ajax/ajax-create-new.php",
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
                    "data": "action_create_new",
                    "orderable": true,
                    "searchable": true
                }
                ],
                "responsive": true
            });

            // Tambah temuan
            $('#tambahTemuan').click(function () {
                var data = $('#formTambah').serialize();
                $.ajax({
                    url: '../../service/ajax/ajax-create-new.php',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        $('#modalTambah').modal('hide');
                        tableListTemuan.ajax.reload();
                        $('#formTambah')[0].reset();
                        alert(response);
                    }
                });
            });

            // Delete List Temuan
            $('#createNewTable').on('click', '.delete', function () {
                var temuan_id = $(this).data('temuan_id');
                if (confirm('Kamu yakin ingin menghapus temuan ini?')) {
                    $.ajax({
                        url: '../../service/ajax/ajax-create-new.php',
                        type: 'DELETE',
                        data: {
                            temuan_id: temuan_id
                        },
                        success: function (response) {
                            tableListTemuan.ajax.reload();
                            alert(response);
                        }
                    });
                }
            });
        });

    </script>

</body>

</html>