<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ScoreType extends Model
{
    protected $primaryKey = 'score_type_id';
    public $incrementing = false;

    public const RUMBLE = 'RUM';
    public const VERSUS = 'VER';

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->score_id = Str::uuid();
        });
    }
}
