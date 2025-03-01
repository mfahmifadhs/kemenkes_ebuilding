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
                                <label for="skala">Skala Penilaian</label>
                                <div class="stars">
                                    <input type="radio" id="star5" name="rating" value="5" <?= $data->nilai == 5 ? 'checked' : '' ?>>
                                    <label for="star5"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4" <?= $data->nilai == 4 ? 'checked' : '' ?>>
                                    <label for="star4"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3" <?= $data->nilai == 3 ? 'checked' : '' ?>>
                                    <label for="star3"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2" <?= $data->nilai == 2 ? 'checked' : '' ?>>
                                    <label for="star2"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1" <?= $data->nilai == 1 ? 'checked' : '' ?>>
                                    <label for="star1"><i class="fas fa-star"></i></label>
                                </div>

                                <div class="form-group">
                                    <textarea name="keterangan" class="form-control mt-1" id="keterangan">{{ $data->keterangan }}</textarea>
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

<script>
    const stars = document.querySelectorAll('.stars input');
    const feedback = document.getElementById('feedback');
    const saveButton = document.getElementById('save-button');

    // Fungsi untuk menampilkan feedback jika rating <= 3
    stars.forEach(star => {
        star.addEventListener('change', function() {
            if (this.value <= 3) {
                feedback.classList.add('show');
            } else {
                feedback.classList.remove('show');
            }
        });
    });

    // Fungsi untuk menyimpan perubahan
    saveButton.addEventListener('click', function() {
        const selectedRating = document.querySelector('input[name="rating"]:checked').value;
        const feedbackText = document.getElementById('feedback-text').value;

        // Kirim data ke server (contoh menggunakan Fetch API)
        fetch('/save-rating', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    rating: selectedRating,
                    feedback: feedbackText
                }),
            })
            .then(response => response.json())
            .then(data => {
                alert('Perubahan berhasil disimpan!');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>

@endsection
@endsection
