<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
            return view('master.user.index', [
                'title' => 'User',
                'section' => 'Master',
                'active' => 'user',
                'users' => $users,
            ]);
    }

    public function store(Request $request)
    {
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'nama' => 'required|string|max:255'
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        // simpan data ke database
        try {
            DB::beginTransaction();

             // Hash password sebelum menyimpannya ke database
            $hashedPassword = Hash::make($request->password);

            // insert ke tabel positions
            User::create([
                'username' => $request->username,
                'password' => $hashedPassword,
                'nama' => $request->nama
            ]);

            DB::commit();

            return redirect()->back()->with('insertSuccess', 'Data berhasil di Inputkan');

        } catch(Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('insertFail', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        return view('master.user.edit', [
            'title' => 'User',
            'secction' => 'Master',
            'active' => 'user',
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'is_aktif' => 'required|integer|between:0,1'
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        try{
            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->is_aktif = $request->is_aktif;

            $user->save();

            return redirect('/user')->with('updateSuccess', 'Data berhasil di Update');
        } catch(Exception $e) {
            dd($e);
            return redirect()->back()->with('updateFail', 'Data gagal di Update');
        }
    }

    public function destroy($id)
    {
        // Cari data pengguna berdasarkan ID
        $position = User::find($id);

        try {
            // Hapus data pengguna
            $position->delete();
            return redirect()->back()->with('deleteSuccess', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('deleteFail', $e->getMessage());
        }
    }

    public function reset($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        return view('master.user.reset', [
            'title' => 'User',
            'secction' => 'Master',
            'active' => 'user',
            'user' => $user,
        ]);
    }

    public function resetupdate(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        try{
            $user->username = $request->username;
            // Hash password sebelum menyimpannya ke database
            $user->password = Hash::make($request->password);

            $user->save();

            return redirect('/user')->with('updateSuccess', 'Data berhasil di Reset');
        } catch(Exception $e) {
            dd($e);
            return redirect()->back()->with('updateFail', 'Data gagal di Reset');
        }
    }

}
