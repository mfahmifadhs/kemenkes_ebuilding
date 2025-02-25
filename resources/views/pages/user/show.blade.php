@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Daftar Pengguna</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengguna</li>
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
                            Daftar Pengguna
                        </label>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body border border-dark">
                            <table id="table" class="table table-bordered text-xs text-center">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Posisi</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="#" class="btn btn-default btn-xs bg-warning border-dark rounded" data-toggle="modal" data-target="#editModal-{{ $row->id }}">
                                                <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                            </a>
                                        </td>
                                        <td>{{ $row->created_at }}</td>
                                        <td class="text-left">{{ $row->pegawai->nama_pegawai }}</td>
                                        <td class="text-left">{{ $row->pegawai->posisi?->nama_posisi }}</td>
                                        <td class="text-left">{{ $row->username }}</td>
                                        <td>{{ $row->status }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="createModal" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModal">Tambah</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role" class="col-form-label">Pilih Role:</label>
                        <select name="role" class="form-control" id="role" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($role as $row)
                            <option value="{{ $row->id_role }}">{{ $row->nama_role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pegawai" class="col-form-label">Pilih Pegawai:</label>
                        <select class="form-control pegawai" name="pegawai" style="width: 100%;" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="col-form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label>Password*</label>
                        <div class="input-group">
                            <input type="password" class="form-control border-dark rounded password" name="password" placeholder="Masukkan Password" required>
                            <div class="input-group-append border rounded border-dark">
                                <span class="input-group-text h-100 rounded-0 bg-white">
                                    <i class="fas fa-eye eye-icon-pass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi Pengguna"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Status:</label>
                        <div class="input-group">
                            <input type="radio" id="true" name="status" value="true">
                            <label for="true" class="my-auto ml-2 mr-5">Aktif</label>

                            <input type="radio" id="false" name="status" value="False">
                            <label for="false" class="my-auto ml-2">Tidak Aktif</label>
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

<!-- Modal Edit -->
@foreach($data as $row)
<div class="modal fade" id="editModal-{{ $row->id }}" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModal">Edit Pengguna</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formUpdate-{{ $row->id }}" action="{{ route('user.update', $row->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role" class="col-form-label">Pilih Role:</label>
                        <select name="role" class="form-control" id="role" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($role as $subRow)
                            <option value="{{ $subRow->id_role }}" <?php echo $subRow->id_role == $row->role_id ? 'selected' : ''; ?>>
                                {{ $subRow->nama_role }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pegawai" class="col-form-label">Pilih Pegawai:</label>
                        <select class="form-control pegawai" name="pegawai" style="width: 100%;" required>
                            <option value="{{ $row->pegawai_id }}">
                                {{ ($row->pegawai->posisi ? $row->pegawai->posisi->nama_posisi . ' - ' : '') . $row->pegawai->nama_pegawai }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="col-form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ $row->username }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Password*</label>
                        <div class="input-group">
                            <input type="password" class="form-control border-dark rounded password" name="password" value="{{ $row->password_text }}" placeholder="Masukkan Password" required>
                            <div class="input-group-append border rounded border-dark">
                                <span class="input-group-text h-100 rounded-0 bg-white">
                                    <i class="fas fa-eye eye-icon-pass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi Pengguna">{{ $row->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Status:</label>
                        <div class="input-group">
                            <input type="radio" id="true" name="status" value="true" <?php echo $row->status == 'true' ? 'checked' : ''; ?>>
                            <label for="true" class="my-auto ml-2 mr-5">Aktif</label>

                            <input type="radio" id="false" name="status" value="False" <?php echo $row->status == 'false' ? 'checked' : ''; ?>>
                            <label for="false" class="my-auto ml-2">Tidak Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(event, 'formUpdate-{{ $row->id }}')">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

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
            title: 'penyedia',
            exportOptions: {
                columns: [0, 2, 3, 4],
            },
        }, {
            extend: 'excel',
            text: ' Excel',
            className: 'bg-success',
            title: 'penyedia',
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
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
        $(".pegawai").select2({
            placeholder: "Cari Pegawai...",
            allowClear: true,
            ajax: {
                url: "{{ route('pegawai.select') }}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response.map(function(item) {
                            return {
                                id: item.id,
                                text: item.posisi ? item.posisi + ' - ' + item.pegawai : item.pegawai
                            };
                        })
                    };
                },
                cache: true
            }
        })

        $(".pegawai").each(function() {
            let selectedId = $(this).find("option:selected").val();
            let selectedText = $(this).find("option:selected").text();

            if (selectedId) {
                let newOption = new Option(selectedText, selectedId, true, true);
                $(this).append(newOption).trigger('change');
            }
        });
    });
</script>
@endsection
@endsection
