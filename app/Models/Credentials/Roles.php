<?php

namespace App\Models\Credentials;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $fillable = ['title'];


    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permissions::class);
    }

    public function hasPermissionId($permission_id)
    {
        $key = $this->permissions->search(function ($item, $key) use ($permission_id) {
            return ($item->id == $permission_id);
        });

        return $key;
    }

    public function delete()
    {
        if ($this->title == 'SysAdmin') {
            throw new \Exception('Não autorizado');
        }

        parent::delete();
    }
}
