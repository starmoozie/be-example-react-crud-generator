<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use Resources\BootMethodTrait;
    use Resources\SearchColumnLikeTrait;
    use Resources\FilterScope;
    use Resources\ActivitylogTrait;

    public static function getRelationship()
    {
        return (new Self)->with;
    }

    /**
     * Get the fillable attributes for the model.
     *
     * @return array<string>
     */
    public function getFillable()
    {
        return $this->fillable;
    }
}
