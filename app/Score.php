<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Score extends Model
{
    public const TEAM_1 = 1;
    public const TEAM_2 = 2;

    public const STATUS_WIN = 'win';
    public const STATUS_LOSS = 'loss';
    public const STATUS_DRAW = 'draw';

    protected $primaryKey = 'score_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'match_id', 'user_id', 'points', 'status', 'team', 'is_team'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->score_id = Str::uuid();
        });
    }

    public function league()
    {
        return $this->hasOneThrough('App\League', 'App\Match');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
