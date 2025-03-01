@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid col-md-6">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Detail Pegawai</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('pegawai') }}"> Daftar</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>

            </div>
        </div>
    </div>
</div>


<div class="content">
    <div class="container-fluid col-md-6">
        <div class="card border border-dark">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        @if ($data->foto_pegawai)
                        <img src="{{ asset('dist/img/foto_pegawai/'. $data->foto_pegawai) }}" class="img-fluid rounded-circle border border-dark my-auto">
                        @else
                        <i class="fas fa-circle-user fa-4x"></i>
                        @endif
                        <div class="mt-2">
                            <a href="{{ route('pegawai.edit', $data->id_pegawai) }}" class="btn btn-warning btn-block btn-xs border-dark p-0 font-weight-bold">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="#" class="btn btn-primary btn-block btn-xs border-dark p-0 font-weight-bold" onclick="confirmLink(event, `{{ route('pegawai.qrcode', $data->id_pegawai) }}`)">
                                <i class="fas fa-qrcode"></i> QR Code
                            </a>
                        </div>
                        <div class="mt-2">
                            <hr>
                            <label class="text-secondary text-xs">Kinerja</label>
                        </div>
                        <div class="mt-2">
                            <hr>
                            <label class="text-secondary text-xs">Kartu Kuning</label>
                        </div>
                    </div>
                    <div class="col-md-9 border-left border-dark">
                        <div class="row text-sm">
                            <div class="col-md-12 col-12">
                                <label class="text-secondary">Informasi Pegawai</label>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="mb-0">Posisi</label>
                                <p>{{ $data->posisi->nama_posisi }}</p>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="mb-0">Penyedia</label>
                                <p>{{ $data->penyedia->nama_penyedia }}</p>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="mb-0">Nama</label>
                                <p>{{ $data->nama_pegawai }}</p>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="mb-0">NIP</label>
                                <p>{{ $data->nip }}</p>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="mb-0">Jenis Kelamin</label>
                                <p>{{ ucwords($data->jenis_kelamin) }}</p>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="mb-0">No. Telepon</label>
                                <p>{{ $data->no_telepon }}</p>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="mb-0">Email</label>
                                <p>{{ $data->no_telepon }}</p>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="mb-0">Status</label>
                                <p>{{ $data->status }}</p>
                            </div>
                            <div class="col-md-12 col-12">
                                <label class="mb-0">Area Kerja</label>
                                <p>{{ $data->area?->gedung->nama_gedung }} - {{ $data->area?->nama_area }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="text-center p-2">
                </h3>
            </div>
        </div>
    </div>
</div>
@endsection
