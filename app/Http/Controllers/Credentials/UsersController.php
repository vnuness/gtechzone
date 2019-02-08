<?php

namespace App\Http\Controllers\Credentials;

use App\Jobs\Credentials\WelcomeUserMail;
use App\Models\Credentials\Roles;
use App\Http\Controllers\Controller;
use \App\Models\User;
use \App\Http\Resources\Credentials\UserResource;
use \App\Http\Requests\Credentials\User as UserRequest;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('credentials.users.list');

        $roles = Roles::all();

        return view('credentials.users.index', compact('roles'));
    }

    /**
     * listing all
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        try {
            return UserResource::collection(User::all());

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $pass = str_random(8);

        $crypt = bcrypt($pass);

        try {

            DB::beginTransaction();

            $user = User::create($request->all() + ['password' => $crypt]);

            $user->roles()->sync($request->get('roles'));

            if ($request->get('notify', true) == "true") {
                WelcomeUserMail::dispatch($user, $pass, auth()->user());
            }

            DB::commit();

        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'Falha ao cadastrar o usuário'], 500);
        }

        return response()->json(['message' => 'Usuário cadastrado.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('credentials.users.edit');

        return new UserResource(User::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $user = User::find($id);
            $user->fill($request->all());
            $user->save();

            $user->roles()->sync($request->get('roles'));

            DB::commit();

        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'Falha ao atualizar o usuário'], 500);
        }

        return response()->json(['message' => 'Usuário atualizado.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('credentials.users.delete');

        User::find($id)->delete();

        return response()->json(['success' => 'Usuário foi removido']);
    }

}
