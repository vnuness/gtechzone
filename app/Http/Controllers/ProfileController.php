<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfile as UserProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserProfileRequest $request)
    {

        auth()->user()->name = $request->name;

        if ($request->filled('old-password')) {

            if (!Hash::check($request->get('old-password'), auth()->user()->getAuthPassword())) {
                return response()->json(['errors' => ['A senha atual informada é inválida']], 400);
            }

            auth()->user()->password = bcrypt($request->get('new-password'));
            auth()->user()->save();
        }

        if ($request->has('avatar')) {
            $this->saveAvatar($request);
        }


        return response()->json(['message' => 'Perfil atualizado']);
    }

    private function saveAvatar(UserProfileRequest $request)
    {
        $this->validate($request, ['avatar' => 'required']);

        $file_data = $request->input('avatar');

        $storage = public_path();

        list($type, $file_data) = explode(';', $file_data);

        $file_data = explode(',', $file_data)[1];

        $ext = explode('/', $type)[1];

        $file_name = md5(auth()->user()->id) . '.' . $ext;

        Storage::disk('public')->put('images/users/' . $file_name, base64_decode($file_data));

        auth()->user()->avatar = $file_name;
        auth()->user()->save();
    }

}
