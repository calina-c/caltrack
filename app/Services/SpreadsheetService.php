<?php

$employees = [
    ['name' => 'John', 'city' => 'Dallas'],
    ['name' => 'Jane', 'city' => 'Austin'],
    ['name' => 'Jake', 'city' => 'Dallas'],
    ['name' => 'Jill', 'city' => 'Dallas'],
];

$offices = [
    ['office' => 'Dallas HQ', 'city' => 'Dallas'],
    ['office' => 'Dallas South', 'city' => 'Dallas'],
    ['office' => 'Austin Branch', 'city' => 'Austin'],
];

$output = [
    'Dallas' => [
        'Dallas HQ' => ['John', 'Jake', 'Jill'],
        'Dallas South' => ['John', 'Jake', 'Jill'],
    ],
    'Austin' => [
        'Austin Branch' => ['Jane'],
    ],
];

$collectedEmployees = collect($employees)->groupBy('city')->map(function ($group) {
    return $group->pluck('name');
});
$collectedOffices = collect($offices)->groupBy('city')->map(function ($group) {
    return $group->pluck('office');
});

$cities = $collectedEmployees->keys()->merge($collectedOffices->keys())->unique();
$cities->mapWithKeys(function ($city) use ($collectedEmployees, $collectedOffices) {
    $offices = $collectedOffices->get($city, collect());
    $employees = $collectedEmployees->get($city, collect());

    return [$city => $offices->mapWithKeys(function ($office) use ($employees) {
        return [$office => $employees->toArray()];
    })];
})->toArray();
