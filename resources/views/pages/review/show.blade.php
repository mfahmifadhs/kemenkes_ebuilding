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
            <div class="col-md-3 form-group">
                <div class="card border border-dark">
                    <div class="card-header">
                        <label class="small m-0">
                            <i class="fas fa-ranking-star"></i> Leaderboard Review
                        </label>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @foreach ($totalReview as $row)
                            <li class="item">
                                <div class="product-img mx-2">
                                    <span class="badge badge-warning">{{ $loop->iteration }}</span>
                                    @if ($row->petugas->foto_pegawai)
                                    <img src="{{ asset('dist/img/foto_pegawai/'. $row->petugas->foto_pegawai) }}" alt="Product Image" class="img-size-50">
                                    @else
                                    <img src="https://cdn-icons-png.flaticon.com/128/3177/3177465.png" alt="Product Image" class="img-size-50">
                                    @endif
                                </div>
                                <div class="product-info text-sm">
                                    <a href="{{ route('pegawai.detail', $row->petugas_id) }}" class="product-title">{{ $row->petugas->nama_pegawai }}
                                        <h4 class="text-primary float-right font-weight-bold">
                                            {{ $row->total }}
                                        </h4>
                                    </a>
                                    <span class="product-description">
                                        {{ $row->petugas->posisi->nama_posisi }}
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9 form-group">
                <div class="card card-primary">
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
                            <table id="table-data" class="table table-bordered text-xs text-center">
                                <thead class="text-uppercase text-center">
                                    <tr>
                                        <th style="width: 0%;">No</th>
                                        <th style="width: 0%;" class="{{ Auth::user()->role_id != 1 ? 'd-none' : ''; }}">Aksi</th>
                                        <th style="width: 0%;">Foto</th>
                                        <th style="width: 10%;">Tanggal</th>
                                        <th>Nama</th>
                                        <th>Posisi</th>
                                        <th>Area Kerja</th>
                                        <th>Nilai</th>
                                        <th>Keterangan</th>
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
        let bulan = $('[name="bulan"]').val();
        let tahun = $('[name="tahun"]').val();
        let posisi = $('[name="posisi"]').val();
        let petugas = $('[name="petugas"]').val();

        loadTable(bulan, tahun, posisi, petugas);

        function loadTable(bulan, tahun, posisi, petugas) {
            $.ajax({
                url: `{{ route('review.select') }}`,
                method: 'GET',
                data: {
                    bulan: bulan,
                    tahun: tahun,
                    posisi: posisi,
                    petugas: petugas
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
                            let role = '{{ Auth::user()->role_id }}';
                            let actionButton = '';
                            let deleteUrl = "{{ route('penilaian.delete', ':id') }}".replace(':id', item.id);
                            actionButton = `
                                <a href="#" class="btn btn-default btn-xs bg-danger rounded border-dark m-1"
                                onclick="confirmLink(event, '${deleteUrl}')">
                                    <i class="fas fa-trash-alt p-1" style="font-size: 12px;"></i>
                                </a>
                             `;
                            tbody.append(`
                                <tr>
                                    <td class="align-middle">${item.no}</td>
                                    <td class="align-middle ${role != 1 ? 'd-none' : ''}">${role == 1 ? actionButton : '' }</td>
                                    <td class="align-middle">${item.foto}</td>
                                    <td class="align-middle">${item.tanggal}</td>
                                    <td class="align-middle text-left">${item.petugas}</td>
                                    <td class="align-middle text-left">${item.posisi}</td>
                                    <td class="align-middle text-left">${item.area}</td>
                                    <td class="align-middle">${item.nilai}</td>
                                    <td class="align-middle text-left">${item.keterangan}</td>
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
                                title: 'penilaian',
                                exportOptions: {
                                    columns: [0, 3, 5, 6, 7, 8, 9, 10],
                                },
                            }, {
                                extend: 'excel',
                                text: ' Excel',
                                className: 'bg-success',
                                title: 'penilaian',
                                exportOptions: {
                                    columns: ':not(:nth-child(2))',
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
