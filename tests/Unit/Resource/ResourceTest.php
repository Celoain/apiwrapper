<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\Helpers\Resources\BasicResource;

it('constrcuts a resource', function () {
    $data = [
        'test' => 'Lorem Ipsum',
    ];

    $resource = new BasicResource($data);

    expect($resource->attributes)
        ->toHaveCount(2)
        ->and($resource->attributes)
        ->toHaveKey('id')
        ->and($resource->attributes)
        ->toHaveKey('test')
        ->and($resource->attributes['test'])
        ->toBe($data['test']);
});

it('handles an attribute', function () {

    $resource = new BasicResource;
    $resource->attributes = [];

    $resource->setAttribute('int', '123');

    expect($resource->attributes)
        ->toHaveKey('int')
        ->and($resource->getDirty())
        ->toHaveKey('int')
        ->and($resource->attributes['int'])
        ->toBeNumeric()
        ->and($resource->attributes['int'])
        ->toBe(123)
        ->and($resource->getDirty()['int'])
        ->toBeNumeric()
        ->and($resource->getDirty()['int'])
        ->toBe(123);

    $resource->setAttribute('value', 123);

    expect($resource->attributes)
        ->toHaveKey('value')
        ->and($resource->getDirty())
        ->toHaveKey('value')
        ->and($resource->attributes['value'])
        ->toBe(123)
        ->and($resource->getDirty()['value'])
        ->toBe(123);

});

it('handles attributes', function () {
    $oldData = [
        'title' => 'Test',
        'body' => 'Lorem Ipsum',
    ];

    $newData = [
        'int' => '123',
        'title' => 'tseT',
        'author' => 'John Doe',
    ];

    $resource = new BasicResource;

    $resource->attributes = $oldData;

    $returnValue = $resource->setAttributes($newData);

    expect($resource)
        ->toBe($returnValue)
        ->and($resource->attributes)
        ->toHaveCount(4)
        ->and($resource->attributes)
        ->toHaveKey('title')
        ->and($resource->attributes['title'])
        ->toBe($newData['title'])
        ->and($resource->attributes['body'])
        ->toBe($oldData['body']);

    $resource->setAttributes($newData, true);

    expect($resource->attributes)
        ->toHaveCount(3)
        ->and($resource->getDirty())
        ->toHaveCount(3);

});

it('allows magic getters and setters', function () {

    $resource = new BasicResource;

    expect($resource->hasAttribute('title'))
        ->toBeFalse();

    $resource->setAttribute('title', 'Lorem Ipsum');

    expect($resource->hasAttribute('title'))
        ->toBeTrue()
        ->and($resource->getAttribute('title'))
        ->toBe('Lorem Ipsum');

    $resource->body = 'Test';

    expect($resource->hasAttribute('body'))
        ->toBeTrue()
        ->and($resource->body)
        ->toBe('Test');

});

it('casts attributes', function () {
    $data = [
        'int' => '123',
        'datetimeFromInt' => 1640995200,
        'datetime' => '2022-01-01 17:35:00',
        'date' => '2022-01-01',
        'class' => ['sub' => 'sub'],
        'classCollection' => [
            ['sub' => 'sub'],
            ['sub' => 'sub'],
            ['sub' => 'sub'],
        ],
        'notCast' => ['sub' => 'sub'],
    ];

    $resource = new BasicResource($data);

    expect($resource->class)
        ->toBeInstanceOf(BasicResource::class)
        ->and($resource->date)
        ->toBeInstanceOf(Carbon::class)
        ->and($resource->datetime)
        ->toBeInstanceOf(Carbon::class)
        ->and($resource->datetimeFromInt)
        ->toBeInstanceOf(Carbon::class)
        ->and($resource->classCollection)
        ->toBeInstanceOf(Collection::class)
        ->and($resource->classCollection)
        ->toContainOnlyInstancesOf(BasicResource::class)
        ->and($resource->notCast)
        ->toBeNull();

});

it('converts to json', function () {
    $data = ['title' => 'Lorem Ipsum'];

    $resource = new BasicResource($data);

    expect($resource->toJson())
        ->toBe('{"id":1,"title":"Lorem Ipsum"}');

});

it('creates a resource from JSON', function () {
    $json = '{"id":1,"title":"Lorem Ipsum"}';
    $resource = BasicResource::fromJSON($json);

    expect($resource->id)
        ->toBe(1)
        ->and($resource->title)
        ->toBe('Lorem Ipsum');

});

it('gets its own ID', function () {
    $resource = new BasicResource;

    expect($resource->getId())
        ->toBe(1);
});

it('converts to array', function () {
    $data = [
        'test' => 'test',
        'class' => ['subclass' => 'subclass'],
        'classCollection' => [
            ['sub' => 'sub'],
            ['sub' => 'sub'],
            ['sub' => 'sub'],
        ],
    ];

    $resource = new BasicResource($data);

    expect($resource->toArray()['classCollection'][0]['sub'])
        ->toBe('sub')
        ->and($resource->toArray()['class']['subclass'])
        ->toBe('subclass');

});
