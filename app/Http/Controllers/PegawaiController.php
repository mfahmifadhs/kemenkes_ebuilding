<?php

namespace App\Http\Controllers;

use App\Models\GedungArea;
use Intervention\Image\ImageManagerStatic as Image;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

use App\Models\Pegawai;
use App\Models\Penempatan;
use App\Models\Penyedia;
use App\Models\Posisi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Str;
use ZipArchive;

class PegawaiController extends Controller
{
    public function show(Request $request)
    {
        $role        = Auth::user()->role_id;
        $user        = Auth::user()->pegawai;
        $data        = Pegawai::count();
        $penyediaArr = Penyedia::orderBy('nama_penyedia', 'asc');
        $penempatan  = Penempatan::get();
        $posisiArr   = Posisi::orderBy('nama_posisi', 'asc');

        if ($role == 4) {
            if ($user->penyedia) {
                if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                    $posisi = $posisiArr->whereIn('id_posisi', [3, 6])->get();
                }

                if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                    $posisiArr = $posisiArr->where('id_posisi', 5);
                }

                if ($user->penyedia_id == 2) {
                    $posisiArr = $posisiArr->whereNotIn('id_posisi', [3, 5, 10, 11]);
                }

                $penyediaArr = $penyediaArr->where('id_penyedia', $user->penyedia_id);
            } else {
                $posisiArr = $posisiArr->where('penyedia_id', $user->penyedia_id);
            }
        } else {
            $posisiArr   = $posisiArr;
            $penyediaArr = $penyediaArr;
        }

        $posisi   = $posisiArr->get();
        $penyedia = $penyediaArr->get();

        $penyediaSelected   = $request->penyedia;
        $penempatanSelected = $request->penempatan;
        $posisiSelected     = $request->posisi;
        $statusSelected     = $request->status;

        return view('pages.pegawai.show', compact('data', 'penyedia', 'penempatan', 'posisi', 'penyediaSelected', 'penempatanSelected', 'posisiSelected', 'statusSelected'));
    }

    public function detail($id)
    {
        $role = Auth::user()->role_id;
        $user = Auth::user()->pegawai;
        $data = Pegawai::where('id_pegawai', $id)->first();

        if ($role == 4) {
            if ($data->penyedia_id == $user->penyedia_id) {
                $data = $data;
            } else {
                return redirect()->route('pegawai');
            }
        }

        if (!$data) {
            return back()->with('failed', 'Data Tidak Ditemukan');
        }

        if ($user->penyedia) {
            if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                if ($data->posisi_id == 3 && $user->posisi_id != 10) {
                    return back();
                }
            }

            if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                if ($data->posisi_id == 5 && $user->posisi_id != 11) {
                    return back();
                }
            }
        }

        return view('pages.pegawai.detail', compact('data'));
    }

    public function select(Request $request)
    {
        $role       = Auth::user()->role_id;
        $user       = Auth::user()->pegawai;
        $penyedia   = $request->penyedia;
        $penempatan = $request->penempatan;
        $posisi     = $request->posisi;
        $status     = $request->status;
        $search     = $request->search;

        $dataArr  = Pegawai::orderBy('posisi_id', 'asc')->orderBy('status', 'desc');
        $no       = 1;
        $response = [];

        if ($role == 4) {
            if ($user->penyedia) {
                if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                    $data = $dataArr->whereIn('posisi_id', [3, 6]);
                }

                if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                    $data = $dataArr->where('posisi_id', 5);
                }

                if ($user->penyedia_id == 2) {
                    $data = $dataArr->whereNotIn('posisi_id', [3, 5]);
                }
            } else {
                $data = $dataArr->where('penyedia_id', $user->penyedia_id);
            }
        } else {
            $data = $dataArr;
        }

        if ($penempatan || $penyedia || $posisi || $status || $search) {

            if ($penyedia) {
                $res = $data->where('penyedia_id', $penyedia);
            }

            if ($posisi) {
                $res = $data->where('posisi_id', $posisi);
            }
            if ($penempatan) {
                $res = $data->where('penempatan_id', $penempatan);
            }

            if ($status) {
                $res = $data->where('status', $status);
            }

            if ($search) {
                $res = $data->with('posisi')->where('nama_pegawai', 'like', '%' . $search . '%')
                    ->orWhereHas('posisi', function ($q) use ($search) {
                        $q->where('nama_posisi', 'like', '%' . $search . '%');
                    });
            }

            $result = $res->get();
        } else {
            $result = $data->get();
        }

        foreach ($result as $row) {
            $aksi   = '';
            $status = '';

            if ($row->foto_pegawai) {
                $foto = '<img src="' . asset('dist/img/foto_pegawai/' . $row->foto_pegawai) . '" class="img-fluid" alt="">';
            } else {
                $foto = '<img src="https://cdn-icons-png.flaticon.com/128/149/149071.png" class="img-fluid" alt="">';
            }

            $aksi .= '
                <a href="' . route('pegawai.detail', $row->id_pegawai) . '" class="btn btn-default btn-xs bg-primary rounded border-dark">
                    <i class="fas fa-info-circle p-1" style="font-size: 12px;"></i>
                </a>
            ';

            if ($role != 3) {
                $aksi .= '
                    <a href="' . route('pegawai.edit', $row->id_pegawai) . '" class="btn btn-default btn-xs bg-warning rounded border-dark">
                        <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                    </a>
                ';
            }

            $response[] = [
                'no'            => $no,
                'id'            => $row->id_pegawai,
                'kode'          => $row->kode_pegawai,
                'aksi'          => $aksi,
                'foto'          => $foto,
                'fileFoto'      => $row->foto_pegawai,
                'penyedia'      => $row->penyedia?->nama_penyedia,
                'posisi'        => $row->posisi?->nama_posisi,
                'penempatan'    => $row->area ? $row->area?->gedung->nama_gedung.' - '.$row->area?->nama_area.', '.$row->penempatan?->nama_penempatan : $row->penempatan?->nama_penempatan,
                'pegawai'       => $row->nama_pegawai,
                'nik'           => $row->nik,
                'nip'           => $row->nip,
                'jenisKelamin'  => $row->jenis_kelamin,
                'email'         => $row->email,
                'noTelp'        => $row->no_telepon,
                'tglMasuk'      => $row->tanggal_masuk,
                'tglKeluar'     => $row->tanggal_keluar,
                'status'        => $row->status,
                'review'        => $row->totalReview->sum('nilai'),
                'nilai'         => $row->totalNilai->sum('nilai')
            ];

            $no++;
        }

        // $response[] = array(
        //     "id"    => "",
        //     "text"  => "-- Pilih Pegawai --"
        // );

        // foreach ($result as $row) {
        //     $response[] = array(
        //         "id"    =>  $row->id_pegawai,
        //         "text"  =>  $row->nama_pegawai
        //     );
        // }

        return response()->json($response);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = $file->getClientOriginalName();
            $request->foto->move(public_path('dist/img/foto_pegawai'), $fileName);
        }

        $id = Pegawai::withTrashed()->count() + 1;
        $tambah = new Pegawai();
        $tambah->id_pegawai     = $id;
        $tambah->uker_id        = $request->uker;
        $tambah->penyedia_id    = $request->penyedia;
        $tambah->penempatan_id  = $request->penempatan;
        $tambah->posisi_id      = $request->posisi;
        $tambah->kode_pegawai   = mt_rand(111111, 999999);
        $tambah->nama_pegawai   = $request->pegawai;
        $tambah->nik            = $request->nik;
        $tambah->nip            = $request->nip;
        $tambah->jenis_kelamin  = $request->jenis_kelamin;
        $tambah->email          = $request->email;
        $tambah->no_telepon     = $request->no_telepon;
        $tambah->tanggal_masuk  = $request->tanggal_masuk;
        $tambah->tanggal_keluar = $request->tanggal_keluar;
        $tambah->foto_pegawai   = $fileName;
        $tambah->status         = 'true';
        $tambah->created_at     = Carbon::now();
        $tambah->save();

        return redirect()->route('pegawai')->with('success', 'Berhasil Menambahkan');
    }

    public function edit($id)
    {
        $role       = Auth::user()->role_id;
        $user       = Auth::user()->pegawai;
        $data       = Pegawai::where('id_pegawai', $id)->first();
        $penyedia   = Penyedia::get();
        $penempatan = Penempatan::get();
        $posisi     = Posisi::get();
        $area       = GedungArea::where('posisi_id', $data->posisi_id)->orderBy('id_area', 'asc')->get();

        if ($role == 4) {
            if ($data->penyedia_id == $user->penyedia_id) {
                $data = $data;
            } else {
                return redirect()->route('pegawai');
            }
        }

        if (!$data) {
            return back()->with('failed', 'Data Tidak Ditemukan');
        }

        if ($user->penyedia) {
            if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                if ($data->posisi_id == 3 && $user->posisi_id != 10) {
                    return back();
                }
            }

            if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                if ($data->posisi_id == 5 && $user->posisi_id != 11) {
                    return back();
                }
            }
        }


        return view('pages.pegawai.edit', compact('data', 'penyedia', 'penempatan', 'posisi', 'area'));
    }

    public function update(Request $request, $id)
    {
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $request->foto->move(public_path('dist/img/foto_pegawai'), $fileName);
        }

        Pegawai::where('id_pegawai', $id)->update([
            'uker_id'        => $request->uker,
            'penempatan_id'  => $request->penempatan,
            'area_id'        => $request->area,
            'penyedia_id'    => $request->penyedia,
            'posisi_id'      => $request->posisi,
            'nama_pegawai'   => $request->pegawai,
            'nik'            => $request->nik,
            'nip'            => $request->nip,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'email'          => $request->email,
            'no_telepon'     => $request->no_telepon,
            'tanggal_masuk'  => $request->tanggal_masuk,
            'tanggal_keluar' => $request->tanggal_keluar,
            'foto_pegawai'   => $fileName ?? $request->foto_pegawai,
            'status'         => $request->status,
            'created_at'     => Carbon::now()
        ]);

        return redirect()->route('pegawai.detail', $id)->with('success', 'Berhasil Memperbaharui');
    }

    public function qrcode($id)
    {
        $user = Auth::user()->pegawai;

        if ($user->penyedia_id) {
            $dataPetugas = Pegawai::where('penyedia_id', $user->penyedia_id);

            if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                $petugas = $dataPetugas->whereIn('posisi_id', [3, 6]);
            } else if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                $petugas = $dataPetugas->where('posisi_id', 5);
            } else if ($user->penyedia_id == 2) {
                $petugas = $dataPetugas->whereNotIn('posisi_id', [3, 5]);
            }

            if ($id == '*') {
                $petugas = $petugas->get();
            } else {
                $petugas = $petugas->where('id_pegawai', $id)->get();
            }

            $tempDirectory = storage_path('app/temp');
            if (!file_exists($tempDirectory)) {
                mkdir($tempDirectory, 0755, true);
            }

            // Buat file ZIP
            $zipFileName = "qrcode_pegawai.zip";
            $zipFilePath = "{$tempDirectory}/{$zipFileName}";


            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                foreach ($petugas as $row) {
                // Generate QR Code
                $token = Str::random(16);
                $link = 'https://e-building.site/pegawai/review/' . $row->id_pegawai . '/' . $token;
                $renderer = new ImageRenderer(new RendererStyle(450), new ImagickImageBackEnd());
                $writer = new Writer($renderer);
                $qrImage = imagecreatefromstring($writer->writeString($link));

                // Load Foto Pegawai (Pastikan File Ada)
                $tempUser = public_path('dist/img/foto_pegawai/' . $row->foto_pegawai);
                if (!file_exists($tempUser)) continue;

                // Tentukan format gambar pegawai
                $extension = pathinfo($tempUser, PATHINFO_EXTENSION);
                $originalImage = ($extension === 'png') ? imagecreatefrompng($tempUser) : imagecreatefromjpeg($tempUser);

                // Resize Foto Pegawai ke 500x500 (Menyesuaikan proporsi)
                $targetSize = 320;
                $srcWidth = imagesx($originalImage);
                $srcHeight = imagesy($originalImage);
                $scale = min($targetSize / $srcWidth, $targetSize / $srcHeight);
                $newWidth = (int)($srcWidth * $scale);
                $newHeight = (int)($srcHeight * $scale);

                // Buat Canvas Transparan 500x500
                $resizedImage = imagecreatetruecolor($targetSize, $targetSize);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
                imagefill($resizedImage, 0, 0, $transparent);

                // Tempelkan gambar yang telah di-resize ke tengah canvas
                $dstX = ($targetSize - $newWidth) / 2;
                $dstY = ($targetSize - $newHeight) / 2;
                imagecopyresampled($resizedImage, $originalImage, $dstX, $dstY, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

                // Hitung ukuran QR Code
                $qrWidth = imagesx($qrImage);
                $qrHeight = imagesy($qrImage);

                // Load Template Review
                $templatePath = public_path('dist/img/format-review-2.jpeg');
                $templateImage = imagecreatefromjpeg($templatePath);
                $templateWidth = imagesx($templateImage);
                $templateHeight = imagesy($templateImage);

                // Tempelkan QR Code di Tengah Atas
                $qrX = ($templateWidth - $qrWidth) / 2;
                $qrY = 50;
                imagecopy($templateImage, $qrImage, $qrX, $qrY + 295, 0, 0, $qrWidth, $qrHeight);

                // Tempelkan Foto Pegawai di Pojok Kiri Bawah
                $photoX = 50;
                $photoY = $templateHeight - $targetSize - 135;
                imagecopy($templateImage, $resizedImage, $photoX, $photoY, 0, 0, $targetSize, $targetSize);

                // Tambahkan Nama & Jabatan Pegawai
                $textColor = imagecolorallocate($templateImage, 255, 255, 255);
                $fontPath = public_path('dist/fonts/GOTHICB0.TTF');
                $fontSize = 32;
                $name = strtoupper($row->nama_pegawai);
                $jobTitle = strtoupper($row->posisi->nama_posisi);

                // Posisi teks di bawah QR Code
                $nameY = $qrY + $qrHeight + 465;
                $jobTitleY = $nameY + 115;

                // Pusatkan teks Nama
                $nameBox = imagettfbbox($fontSize, 0, $fontPath, $name);
                $nameX = ($templateWidth - ($nameBox[2] - $nameBox[0])) / 2 + 200;
                imagettftext($templateImage, $fontSize, 0, $nameX, $nameY, $textColor, $fontPath, $name);

                // Pusatkan teks Jabatan
                $jobColor = imagecolorallocate($templateImage, 11, 130, 110);
                $jobTitleBox = imagettfbbox($fontSize, 0, $fontPath, $jobTitle);
                $jobTitleX = ($templateWidth - ($jobTitleBox[2] - $jobTitleBox[0])) / 2 + 100;
                imagettftext($templateImage, $fontSize, 0, $jobTitleX + 100, $jobTitleY, $jobColor, $fontPath, $jobTitle);

                // Simpan Hasil Gambar
                $fileName = "qr_pegawai_{$row->id_pegawai}_{$row->nama_pegawai}.png";
                $filePath = "{$tempDirectory}/{$fileName}";

                // Simpan gambar ke file sementara
                imagepng($templateImage, $filePath);

                // Tambahkan file ke ZIP
                $zip->addFile($filePath, $fileName);

                // Hapus resource gambar
                imagedestroy($templateImage);
            }

                $zip->close();
            }
            return response()->download($zipFilePath)->deleteFileAfterSend(true);


            return back()->with('success', 'Berhasil Download');
        }
            return back()->with('success', 'Gagal');
    }
}
