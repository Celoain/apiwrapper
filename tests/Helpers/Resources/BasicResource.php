<?php

namespace Tests\Helpers\Resources;

use Carbon\Carbon;
use Celoain\ApiWrapper\Enums\Resource\CastTypes;
use Celoain\ApiWrapper\Resources\AbstractResource;
use Celoain\ApiWrapper\Resources\Traits\Create;
use Celoain\ApiWrapper\Resources\Traits\Delete;
use Celoain\ApiWrapper\Resources\Traits\Get;
use Celoain\ApiWrapper\Resources\Traits\GetAll;
use Celoain\ApiWrapper\Resources\Traits\Update;
use Illuminate\Support\Collection;

/**
 * @property int int
 * @property Carbon datetimeFromInt
 * @property Carbon datetime,
 * @property Carbon date
 * @property BasicResource class
 * @property Collection<int, BasicResource> classCollection
 * @property null notCast
 */
class BasicResource extends AbstractResource
{
    use Create;
    use Delete;
    use Get;
    use GetAll;
    use Update;

    public array $attributes = [
        'id' => 1,
    ];

    protected array $casts = [
        'int' => CastTypes::INTEGER,
        'datetimeFromInt' => CastTypes::DATETIME,
        'datetime' => CastTypes::DATETIME,
        'date' => CastTypes::DATE,
        'class' => BasicResource::class,
        'classCollection' => BasicResource::class,
        'notCast' => 'someClass',
    ];
}
