@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Daftar Pegawai</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Pegawai</li>
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
                    <div class="card-header">
                        <label class="card-title">
                            Daftar Pegawai
                        </label>

                        <div class="card-tools">
                            <a href="" class="btn btn-default btn-sm text-dark" data-toggle="modal" data-target="#modalFilter">
                                <i class="fas fa-filter"></i> Filter
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body">
                            <table id="table-data" class="table table-bordered text-xs text-center">
                                <thead class="text-uppercase text-center">
                                    <tr>
                                        <th style="width: 0%;">No</th>
                                        <th style="width: 7%;">Aksi</th>
                                        <th style="width: 0%;">Penyedia</th>
                                        <th style="width: 0%;">Foto</th>
                                        <th style="width: 0%;">ID</th>
                                        <th>Posisi</th>
                                        <th>Nama</th>
                                        <th style="width: 0%;">NIP</th>
                                        <th style="width: 0%;">Gender</th>
                                        <th style="width: 0%;">Email</th>
                                        <th style="width: 10%;">No. Telepon</th>
                                        <th style="width: 10%;">Penempatan</th>
                                        <th style="width: 0%;">Status</th>
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
<div class="modal fade" id="modalFilter" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    @if (!in_array(Auth::user()->role_id, [5, 4]))
                    <div class="form-group">
                        <label class="col-form-label">Pilih Penyedia</label>
                        <select name="penyedia" class="form-control">
                            <option value="">-- Pilih Penyedia --</option>
                            @foreach ($penyedia as $row)
                            <option value="{{ $row->id_penyedia }}" <?php echo $row->id_penyedia == $penyediaSelected ? 'selected' : ''; ?>>
                                {{ $row->nama_penyedia }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="col-form-label">Pilih Posisi</label>
                        <select name="posisi" class="form-control">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach ($posisi as $row)
                            <option value="{{ $row->id_posisi }}" <?php echo $row->id_posisi == $posisiSelected ? 'selected' : ''; ?>>
                                {{ $row->nama_posisi }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Pilih Penempatan</label>
                        <select name="penempatan" class="form-control">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach ($penempatan as $row)
                            <option value="{{ $row->id_penempatan }}" <?php echo $row->id_penempatan == $penempatanSelected ? 'selected' : ''; ?>>
                                {{ $row->nama_penempatan }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Pilih Status</label>
                        <select name="status" class="form-control">
                            <option value="">-- Pilih Status --</option>
                            <option value="true" <?php echo $statusSelected == 'true' ? 'selected' : ''; ?>>True</option>
                            <option value="false" <?php echo $statusSelected == 'false' ? 'selected' : ''; ?>>False</option>
                        </select>
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

<!-- Modal Tambah -->
<div class="modal fade" id="createModal" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModal">Tambah</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="uker" class="col-form-label">Unit Kerja:</label>
                            <select name="uker" class="form-control" id="uker">
                                <option value="7">Biro Umum</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="penyedia" class="col-form-label">Penyedia:</label>
                            <select name="penyedia" class="form-control" id="penyedia">
                                <option value="">-- Pilih Penyedia --</option>
                                @foreach($penyedia as $row)
                                <option value="{{ $row->id_penyedia }}">{{ $row->nama_penyedia }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="penempatan" class="col-form-label">Penempatan:</label>
                            <select name="penempatan" class="form-control" id="penempatan">
                                <option value="">-- Pilih Penempatan --</option>
                                @foreach($penempatan as $row)
                                <option value="{{ $row->id_penempatan }}">{{ $row->nama_penempatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="posisi" class="col-form-label">Posisi:</label>
                            <select name="posisi" class="form-control" id="posisi">
                                <option value="">-- Pilih Posisi --</option>
                                @foreach($posisi as $row)
                                <option value="{{ $row->id_posisi }}">{{ $row->nama_posisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="pegawai" class="col-form-label">*Nama Pegawai:</label>
                            <input type="text" class="form-control" id="pegawai" name="pegawai" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="nip" class="col-form-label">NIP:</label>
                            <input type="text" class="form-control number" id="nip" name="nip" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="jenis_kelamin" class="col-form-label">Jenis Kelamin:</label>
                            <select name="jenis_kelamin" class="form-control" id="jenis_kelamin">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="pria">Pria</option>
                                <option value="wanita">Wanita</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="email" class="col-form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="no_telepon" class="col-form-label">No. Telepon:</label>
                            <input type="text" class="form-control number" id="no_telepon" name="no_telepon">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="tanggal_masuk" class="col-form-label">Tanggal Masuk:</label>
                            <input type="date" class="form-control number" id="tanggal_masuk" name="tanggal_masuk" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="col-form-label">Foto Pegawai:</label> <br>
                            <img id="modal-foto" src="https://cdn-icons-png.flaticon.com/128/149/149071.png" alt="Foto Pegawai" class="img-fluid w-25">

                            <div class="btn btn-warning ml-5 btn-sm mt-1 btn-file border-dark">
                                <i class="fas fa-paperclip"></i> Upload Foto
                                <input type="file" name="foto" class="previewImg" data-preview="modal-foto" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(event, 'form')">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script>
    $("#table").DataTable({
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
            title: 'Pegawai',
            exportOptions: {
                columns: [0, 2, 3, 4],
            },
        }, {
            extend: 'excel',
            text: ' Excel',
            className: 'bg-success',
            title: 'Pegawai',
            exportOptions: {
                columns: ':not(:nth-child(2))'
            },
        }, {
            text: ' Tambah',
            className: 'bg-primary',
            action: function(e, dt, button, config) {
                $('#createModal').modal('show');
            }
        }, ],
        "bDestroy": true
    }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');
</script>

<script>
    $(document).ready(function() {
        let penyedia = $('[name="penyedia"]').val();
        let penempatan = $('[name="penempatan"]').val();
        let posisi = $('[name="posisi"]').val();
        let status = $('[name="status"]').val();

        loadTable(penyedia, posisi, penempatan, status);

        function loadTable(penyedia, posisi, penempatan, status) {
            $.ajax({
                url: `{{ route('pegawai.select') }}`,
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
                            let actionButton = '';
                            let deleteUrl = "{{ route('pegawai.delete', ':id') }}".replace(':id', item.id);
                            actionButton = `
                                <a href="#" class="btn btn-default btn-xs bg-danger rounded border-dark"
                                onclick="confirmRemove(event, '${deleteUrl}')">
                                    <i class="fas fa-trash-alt p-1" style="font-size: 12px;"></i>
                                </a>
                             `;
                            tbody.append(`
                                <tr>
                                    <td class="align-middle">${item.no}</td>
                                    <td class="align-middle">${item.aksi}</td>
                                    <td class="align-middle">${item.penyedia}</td>
                                    <td class="align-middle">${item.foto}</td>
                                    <td class="align-middle text-left">${item.kode}</td>
                                    <td class="align-middle text-left">${item.posisi}</td>
                                    <td class="align-middle text-left">${item.pegawai}</td>
                                    <td class="align-middle">${item.nip}</td>
                                    <td class="align-middle">${item.jenisKelamin}</td>
                                    <td class="align-middle">${item.email}</td>
                                    <td class="align-middle">${item.noTelp}</td>
                                    <td class="align-middle text-left">${item.penempatan}</td>
                                    <td class="align-middle">${item.status}</td>
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
                                title: 'kegiatan',
                                exportOptions: {
                                    columns: [0, 2, 3, 4],
                                },
                            }, {
                                extend: 'excel',
                                text: ' Excel',
                                className: 'bg-success',
                                title: 'kegiatan',
                                exportOptions: {
                                    columns: [0, 2, 3, 4, 5, 6, 7, 8],
                                },
                            }, {
                                text: ' Tambah',
                                className: 'bg-primary',
                                action: function(e, dt, button, config) {
                                    $('#createModal').modal('show');
                                }
                            }, {
                                text: 'QR Code',
                                action: function(e, dt, node, config) {
                                    Swal.fire({
                                        title: 'Download...',
                                        text: 'Mohon menunggu.',
                                        icon: 'info',
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });
                                    window.location.href = "{{ route('pegawai.qrcode', '*') }}";
                                },
                                className: 'bg-default',
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
