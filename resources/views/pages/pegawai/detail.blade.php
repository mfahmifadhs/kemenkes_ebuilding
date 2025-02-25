@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid col-md-8">
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


<section class="content">
    <div class="container-fluid col-md-8">
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header border border-dark">
                        <label class="card-title">
                            Detail Pegawai
                        </label>
                    </div>
                    <div class="card-body border border-dark">
                        <div class="row">
                            <div class="col-md-2">
                                @if ($data->foto_pegawai)
                                    <img src="' . asset('dist/img/foto_pegawai/' . $data->foto_pegawai) . '" class="img-fluid" alt="">'
                                @else
                                    <img src="https://cdn-icons-png.flaticon.com/128/149/149071.png" class="img-fluid" alt="">'
                                @endif
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Nama Pegawai</label>
                                        <h6>{{ $data->nama_pegawai }}</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Posisi</label>
                                        <h6>{{ $data->posisi->nama_posisi }}</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Penempatan</label>
                                        <h6>{{ $data->penempatan->nama_penempatan }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
