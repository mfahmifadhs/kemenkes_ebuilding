@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid col-md-8">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Edit Pegawai</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('pegawai') }}"> Daftar</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>

            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container-fluid col-md-8">
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header border border-dark">
                        <label class="card-title">
                            Edit Pegawai
                        </label>
                    </div>
                    <div class="table-responsive">
                        <form id="form" action="{{ route('pegawai.update', $data->id_pegawai) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body border border-dark">
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
                                            <option value="{{ $row->id_penyedia }}" <?php echo $data->penyedia_id == $row->id_penyedia ? 'selected' : ''; ?>>
                                                {{ $row->nama_penyedia }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="penempatan" class="col-form-label">Penempatan:</label>
                                        <select name="penempatan" class="form-control" id="penempatan">
                                            <option value="">-- Pilih Penempatan --</option>
                                            @foreach($penempatan as $row)
                                            <option value="{{ $row->id_penempatan }}" <?php echo $data->penempatan_id == $row->id_penempatan ? 'selected' : ''; ?>>
                                                {{ $row->nama_penempatan }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="posisi" class="col-form-label">Posisi:</label>
                                        <select name="posisi" class="form-control" id="posisi">
                                            <option value="">-- Pilih Posisi --</option>
                                            @foreach($posisi as $row)
                                            <option value="{{ $row->id_posisi }}" <?php echo $data->posisi_id == $row->id_posisi ? 'selected' : ''; ?>>
                                                {{ $row->nama_posisi }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="pegawai" class="col-form-label">*Nama Pegawai:</label>
                                        <input type="text" class="form-control" id="pegawai" name="pegawai" value="{{ $data->nama_pegawai }}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="nip" class="col-form-label">NIP:</label>
                                        <input type="text" class="form-control number" id="nip" name="nip" value="{{ $data->nip }}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="jenis_kelamin" class="col-form-label">Jenis Kelamin:</label>
                                        <select name="jenis_kelamin" class="form-control" id="jenis_kelamin">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="pria" <?php echo $data->jenis_kelamin == 'pria' ? 'selected' : ''; ?>>Pria</option>
                                            <option value="wanita" <?php echo $data->jenis_kelamin == 'wanita' ? 'selected' : ''; ?>>Wanita</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="email" class="col-form-label">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $data->email }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="no_telepon" class="col-form-label">No. Telepon:</label>
                                        <input type="text" class="form-control number" id="no_telepon" name="no_telepon" value="{{ $data->no_telepon }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="tanggal_masuk" class="col-form-label">Tanggal Masuk:</label>
                                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ $data->tanggal_masuk }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="col-form-label">Foto Pegawai:</label> <br>
                                        @if (!$data->foto_pegawai)
                                        <img id="modal-foto" src="https://cdn-icons-png.flaticon.com/128/149/149071.png" alt="Foto Pegawai" class="img-fluid w-25">
                                        @else
                                        <img id="modal-foto" src="{{ asset('dist/img/foto_pegawai/'. $data->foto_pegawai) }}" alt="Foto Pegawai" class="img-fluid w-25">
                                        @endif

                                        <div class="btn btn-warning ml-5 btn-sm mt-1 btn-file border-dark">
                                            <i class="fas fa-paperclip"></i> Upload Foto
                                            <input type="file" name="foto" class="previewImg" data-preview="modal-foto" accept="image/*">
                                            <input type="hidden" name="foto_pegawai" value="{{ $data->foto_pegawai }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="col-form-label">Status:</label> <br>
                                        <div class="input-group">
                                            <input type="radio" id="true" name="status" value="true" <?php echo $data->status == 'true' ? 'checked' : ''; ?>>
                                            <label for="true" class="my-auto ml-2 mr-5">Aktif</label>

                                            <input type="radio" id="false" name="status" value="false" <?php echo $data->status == 'false' ? 'checked' : ''; ?>>
                                            <label for="false" class="my-auto ml-2">Tidak Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border border-dark text-right">
                                <button type="button" class="btn btn-primary" onclick="confirmSubmit(event, 'form')">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
        let penyedia = $('#penyedia').val();
        let posisi = $('#posisi').val();
        let status = $('#status').val();

        loadTable(penyedia, posisi, status);

        function loadTable(penyedia, posisi, status) {
            $.ajax({
                url: `{{ route('pegawai.select') }}`,
                method: 'GET',
                data: {
                    penyedia: penyedia,
                    posisi: posisi,
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
                                    <td class="align-middle">${item.notelp}</td>
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
