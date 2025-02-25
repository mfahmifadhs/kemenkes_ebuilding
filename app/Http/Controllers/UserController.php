<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penyedia;
use App\Models\Posisi;
use App\Models\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;

class UserController extends Controller
{
    public function show()
    {
        $data     = User::get();
        $role     = Role::get();
        $penyedia = Penyedia::get();
        $posisi   = Posisi::get();
        return view('pages.user.show', compact('data', 'role', 'penyedia', 'posisi'));
    }

    public function store(Request $request)
    {
        $user = User::where('username', $request->username)->orWhere('pegawai_id', $request->pegawai_id)->count();

        if ($user != 0) {
            return back()->with('failed', 'NIP / Username sudah terdaftar');
        }

        $id_user = User::withTrashed()->count() + 1;
        $user = new User();
        $user->id            = $id_user;
        $user->role_id       = $request->role;
        $user->pegawai_id    = $request->pegawai;
        $user->deskripsi     = $request->deskripsi;
        $user->username      = $request->username;
        $user->password      = Hash::make($request->password);
        $user->password_text = $request->password;
        $user->status        = $request->status ?? 'true';
        $user->created_at    = Carbon::now();
        $user->save();

        return redirect()->route('user')->with('success', 'Berhasil Menambahkan');
    }

    public function update(Request $request, $id)
    {
        $cekUser = User::where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->where('username', $request->username)
                    ->orWhere('pegawai_id', $request->pegawai);
            })
            ->exists();

        if ($cekUser) {
            return back()->with('failed', 'Username/Pegawai sudah terdaftar');
        }

        User::where('id', $id)->update([
            'role_id'       => $request->role,
            'pegawai_id'    => $request->pegawai,
            'deskripsi'     => $request->deskripsi,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'password_text' => $request->password,
            'status'        => $request->status ?? 'true'
        ]);

        return redirect()->route('user')->with('success', 'Berhasil Menyimpan Perubahan');
    }
}
