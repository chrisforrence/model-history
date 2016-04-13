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

    /**
     * Adds functionality to ModelHistory.
     */
    static function boot()
    {
        parent::boot();

        /**
         * When saving a ModelHistory entry, set the user_id and executed_at fields automatically.
         */
        self::saving(function ($a) {
            $a->user_id = Auth::check() ? Auth::id() : null;
            $a->executed_at = time();
        });
    }

    /**
     * Returns the user relationship
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(config('model-history.user_model'));
    }

    /**
     * Returns whether or not a context type/id are present
     * @return bool
     */
    public function contextAvailable()
    {
        return $this->context_type !== null && $this->context_id !== null;
    }

    /**
     * Returns a display string
     * @return string
     */
    public function getDisplayContextAttribute()
    {
        if (!$this->contextAvailable()) {
            return '';
        }
        if ($this->link === null) {
            return "{$this->context_type} #{$this->context_id}";
        }

        return "<a href=\"{$this->link}\">{$this->context_type} #{$this->context_id}";

    }

    /**
     * Provides context for the history model
     * @return mixed
     */
    public function context()
    {
        return $this->morphTo();
    }

}