<?php

namespace Cforrence;

use Auth;
use Eloquent;

class ModelHistory extends Eloquent
{

    public $timestamps = false;
    protected $table = 'model_history';
    protected $dates = ['executed_at'];
    protected $fillable = [
        'user_id',
        'message',
        'executed_at',
        'additional_information',
        'link'
    ];


    protected $casts = [
        'additional_information' => 'array',
    ];

    static function boot()
    {
        parent::boot();

        self::saving(function ($a) {
            $a->user_id = Auth::check() ? Auth::id() : null;
            $a->executed_at = time();
        });
    }

    /**
     *
     * Scope for only fetching history of context
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $context_name
     * @param $context_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeContext($query, $context_name, $context_id)
    {
        return $query->where('context_type', $context_name)->where('context_id', $context_id);
    }

    public function getHasContextAttribute()
    {
        return $this->context_type != null && $this->context_id != null;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getDisplayContextAttribute()
    {
        if (!$this->has_context) {
            return '';
        }
        if ($this->link == null) {
            return "{$this->context_type} #{$this->context_id}";
        } else {
            return "<a href=\"{$this->link}\">{$this->context_type} #{$this->context_id}";
        }
    }

    public function context()
    {
        return $this->morphTo();
    }

}