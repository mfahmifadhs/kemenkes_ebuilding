@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Daftar Temuan</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Temuan</li>
                </ol>

            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header border border-dark">
                        <label class="card-title">
                            Daftar Temuan
                        </label>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body border border-dark">
                            <table id="table-data" class="table table-bordered text-xs text-center">
                                <thead class="text-uppercase text-center">
                                    <tr>
                                        <th style="width: 0%;">No</th>
                                        <th style="width: 0%;">Foto</th>
                                        <th style="width: 0%;">Tanggal</th>
                                        <th style="width: 0%;">Kode</th>
                                        <th style="width: 0%;">Penyedia</th>
                                        <th>Petugas</th>
                                        <th style="width: 0%;">Posisi</th>
                                        <th style="width: 0%;">Penempatan</th>
                                        <th>Temuan</th>
                                        <th>Pengawas</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data == 0)
                                    <tr class="text-center">
                                        <td colspan="13">Tidak ada data</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="13">Sedang mengambil data ...</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-filter"></i> Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="GET" action="{{ route('pegawai') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Pilih Barang</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('pegawai') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-undo"></i> Muat
                    </a>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function() {
        let penyedia   = $('[name="penyedia"]').val();
        let penempatan = $('[name="penempatan"]').val();
        let posisi = $('[name="posisi"]').val();
        let status = $('[name="status"]').val();

        loadTable(penyedia, posisi, penempatan, status);

        function loadTable(penyedia, posisi, penempatan, status) {
            $.ajax({
                url: `{{ route('temuan.select') }}`,
                method: 'GET',
                data: {
                    penyedia: penyedia,
                    posisi: posisi,
                    penempatan: penempatan,
                    status: status
                },
                dataType: 'json',
                success: function(response) {
                    let tbody = $('.table tbody');
                    tbody.empty();

                    if (response.message) {
                        tbody.append(`
                        <tr>
                            <td colspan="9">${response.message}</td>
                        </tr>
                    `);
                    } else {
                        // Jika ada data
                        $.each(response, function(index, item) {
                            tbody.append(`
                                <tr>
                                    <td class="align-middle">${item.no} ${item.statusIcon}</td>
                                    <td class="align-middle">${item.foto}</td>
                                    <td class="align-middle">${item.tanggal}</td>
                                    <td class="align-middle">${item.kode}</td>
                                    <td class="align-middle text-left">${item.penyedia}</td>
                                    <td class="align-middle text-left">${item.petugas}</td>
                                    <td class="align-middle text-left">${item.posisi}</td>
                                    <td class="align-middle text-left">${item.penempatan}</td>
                                    <td class="align-middle text-left">${item.temuan} temuan</td>
                                    <td class="align-middle text-left">${item.pengawas}</td>
                                    <td class="align-middle text-left">${item.status}</td>
                                </tr>
                            `);
                        });

                        $("#table-data").DataTable({
                            "responsive": false,
                            "lengthChange": true,
                            "autoWidth": false,
                            "info": true,
                            "paging": true,
                            "searching": true,
                            buttons: [{
                                extend: 'pdf',
                                text: ' PDF',
                                pageSize: 'A4',
                                className: 'bg-danger',
                                title: 'temuan',
                            }, {
                                extend: 'excel',
                                text: ' Excel',
                                className: 'bg-success',
                                title: 'temuan'
                            }, ],
                            "bDestroy": true
                        }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    });
</script>
@endsection
@endsection
