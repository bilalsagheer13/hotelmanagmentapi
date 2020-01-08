<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    public static function boot()
    {
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'created_by')) {
                if (Auth::guard('api')->check()) {
                    if (!(isset($model->created_by) && $model->created_by >= 0)) {
                        $model->created_by = Auth::guard('api')->id();
                    }
                }
            }
        });

        static::updating(function ($model) {

            if (Schema::hasColumn($model->getTable(), 'updated_by')) {
                if (Auth::guard('api')->check()) {
                    if (!(isset($model->updated_by) && $model->updated_by >= 0)) {
                        $model->updated_by = Auth::guard('api')->id();
                    }
                }
            }
        });
        static::deleting(function ($model) {

        });

        parent::boot();
    }
}
