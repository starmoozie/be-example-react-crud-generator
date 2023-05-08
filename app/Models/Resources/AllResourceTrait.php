<?php

namespace App\Models\Resources;

/**
 * Call all traits inside resources folder
 */
trait AllResourceTrait
{
    use BootMethodTrait;
    use ActivitylogTrait;
    use FilterScopeTrait;
    use TableActivityScopeTrait;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    public static function getRelationship()
    {
        return (new Self)->with;
    }
}
