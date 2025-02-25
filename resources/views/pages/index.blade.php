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

            <div class="col-md-3">
                <a href="{{ route('penilaian.create') }}" class="btn btn-primary btn-block border-dark p-3 form-group">
                    <span class="font-weight-bold">
                        <i class="fas fa-plus-circle"></i> Mulai Penilaian
                    </span>
                </a>

                <div class="info-box mb-3 bg-warning border border-dark">
                    <span class="info-box-icon"><i class="fas fa-file"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Kartu Kuning</span>
                        <span class="info-box-number">{{ $totalTemuan->count() }}</span>
                    </div>
                </div>

                <div class="info-box mb-3 bg-warning border border-dark">
                    <span class="info-box-icon"><i class="fas fa-file text-success"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Kartu Kuning Diterima</span>
                        <span class="info-box-number">{{ $totalTemuan->where('status', 'true')->count() }}</span>
                    </div>
                </div>

                <div class="info-box mb-3 bg-warning border border-dark">
                    <span class="info-box-icon"><i class="fas fa-file text-danger"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Kartu Kuning Ditolak</span>
                        <span class="info-box-number">{{ $totalTemuan->where('status', 'false')->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
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
                    @endif
                    <div class="col-md-4">
                        <!-- Lainnya -->
                    </div>

                    <div class="col-md-12">
                        <div class="card border border-dark table-responsive">
                            <div class="card-body">
                                <label class="text-sm">Review Petugas</label>
                                <table id="table-data" class="table table-striped text-xs text-center">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Petugas</th>
                                            <th>Posisi</th>
                                            <th>Area Kerja</th>
                                            <th>Nilai</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($review as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $row->created_at }}</td>
                                            <td class="text-left">{{ $row->petugas->nama_pegawai }}</td>
                                            <td class="text-left">{{ $row->petugas->posisi->nama_posisi }}</td>
                                            <td class="text-left">
                                                {{ $row->area?->gedung->nama_gedung }} {{ $row->area?->nama_area }}
                                            </td>
                                            <td>{{ $row->nilai }}</td>
                                            <td>{{ $row->keterangan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3"></div>
            <div class="col-md-9">
                <!-- Lainnya -->
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
