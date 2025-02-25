@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid col-md-4">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Edit Penilaian</h4>
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
    <div class="container-fluid col-md-4">
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="card card-primary border border-dark">
                    <div class="card-header">
                        <label class="card-title">
                            Edit Penilaian
                        </label>
                    </div>

                    <form id="form" action="{{ route('penilaian.update', $data->id_penilaian) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body" style="overflow-y: auto; height: 64vh;">
                            <div class="form-group">
                                <label for="posisi">Posisi</label>
                                <select name="posisi" id="posisi" class="form-control">
                                    <option value="{{ $data->posisi_id }}">
                                        {{ $data->posisi->nama_posisi }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pegawai">Pegawai</label>
                                <select name="pegawai" id="pegawai" class="form-control">
                                    <option value="{{ $data->petugas_id }}">
                                        {{ $data->petugas->nama_pegawai }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pegawai">Area Kerja</label>
                                <select name="area" id="area" class="form-control w-100">
                                    @foreach($area as $row)
                                    <option value="{{ $row->id_area }}" <?php echo $row->id_area == $data->area_id ? 'selected' : ''; ?>>
                                        {{ $row->gedung->nama_gedung.' - '.$row->nama_area }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pegawai">Kriteria Penilaian</label>
                                @foreach ($kriteria as $row)
                                @php
                                $isChecked = $data->temuan->firstWhere('kriteria_id', $row->id_kriteria)?->status === 'true';
                                @endphp
                                <div class="form-group d-flex">
                                    <span class="w-0">
                                        @if ($isChecked)
                                        <input type="hidden" name="id_detail[{{ $row->id_kriteria }}]" value="{{ optional($data->temuan->firstWhere('kriteria_id', $row->id_kriteria))->id_detail }}">
                                        @else
                                        <input type="hidden" name="id_detail[{{ $row->id_kriteria }}]" value="">
                                        @endif
                                        <input type="hidden" name="kriteria[]" value="{{ $row->id_kriteria }}">
                                        <input type="hidden" name="temuan[{{ $row->id_kriteria }}]" value="false">
                                        <input
                                            id="kriteria-{{ $row->id_kriteria }}"
                                            type="checkbox"
                                            name="temuan[{{ $row->id_kriteria }}]"
                                            value="true"
                                            {{ $isChecked ? 'checked' : '' }}
                                            style="transform: scale(1.2);">
                                    </span>
                                    <span class="w-100 ml-2">
                                        <label for="kriteria-{{ $row->id_kriteria }}" style="font-weight: 400;">{{ $row->nama_kriteria }}</label>
                                    </span>
                                </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label for="keterangan">keterangan</label>
                                <textarea name="keterangan" class="form-control" id="keterangan">{{ $data->keterangan }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="pegawai">Foto Temuan</label> <br>
                                <input type="file" name="foto[]" class="previewImg" data-preview="multiple-foto" accept="image/*" multiple> <br>
                                <div id="multiple-foto" class="mt-3 d-flex flex-wrap gap-2"></div>

                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    @foreach ($data->foto as $row)
                                    <div class="w-25 mx-1">
                                        <a href="{{ asset('dist/img/foto_temuan/'. $row->foto_temuan) }}" data-lightbox="zoom">
                                            <img src="{{ asset('dist/img/foto_temuan/'. $row->foto_temuan) }}" class="img-fluid rounded border border-dark" alt="Foto Temuan">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary border-dark mt-2" onclick="confirmSubmit(event, 'form')">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@section('js')
<script>
    $('#area').select2()
</script>

@endsection
@endsection
