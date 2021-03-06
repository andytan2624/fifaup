<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';
    protected $table = 'users';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'slack_user_id', 'avatar_url',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->user_id = Str::uuid();
        });
    }

    public function __toString()
    {
        return $this->name;
    }

    public function leagues()
    {
        return $this->belongsToMany(
            'App\League',
            'league_users',
            'user_id',
            'league_id'
        )->withTimestamps();
    }

    public function organizations()
    {
        return $this->belongsToMany(
            'App\Organization',
            'organization_users',
            'user_id',
            'organization_id'
        )->withTimestamps();
    }
}
