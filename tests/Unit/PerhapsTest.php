<?php

use Gateless\Perhaps\Nothing;
use Gateless\Perhaps\Perhaps;

test('It can lift a value', function (mixed $value) {
    expect(Perhaps::return($value))->toBeInstanceOf(Perhaps::class);
})
    ->with([
        'string' => 'Test String',
        'integer' => 1,
        'array'   => [1,2,3],
        'object'  => (object) ['name' => 'John','age' => 44]
    ]);

test('It will return Nothing if it lifts null', function () {
    expect(Perhaps::return(null))->toBeInstanceOf(Nothing::class);
});

test('It will return Nothing if it lifts Nothing', function () {
    expect(Perhaps::return(Nothing::message('Nada')))
        ->toBeInstanceOf(Nothing::class)
        ->reason()
        ->toBe('Nada');
});

test('It can map over a non-null value', function (mixed $value,Closure $fn,mixed $expected) {
    expect(Perhaps::return($value)->map($fn)->flat())->toBe($expected);
})
    ->with([
        'string' => [
            'Test String',
            fn (string $string) => strlen($string),
            11],
        'integer' => [
            1,
            fn (int $int) => $int + 1,
            2],
        'array'   => [
            [1,2,3],
            fn (array $array) => array_map(fn (int $int) => $int + 1,$array),
            [2,3,4]],
        'object'  => [
            (object) ['name' => 'John','age' => 44],
            fn (object $obj) => $obj->name,
            'John'],
    ]);

test('It will return Nothing if mapping throws an error', function () {
    expect(
        Perhaps::return('String')
            ->map(fn (int $x) => $x + 1)
    )
        ->toBeInstanceOf(Nothing::class)
        ->reason()
        ->toBeString();
});

test('toString method outputs a formatted type string', function () {
    expect(Perhaps::return(Nothing::message('message'))->__toString())
        ->toBe("Nothing<message>");
    expect(Perhaps::return([1,2,3])->__toString())
        ->toBe("Perhaps<array>");
});