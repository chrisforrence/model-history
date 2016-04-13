<?php

namespace Cforrence\Traits;

use Route;

/**
 * This trait is intended to maintain a log of changes to participating models.
 *
 * Class MaintainHistory
 */
trait MaintainHistory
{
    /**
     * Attempts to label a model for display purposes.
     *
     * @param $model
     *
     * @return string
     */
    private static function getObjectLabel($model)
    {
        /*
         * Iterates through potential naming attributes ('label', 'name', 'title', etc.)
         */
        foreach (config('model-history.potential_name_attributes') as $attribute) {
            if (isset($model->$attribute)) {
                return $model->$attribute;
            }
        }

        return '';
    }

    public static function boot()
    {
        parent::boot();

        /*
         * Adds a ModelHistory entry to the model after creating the model
         */
        self::created(function ($model) {

            $model->history()->create([
                'link' => Route::has(str_plural(strtolower(class_basename($model))).'.show') ? route(str_plural(strtolower(class_basename($model))).'.show',
                    [$model->id]) : null,
                'message' => 'Created new '.strtolower(class_basename($model)).' "'.self::getObjectLabel($model).'"',
            ]);
        });

        /*
         * Adds a ModelHistory entry to the model after updating the model
         */
        self::updating(function ($model) {

            /*
             * Gets the model's altered values and tracks what had changed
             */
            $changes = $model->getDirty();
            $changed = [];
            foreach ($changes as $key => $value) {
                $changed[] = ['key' => $key, 'old' => $model->getOriginal($key), 'new' => $model->$key];
            }

            $model->history()->create([
                'message' => 'Updating '.strtolower(class_basename($model)).' "'.self::getObjectLabel($model).'"',
                'link' => Route::has(str_plural(strtolower(class_basename($model))).'.show') ? route(str_plural(strtolower(class_basename($model))).'.show',
                    [$model->id]) : null,
                'additional_information' => json_encode($changed),
            ]);
        });

        /*
         * Adds a ModelHistory entry to the model prior to deleting it
         */
        self::deleting(function ($model) {
            $model->history()->create([
                'message' => 'Deleting '.strtolower(class_basename($model)).' "'.self::getObjectLabel($model).'"',
            ]);
        });
    }

    /**
     * Adds the history relationship to participating models.
     *
     * @return mixed
     */
    public function history()
    {
        return $this->morphMany('Cforrence\ModelHistory', 'context');
    }
}
