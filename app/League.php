<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class League extends Model
{
    protected  $primaryKey = 'league_id';

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->league_id = Str::uuid();
        });
    }

    public function matches()
    {
        return $this->hasMany('App\Match', 'league_id');
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
}
