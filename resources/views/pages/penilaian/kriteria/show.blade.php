@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Daftar Kriteria</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Kriteria</li>
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
                            Daftar Kriteria
                        </label>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body">
                            <table id="table" class="table table-bordered text-xs text-center">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Nama Kriteria</th>
                                        <th>Posisi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="#" class="btn btn-default btn-xs bg-warning border-dark rounded" data-toggle="modal" data-target="#editModal">
                                                <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                                            </a>
                                        </td>
                                        <td class="text-left">{{ $row->nama_kriteria }}</td>
                                        <td>{{ $row->posisi->nama_posisi }}</td>
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
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModal">New message</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" action="{{ route('kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kriteria" class="col-form-label">Nama Kriteria:</label>
                        <input type="text" class="form-control" id="kriteria" name="kriteria" required>
                    </div>
                    <div class="mb-3">
                        <label for="posisi" class="col-form-label">Posisi:</label>
                        <select name="posisi" class="form-control" id="posisi">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach ($posisi as $row)
                            <option value="{{ $row->id_posisi }}">{{ $row->nama_posisi }}</option>
                            @endforeach
                        </select>
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
<div class="modal fade" id="editModal" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModal">Edit Kriteria</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formUpdate" action="{{ route('kriteria.update', $row->id_kriteria) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kriteria" class="col-form-label">Nama Penyedia:</label>
                        <input type="text" class="form-control" id="kriteria" name="kriteria" value="{{ $row->nama_kriteria }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="posisi" class="col-form-label">Posisi:</label>
                        <select name="posisi" class="form-control" id="posisi">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach ($posisi as $subRow)
                            <option value="{{ $subRow->id_posisi }}" <?php echo $subRow->id_posisi == $row->posisi_id ? 'selected' : ''; ?>>
                                {{ $subRow->nama_posisi }}
                            </option>
                            @endforeach
                        </select>
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
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(event, 'formUpdate')">Submit</button>
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
@endsection
@endsection
