<?php

namespace App\Http\Controllers\Credentials;

use App\Http\Resources\Credentials\RolesResource;
use App\Library\PerfilTreeBuilder;
use App\Library\ProfileTreeBuilder;
use App\Models\Credentials\Roles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Credentials\Profile as ProfileRequest;

class ProfilesController extends Controller
{

    public function index()
    {
        $this->authorize('credentials.profiles.list');

        $roles = Roles::all();

        return view('credentials.profiles.index', compact('roles'));
    }


    public function all()
    {
        return RolesResource::collection(Roles::all());
    }


    public function store(ProfileRequest $request)
    {
        $this->authorize('credentials.profiles.create');

        $role = Roles::create($request->all());

        return $this->edit($role->id);
    }


    public function edit($id)
    {
        $this->authorize('credentials.profiles.edit');

        $role_m = Roles::find($id);

        $treeBuilder = new ProfileTreeBuilder();
        $permissions = $treeBuilder
            ->checkInProfile($role_m)
            ->closeAll()
            ->getAsArray();

        $role = new RolesResource($role_m);

        return response()->json(compact('role', 'permissions'));
    }


    public function update($id, ProfileRequest $request)
    {
        $this->authorize('credentials.profiles.edit');

        $roles = Roles::find($id);
        $roles->fill($request->all());
        $roles->save();

        $roles->permissions()->sync($request->get('permissions', []));

        return response()->json(['message' => 'Perfil foi atualizado com sucesso']);
    }


    public function destroy($id)
    {
        $this->authorize('credentials.profiles.delete');

        Roles::find($id)->delete();

        return response()->json(['success' => 'Perfil foi removido']);
    }


    public function show($id)
    {
        $this->authorize('credentials.profiles.show');

        $role_m = Roles::find($id);

        $treeBuilder = new ProfileTreeBuilder();
        $permissions = $treeBuilder
            ->checkInProfile($role_m)
            ->closeAll()
            ->disableAll()
            ->getAsArray();

        $role = new RolesResource($role_m);

        return response()->json(compact('role', 'permissions'));
    }
}
