<?php

namespace App\Models\Resources;

use Spatie\Activitylog\LogOptions;

/**
 * 
 */
trait ActivitylogTrait
{
    use \Spatie\Activitylog\Traits\LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName($this->getTable());
    }
}
