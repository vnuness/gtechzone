<?php

namespace App\Models\Credentials;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $fillable = ['slug', 'description'];

    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Roles::class);
    }
}
