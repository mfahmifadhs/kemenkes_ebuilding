@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid col-md-5">
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
    <div class="container-fluid col-md-5">
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="card card-primary border border-dark">
                    <div class="card-header">
                        <label class="card-title">
                            Penilaian
                        </label>
                    </div>
                    <div class="card-body" style="overflow-y: auto; height: 65vh;">
                        <div class="row">
                            <div class="col-md-4 col-4"><label>Posisi</label></div>
                            <div class="col-md-8 col-8">: {{ $data->posisi->nama_posisi }}</div>

                            <div class="col-md-4 col-4"><label>Pegawai</label></div>
                            <div class="col-md-8 col-8">: {{ $data->petugas->nama_pegawai }}</div>

                            <div class="col-md-4 col-4"><label>Area Kerja</label></div>
                            <div class="col-md-8 col-8">: {{ $data->area->gedung->nama_gedung.' - '.$data->area->nama_area }}</div>

                            <div class="col-md-4 col-4"><label>Kriteria Penilaian : </label></div>
                            <div class="col-md-12 col-12 small">
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

                            <div class="col-md-4"><label>Foto Temuan : </label></div>
                            <div class="col-md-12">
                                <div class="d-flex ml-2 mt-1">
                                    @foreach ($data->foto as $row)
                                    <a href="{{ asset('dist/img/foto_temuan/'. $row->foto_temuan) }}" data-lightbox="zoom" class="w-25 ml-1">
                                        <img src="{{ asset('dist/img/foto_temuan/'. $row->foto_temuan) }}" class="img-fluid rounded border border-dark" alt="Foto Temuan">
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="float-left">
                            <form id="form-false" action="{{ route('penilaian.approval-store', $data->id_penilaian) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="status" value="false">
                                <input type="hidden" name="keterangan_tolak" id="tolak" value="false">
                                <button type="submit" class="btn btn-danger border-dark btn-sm mt-2" onclick="confirmFalse(event)">
                                    <i class="fas fa-times-circle"></i> Tolak
                                </button>
                            </form>
                        </div>
                        <div class="float-right">
                            <form id="form-true" action="{{ route('penilaian.approval-store', $data->id_penilaian) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="status" value="true">
                                <button type="submit" class="btn btn-success border-dark btn-sm mt-2" onclick="confirmTrue(event)">
                                    <i class="fas fa-check-circle"></i> Setuju
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@section('js')
<script>
    $('#area').select2()

    let signaturePadInstance; // Variabel global untuk menyimpan instance SignaturePad

    function confirmTrue(event) {
        event.preventDefault();

        const form = document.getElementById('form-true');

        Swal.fire({
            title: 'Setuju',
            text: 'Tanda tangan persetujuan',
            icon: 'question',
            html: `
            <h6 class="mt-3">Tanda Tangan</h6>
            <div id="signature-pad-container" class="border border-dark" style="width: 100%; height: 200px;"></div>
            <button id="clear-signature" type="button" class="btn btn-danger btn-sm mt-2">Clear</button>
        `,
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
            didOpen: () => {
                const container = Swal.getHtmlContainer().querySelector('#signature-pad-container');
                const canvas = document.createElement('canvas');

                // Set ukuran canvas
                canvas.width = container.offsetWidth;
                canvas.height = container.offsetHeight;

                // Tambahkan canvas ke container
                container.appendChild(canvas);

                // Inisialisasi SignaturePad
                signaturePadInstance = new SignaturePad(canvas);

                // Tombol Clear
                document.getElementById('clear-signature').addEventListener('click', () => {
                    signaturePadInstance.clear();
                });
            },
            preConfirm: () => {
                // Validasi tanda tangan
                if (!signaturePadInstance || signaturePadInstance.isEmpty()) {
                    Swal.showValidationMessage('Silakan isi tanda tangan!');
                    return false;
                }

                // Kembalikan data tanda tangan
                return {
                    signatureData: signaturePadInstance.toDataURL(),
                };
            },
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim tanda tangan ke form
                const signatureData = result.value.signatureData;
                const signatureInput = document.createElement('input');
                signatureInput.type = 'hidden';
                signatureInput.name = 'signature';
                signatureInput.value = signatureData;
                form.appendChild(signatureInput);

                Swal.fire({
                    title: 'Proses...',
                    text: 'Mohon menunggu.',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form
                form.submit();
            }
        });
    }

    // Membuat elemen canvas
    function createCanvas() {
        const canvas = document.createElement('canvas');
        canvas.width = 600; // Anda dapat menyesuaikan ukuran ini
        canvas.height = 200;
        const signaturePad = new SignaturePad(canvas);

        // Tombol Clear
        setTimeout(() => {
            document.getElementById('clear-signature').addEventListener('click', () => {
                signaturePad.clear();
            });
        }, 0);

        return canvas;
    }

    function confirmFalse(event) {
        event.preventDefault();

        const form = document.getElementById('form-false');

        Swal.fire({
            title: 'Konfirmasi Penolakan',
            text: 'Tolak penilaian ini?',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Berikan alasan penolakan di sini...',
            inputAttributes: {
                'aria-label': 'Tulis alasan penolakan di sini'
            },
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan penolakan harus diisi!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const alasanPenolakan = result.value;

                Swal.fire({
                    title: 'Ditolak!',
                    text: 'Usulan telah ditolak dengan alasan: ' + alasanPenolakan,
                    icon: 'success'
                });

                document.getElementById('tolak').value = alasanPenolakan;
                form.submit();
            }
        });
    }
</script>

@endsection
@endsection
