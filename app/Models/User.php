<?php

namespace App\Models;

use App\Models\Credentials\Permissions;
use App\Models\Credentials\Roles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\UserResetPasswordNotification;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Roles::class);
    }


    /**
     * @param Permissions $permission
     * @return bool
     */
    public function hasPermission(Permissions $permission)
    {
        return $this->hasAnyRoles($permission->roles);
    }


    /**
     * @param $roles
     * @return bool
     */
    public function hasAnyRoles($roles)
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param $role
     * @return mixed
     */
    public function hasRole($role)
    {
        return $this->roles->contains('title', $role->title);
    }

    /**
     * @return mixed
     */
    public function isSuperAdmin()
    {
        return $this->roles->contains('title', 'SysAdmin');
    }


    /**
     * @param string $ability
     * @param array $arguments
     * @return bool
     */
    public function cannot($ability, $arguments = [])
    {
        if (!is_array($ability)) {
            return parent::cant($ability, $arguments);
        }

        return !$this->can($ability, $arguments);
    }

    /**
     * @param string $ability
     * @param array $arguments
     * @return bool
     */
    public function can($ability, $arguments = [])
    {
        if (!is_array($ability)) {
            return parent::can($ability, $arguments);
        }

        foreach ($ability as $v) {
            if ($this->can($v)) {
                return true;
            }
        }

        return true;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }
}
