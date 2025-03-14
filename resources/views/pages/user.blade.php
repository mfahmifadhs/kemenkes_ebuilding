@extends('layouts.app')

@section('content')

<!-- Main content -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4 class="font-weight-bold mb-4 text-capitalize">
                    Selamat Datang, {{ ucfirst(strtolower(Auth::user()->pegawai->nama_pegawai)) }}
                </h4>
            </div>

            <div class="col-md-2">
                <a href="{{ route('penilaian.create') }}" class="btn btn-primary btn-block border-dark p-3 form-group">
                    <span class="font-weight-bold">
                        <i class="fas fa-plus-circle"></i> Mulai Penilaian
                    </span>
                </a>

                <div class="info-box mb-3 bg-warning border border-dark">
                    <span class="info-box-icon"><i class="fas fa-file"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-number">{{ $totalTemuan->count() }}</span>
                        <span class="info-box-text text-xs"><b>Total Seluruh Penilaian</b></span>
                    </div>
                </div>

                <div class="info-box mb-3 bg-warning border border-dark">
                    <span class="info-box-icon"><i class="fas fa-file text-success"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-number">{{ $totalTemuan->where('status', 'true')->count() }}</span>
                        <span class="info-box-text text-xs"><b>Total Penilaian Diterima</b></span>
                    </div>
                </div>

                <div class="info-box mb-3 bg-warning border border-dark">
                    <span class="info-box-icon"><i class="fas fa-file text-danger"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-number">{{ $totalTemuan->where('status', 'false')->count() }}</span>
                        <span class="info-box-text text-xs"><b>Total Penilaian Ditolak</b></span>
                    </div>
                </div>
            </div>

            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            @if (Auth::user()->role_id != 4 || (Auth::user()->role_id == 4 && Auth::user()->pegawai->posisi_id == 11))
                            <div class="col-md-4">
                                <div class="info-box border border-dark">
                                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Security</span>
                                        <span class="info-box-number">
                                            {{ $posisi->where('posisi_id', 5)->count() ?? 0 }}
                                            <small>pegawai</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if (Auth::user()->role_id != 4 || (Auth::user()->role_id == 4 && Auth::user()->pegawai->posisi_id == 10))
                            <div class="col-md-4">
                                <div class="info-box border border-dark">
                                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Cleaning Service</span>
                                        <span class="info-box-number">
                                            {{ $posisi->where('posisi_id', 3)->count() ?? 0 }}
                                            <small>pegawai</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box border border-dark">
                                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Gardener</span>
                                        <span class="info-box-number">
                                            {{ $posisi->where('posisi_id', 6)->count() ?? 0 }}
                                            <small>pegawai</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box border border-dark">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-signature"></i></span>
                                    <div class="info-box-content">
                                        <span class="p-0" style="margin-top: 0%;">Total Penilaian Harian
                                            <h6 class="text-xs">Cleaning Service</h6>
                                        </span>
                                        <span class="info-box-number">
                                            {{ Auth::user()->pegawai->penilaianHarian->count() }} / {{ $posisi->where('posisi_id', 3)->count() ?? 0 }}
                                            <small>pegawai</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box border border-dark">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-signature"></i></span>
                                    <div class="info-box-content">
                                        <span class="p-0" style="margin-top: 0%;">Total Penilaian Harian
                                            <h6 class="text-xs">Gardener / Taman</h6>
                                        </span>
                                        <span class="info-box-number">
                                            {{ Auth::user()->pegawai->penilaianHarian->count() }} / {{ $posisi->where('posisi_id', 6)->count() ?? 0 }}
                                            <small>pegawai</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
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
                            <div class="card-body p-0" style="overflow-y: auto; height: 30vh;">
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
                            <!-- /.card-body -->
                            <div class="card-footer text-center">
                                <a href="javascript:void(0)" class="uppercase">View All Products</a>
                            </div>
                            <!-- /.card-footer -->
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border border-dark">
                            <div class="card-header">
                                <label class="small m-0">
                                    <i class="fas fa-ranking-star"></i> Leaderboard Reviewer
                                </label>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow-y: auto; height: 30vh;">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    @foreach ($totalReviewer as $row)
                                    <li class="item">
                                        <div class="product-img mx-2">
                                            <span class="badge badge-warning">{{ $loop->iteration }}</span>
                                            <img src="https://cdn-icons-png.flaticon.com/128/3177/3177465.png" alt="Product Image" class="img-size-50">
                                        </div>
                                        <div class="product-info text-sm">
                                            <a href="#" class="product-title">{{ $row->no_telepon }}
                                                <h4 class="text-primary float-right font-weight-bold">
                                                    {{ $row->total }}
                                                </h4>
                                            </a>
                                            <span class="product-description">
                                                Reviewer
                                            </span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-center">
                                <a href="javascript:void(0)" class="uppercase">View All Products</a>
                            </div>
                            <!-- /.card-footer -->
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border border-dark">
                            <div class="card-header">
                                <label class="small m-0">
                                    <i class="fas fa-ranking-star"></i> Leaderboard Penilaian
                                </label>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow-y: auto; height: 30vh;">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    @foreach ($totalPenilaian as $row)
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
                            <!-- /.card-body -->
                            <div class="card-footer text-center">
                                <a href="{{ route('penilaian') }}" class="uppercase">Seluruh Penilaian</a>
                            </div>
                            <!-- /.card-footer -->
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card border border-dark">
                            <div class="card-header">
                                <label class="small m-0">
                                    <i class="fas fa-file-signature"></i> Penilaian Harian Supervisor
                                </label>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow-y: auto; height: 30vh;">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    @foreach ($penilaianList as $row)
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
                                            <a href="#" class="product-title">{{ $row->petugas->nama_pegawai }}
                                                <h4 class="text-dark float-right font-weight-bold text-md">
                                                    {{ $row->temuan->count() }} temuan / <i class="fas fa-star text-warning"></i>  {{ $row->nilai }}
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
                            <!-- /.card-body -->
                            <div class="card-footer text-center">
                                <a href="{{ route('penilaian') }}" class="uppercase">Seluruh Penilaian</a>
                            </div>
                            <!-- /.card-footer -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('stokChart').getContext('2d');

        // Ambil data dari Laravel (pastikan tidak kosong)
        var stokMasuk = Number("{{ $stokMasuk ?? 0 }}");
        var stokKeluar = Number("{{ $stokKeluar ?? 0 }}");

        // Pastikan plugin datalabels terdaftar
        Chart.plugins.register(ChartDataLabels);

        var stokChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Barang Masuk', 'Barang Keluar'],
                datasets: [{
                    data: [stokMasuk, stokKeluar], // Data angka
                    backgroundColor: ['#4CAF50', '#FF5733']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom'
                },
                plugins: {
                    datalabels: {
                        color: '#fff', // Warna teks angka
                        anchor: 'center',
                        align: 'center',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            return value.toLocaleString(); // Format angka dengan pemisah ribuan
                        }
                    }
                }
            }
        });
    });
</script>

<script>
    $(function() {
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()


        $("#table-data").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": true,
            "paging": true,
            "searching": true,
            buttons: [{
                extend: 'pdf',
                text: ' PDF',
                pageSize: 'A4',
                className: 'bg-danger btn-sm',
                title: 'show'
            }, {
                extend: 'excel',
                text: ' Excel',
                className: 'bg-success btn-sm',
                title: 'show'
            }],
            "bDestroy": true
        }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
    })
</script>
@endsection
@endsection
