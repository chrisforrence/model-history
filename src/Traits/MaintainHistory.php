<?php

namespace Cforrence\Traits;

use Route;

/**
 * This trait is intended to maintain a log of changes to models that implement this trait.
 *
 * Class MaintainHistory
 * @package Cforrence\Traits
 *
 */
trait MaintainHistory
{

    private static function getObjectLabel($model)
    {
        foreach (config('model-history.potential-name-attributes') as $attribute) {
            if (isset($model->$attribute)) {
                return $model->$attribute;
            }
        }

        return '';
    }

    static function boot()
    {

        parent::boot();

        self::created(function ($model) {
            $model->history->create([
                'link' => Route::has(str_plural(strtolower(class_basename($model))) . '.show') ? route(str_plural(strtolower(class_basename($model))) . '.show',
                    [$model->id]) : null,
                'message' => 'Created new ' . strtolower(class_basename($model)) . ' "' . self::getObjectLabel($model) . '"'
            ]);
        });

        self::updating(function ($model) {
            $changes = $model->getDirty();
            $changed = [];
            foreach ($changes as $key => $value) {
                $changed[] = ['key' => $key, 'old' => $model->getOriginal($key), 'new' => $model->$key];
            }


            $model->history->create([
                'message' => 'Updating ' . strtolower(class_basename($model)) . ' "' . self::getObjectLabel($model) . '"',

                'link' => Route::has(str_plural(strtolower(class_basename($model))) . '.show') ? route(str_plural(strtolower(class_basename($model))) . '.show',
                    [$model->id]) : null,
                'additional_information' => json_encode($changed),
            ]);
        });

        self::deleting(function ($model) {
            $model->history->create([
                'message' => 'Deleting ' . strtolower(class_basename($model)) . ' "' . self::getObjectLabel($model) . '"',
            ]);
        });

    }

    public function history()
    {
        return $this->morphMany('Cforrence\ModelHistory', 'parent');
    }
}