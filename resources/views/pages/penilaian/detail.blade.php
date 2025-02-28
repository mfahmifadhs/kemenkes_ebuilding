@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid col-md-6">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Penilaian</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('penilaian') }}"> Daftar</a></li>
                    <li class="breadcrumb-item active">Penilaian</li>
                </ol>

            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container-fluid col-md-6">
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="card card-primary border border-dark">
                    <div class="card-header">
                        <label class="card-title">
                            Penilaian
                        </label>
                        <div class="card-tools">
                            <a href="{{ route('penilaian.edit', $data->id_penilaian) }}" class="btn btn-warning btn-xs text-dark">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body" style="overflow-y: auto; height: 65vh;">
                        <div class="d-flex flex-wrap gap-2">
                            <label class="w-50 text-secondary small">
                                #{{ $data->kode_penilaian }}-{{ $data->id_penilaian }}
                            </label>
                            <label class="w-50 text-right small">
                                @if (!$data->status) <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span> @endif
                                @if ($data->status == 'true')
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Setuju</span>
                                @else
                                <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tolak</span>
                                @endif
                            </label>

                            <label class="w-25 col-4">Posisi</label>
                            <label class="w-75 col-8" style="font-weight: 400;">: {{ $data->posisi->nama_posisi }}</label>

                            <label class="w-25 col-4">Pengawas</label>
                            <label class="w-75 col-8" style="font-weight: 400;">: {{ $data->pengawas->nama_pegawai }}</label>

                            <label class="w-25 col-4">Petugas</label>
                            <label class="w-75 col-8" style="font-weight: 400;">: {{ $data->petugas->nama_pegawai }}</label>

                            <label class="w-25 col-4">Area Kerja</label>
                            <label class="w-75 col-8" style="font-weight: 400;">: {{ $data->area->nama_area }}</label>

                            <label class="w-25 col-4">keterangan</label>
                            <label class="w-75 col-8" style="font-weight: 400;">: {{ $data->keterangan }}</label>

                            <label class="w-25 mt-3 col-12">Kriteria Penilaian</label>
                            <div class="w-75 col-12">
                                @foreach ($data->temuan as $row)
                                <div class="d-flex ml-2 mt-1">
                                    <span class="w-0">
                                        <i class="fa-solid fa-check-square text-success"></i>
                                    </span>
                                    <span class="w-100 ml-1">
                                        <label for="kriteria-{{ $row->kriteria_id }}" style="font-weight: 400;">{{ $row->kriteria->nama_kriteria }}</label>
                                    </span>
                                </div>
                                @endforeach
                            </div>

                            <label class="w-25 col-form-label col-12">Foto Temuan</label>
                            <div class="w-75">
                                <div class="mt-2 d-flex flex-wrap gap-2 col-12">
                                    @foreach ($data->foto as $row)
                                    <div class="w-25 mx-1">
                                        <a href="{{ asset('dist/img/foto_temuan/'. $row->foto_temuan) }}" data-lightbox="zoom">
                                            <img src="{{ asset('dist/img/foto_temuan/'. $row->foto_temuan) }}" class="img-fluid rounded border border-dark" alt="Foto Temuan">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($data->foto_ttd)
                            <label class="w-25 mt-3 col-form-label col-12">Foto Tandatangan</label>
                            <div class="w-75">
                                <div class="mt-2 d-flex flex-wrap gap-2 col-12">
                                    <div class="w-25 mx-1">
                                        <a href="{{ asset('dist/img/foto_ttd/'. $data->foto_ttd) }}" data-lightbox="zoom">
                                            <img src="{{ asset('dist/img/foto_ttd/'. $data->foto_ttd) }}" class="img-fluid rounded border border-dark" alt="Foto Tandatangan">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if ($data->status == 'false')
                            <label class="w-25 mt-3 col-12">Alasan Penolakan :</label>
                            <div class="w-75 col-12">
                                {{ $data->keterangan_tolak }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
