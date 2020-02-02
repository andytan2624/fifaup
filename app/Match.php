<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Match extends Model
{
    protected $primaryKey = 'match_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'league_id', 'team_1_score', 'team_2_score', 'team_1_penalties', 'team_2_penalties', 'name'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->match_id = Str::uuid();
        });
    }

    public function league()
    {
        return $this->hasOne('App\League');
    }

    public function scores()
    {
        return $this->hasMany('App\Score', 'match_id');
    }

    public function team1Scores()
    {
        return $this->scores()->where('team', '=', 1);
    }

    public function team2Scores()
    {
        return $this->scores()->where('team', '=', 2);
    }
}
