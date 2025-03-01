@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid col-md-4">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Penilaian</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('pegawai') }}"> Daftar</a></li>
                    <li class="breadcrumb-item active">Penilaian</li>
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
                            Penilaian
                        </label>
                    </div>
                    @if (!$proses == '1-pegawai')
                    <form id="form" action="{{ route('penilaian.create') }}" method="GET">
                        @csrf
                        <input type="hidden" name="proses" value="proses">
                        <div class="card-body">
                            <label for="posisi">Posisi</label>
                            <select name="posisi" id="posisi" class="form-control" required>
                                <option value="">-- Pilih Posisi</option>
                                @foreach($posisi as $row)
                                <option value="{{ $row->id_posisi }}">{{ $row->nama_posisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary btn-sm" onclick="confirmSubmit(event, 'form')">
                                Selanjutnya <i class="fa-solid fa-arrow-circle-right"></i>
                            </button>
                        </div>
                    </form>
                    @endif

                    @if ($proses == 'proses')
                    <form id="form" action="{{ route('penilaian.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="proses" value="selesai">
                        <div class="card-body" style="overflow-y: auto; height: 65vh;">
                            <div class="form-group">
                                <label for="posisi">Posisi</label>
                                <select name="posisi" id="posisi" class="form-control" required>
                                    @foreach($posisi as $row)
                                    <option value="{{ $row->id_posisi }}">
                                        {{ $row->nama_posisi }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pegawai">Pegawai</label>
                                <select name="pegawai" id="pegawai" class="form-control" required>
                                    <option value="">-- Pilih Pegawai</option>
                                    @foreach($pegawai as $row)
                                    <option value="{{ $row->id_pegawai }}">{{ $row->nama_pegawai }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pegawai">Area Kerja</label>
                                <select name="area" id="area" class="form-control w-100" required>
                                    <option value="">-- Pilih Area Kerja</option>
                                    @foreach($area as $row)
                                    <option value="{{ $row->id_area }}">{{ $row->gedung->nama_gedung.' - '.$row->nama_area }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="skala">Skala Penilaian</label>
                                <div class="stars">
                                    <input type="radio" id="star5" name="rating" value="5">
                                    <label for="star5"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1"><i class="fas fa-star"></i></label>
                                </div>

                                <div class="form-group">
                                    <textarea id="keterangan" name="keterangan" class="form-control mt-1" placeholder="Keterangan" rows="4"></textarea>
                                </div>

                                <div id="feedback">
                                    <div class="form-group">
                                        <label for="pegawai">Kriteria Penilaian</label>
                                        @foreach ($kriteria as $row)
                                        <div class="form-group d-flex">
                                            <span class="w-0">
                                                <input id="kriteria-{{ $row->id_kriteria }}" type="checkbox" name="temuan[]" value="{{ $row->id_kriteria }}" style="transform: scale(1.2);">
                                            </span>
                                            <span class="w-100 ml-2">
                                                <label for="kriteria-{{ $row->id_kriteria }}" style="font-weight: 400;">{{ $row->nama_kriteria }}</label>
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="form-group">
                                        <label for="pegawai">Foto Temuan</label> <br>
                                        <input type="file" name="foto[]" class="previewImg" data-preview="multiple-foto" accept="image/*" multiple> <br>
                                        <div id="multiple-foto" class="mt-3 d-flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-left">
                                <a href="{{ url()->previous() }}" type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-arrow-circle-left"></i> Sebelumnya
                                </a>
                            </div>
                            <div class="float-right">
                                <button type="submit" class="btn btn-primary btn-sm" onclick="confirmSubmit(event, 'form')">
                                    Selanjutnya <i class="fa-solid fa-arrow-circle-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@section('js')
<script>
    $('#pegawai').select2()
    $('#area').select2()
</script>
@endsection
@endsection
