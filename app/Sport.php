<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $primaryKey = 'sport_id';
    protected $table = 'sports';
    public $incrementing = false;

    public function scoreType()
    {
        return $this->belongsTo('App\ScoreType', 'score_type_id');
    }

    public function leagues()
    {
        return $this->hasMany('App\League', 'league_id');
    }
}
