<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class League extends Model
{
    protected $primaryKey = 'league_id';
    protected $table = 'leagues';
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->league_id = Str::uuid();
        });
    }

    public function matches()
    {
        return $this->hasMany('App\Match', 'league_id')->orderByDesc('created_at');
    }

    public function scores()
    {
        return $this->hasManyThrough(
            'App\Score',
            'App\Match',
            'league_id',
            'match_id',
            'league_id',
            'match_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'league_users', 'league_id', 'user_id')->withTimestamps();
    }
}
