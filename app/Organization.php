<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $primaryKey = 'organization_id';
    protected $table = 'organizations';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slack_team_id', 'slack_token'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'league_users', 'organization_id', 'user_id')->withTimestamps();
    }
}
