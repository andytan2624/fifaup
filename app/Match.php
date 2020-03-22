<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Match extends Model
{
    protected $primaryKey = 'match_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'league_id', 'higher_score', 'lower_score', 'name'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->match_id = Str::uuid();
        });
    }

    public function league()
    {
        return $this->hasOne('App\League');
    }

    public function scores()
    {
        return $this->hasMany('App\Score', 'match_id')->orderByDesc('points');
    }

    public function team1Scores()
    {
        return $this->scores()->where('team', '=', 1);
    }

    public function team2Scores()
    {
        return $this->scores()->where('team', '=', 2);
    }

    public function winningTeam()
    {
        return $this->scores()->where('status', '=', Score::STATUS_WIN);
    }

    public function losingTeam()
    {
        return $this->scores()->where('status', '=', Score::STATUS_LOSS);
    }

    public function drawTeam()
    {
        return $this->scores()->where('status', '=', Score::STATUS_DRAW);
    }

    /**
     * @return bool
     */
    public function isDraw(): bool
    {
        return $this->higher_score === $this->lower_score;
    }
}
