<?php

namespace App\Models\Resources;

trait BootMethodTrait
{
    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        // Call Before Creating or Updating Record.
        static::saving(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                // Generate Primary Key
                $model->{$model->getKeyName()} = \Str::uuid()->toString();
            }

            if (\in_array('code', $model->fillable) && !$model->code) {
                $model->code = $model->generateCode($model);
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    protected function generateCode($model)
    {
        $last = $model->where('name', $this->name)->latest()->orderByDesc('code')->first();
        $code = $last ? sprintf('%03d', explode('-', $last->code)[1] + 1) : "001";
        $name = explode(' ', $this->name);

        if (count($name) > 1) {
            $a = '';
            foreach ($name as $value) {
                $a .= \strtoupper($value[0]);
            }
        } else {
            $a = \strtoupper($this->name);
        }

        return "${a}-{$code}";
    }
}
