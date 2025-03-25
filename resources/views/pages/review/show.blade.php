@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Daftar Review</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Review</li>
                </ol>

            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container-fluid">
        <div class="row">
            @foreach ($totalReview as $row)
            <div class="col-md-3 col-6">
                <div class="info-box border border-dark">
                    <span class="my-auto mx-3">
                        <h4 class="text-info font-weight-bold mt-2">{{ $loop->iteration }}</h4>
                    </span>
                    <span class="info-box-icon bg-info elevation-1">
                        @if ($row->petugas->foto_pegawai)
                        <img src="{{ asset('dist/img/foto_pegawai/'. $row->petugas->foto_pegawai) }}" alt="Product Image" class="img-size-50">
                        @else
                        <img src="https://cdn-icons-png.flaticon.com/128/3177/3177465.png" alt="Product Image" class="img-size-50">
                        @endif
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">
                            {{ $row->petugas->nama_pegawai }}
                            <h6 class="text-xs">{{ $row->petugas->posisi->nama_posisi }}</h6>
                        </span>
                        <span class="info-box-number mt-0">
                            <i class="fas fa-star text-warning"></i> {{ $row->total }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card card-primary border border-dark">
            <div class="card-header">
                <label class="card-title">
                    Daftar Review
                </label>

                <div class="card-tools">
                    <a href="" class="btn btn-default btn-sm text-dark" data-toggle="modal" data-target="#modalFilter">
                        <i class="fas fa-filter"></i> Filter
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <div class="card-body">
                    <table id="table-data" class="table table-bordered text-center">
                        <thead class="text-uppercase text-center">
                            <tr>
                                <th style="width: 0%;">No</th>
                                <th style="width: 10%;" class="{{ Auth::user()->role_id != 1 ? 'd-none' : ''; }}">Aksi</th>
                                <th style="width: 5%;">Foto</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Review</th>
                                <th>Nilai</th>
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
            <form method="GET" action="{{ route('review') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-sm">Pilih Bulan</label>
                        <select name="bulan" class="form-control border-dark rounded">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $monthNumber)
                            @php $rowBulan = Carbon\Carbon::create()->month($monthNumber); @endphp
                            <option value="{{ $rowBulan->isoFormat('MM') }}" <?php echo $bulan == $rowBulan->isoFormat('M') ? 'selected' : '' ?>>
                                {{ $rowBulan->isoFormat('MMMM') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-sm">Pilih Tahun</label>
                        <select name="tahun" class="form-control border-dark rounded">
                            <option value="">Semua Tahun</option>
                            @foreach(range(2025,2026) as $yearNumber)
                            @php $rowTahun = Carbon\Carbon::create()->year($yearNumber); @endphp
                            <option value="{{ $rowTahun->isoFormat('Y') }}" <?php echo $tahun == $rowTahun->isoFormat('Y') ? 'selected' : '' ?>>
                                {{ $rowTahun->isoFormat('Y') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Pilih Posisi</label>
                        <select id="posisi" class="form-control" name="posisi" style="width: 100%;" required>
                            @foreach ($posisiList as $row)
                            <option value="{{ $row->id_posisi }}" <?= $row->id_posisi == $posisi ? 'selected' : ''; ?>>
                                {{ $row->nama_posisi }}
                            </option>
                            @endforeach
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

@section('js')
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
                        response.sort((a, b) => Number(b.review || 0) - Number(a.review || 0));
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
                                    <td class="align-middle">${index + 1}</td>
                                    <td class="align-middle">${item.aksi}</td>
                                    <td class="align-middle">${item.foto}</td>
                                    <td class="align-middle text-left">${item.pegawai}</td>
                                    <td class="align-middle">${item.posisi}</td>
                                    <td class="align-middle"><i class="fas fa-star text-warning"></i> ${item.review}</td>
                                    <td class="align-middle"><i class="fas fa-star text-warning"></i> ${item.nilai}</td>
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
