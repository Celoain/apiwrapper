<?php

namespace Celoain\ApiWrapper\Enums\Resource;

enum CastTypes: string
{
    case ARRAY = 'array';
    case BOOLEAN = 'bool';
    case COLLECTION = 'collection';
    case DATE = 'date';
    case DATETIME = 'datetime';
    case FLOAT = 'float';
    case INTEGER = 'int';
    case OBJECT = 'object';
    case STRING = 'string';
    case TIMESTAMP = 'timestamp';
}
