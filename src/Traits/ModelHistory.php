<?php

namespace Rukhsar\Traits;

use Route;

trait ModelHistory
{
    // Iterates through potential naming attributes (Change depending on your attributes)
    private static function getObjectLabel($model)
    {
        foreach (['title', 'name', 'label', 'id'] as $attribute)
        {
            if (isset($model->$attribute))
            {
                return $model->$attribute;
            }
        }

        return '';
    }

    public static function boot()
    {
        parent::boot();

        // Adds a ModelHistory entry to the model after creating the model
        self::created(function ($model) {

            $model->history()->create([
                'link' => Route::has(str_plural(strtolower(class_basename($model))).'.show') ? route(str_plural(strtolower(class_basename($model))).'.show',
                    [$model->id]) : null,
                'message' => 'Created new '.strtolower(class_basename($model)).' "'.self::getObjectLabel($model).'"',
            ]);
        });

        // Adds a ModelHistory entry to the model after updating the model
        self::updating(function ($model) {

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

        // Adds a ModelHistory entry to the model prior to deleting it
        self::deleting(function ($model) {
            $model->history()->create([
                'message' => 'Deleting '.strtolower(class_basename($model)).' "'.self::getObjectLabel($model).'"',
            ]);
        });

    }

    public function history()
    {
        return $this->morphMany('Rukhsar\ModelHistory', 'context');
    }
}