<?php

namespace App\Models\Resources;

use Spatie\Activitylog\LogOptions;

/**
 * Insert log activity model
 */
trait ActivitylogTrait
{
    use \Spatie\Activitylog\Traits\LogsActivity;

    /**
     * Get activity log options, dependent spatie package
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName($this->getTable());
    }
}
