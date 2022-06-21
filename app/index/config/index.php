<?php
declare (strict_types = 1);

/** @var \app\services\system\ConfigServices $services */
$services = app()->make(\app\services\system\ConfigServices::class);
$result = $services->getData(['type' => [1, 4]], null, 'name, value')->toArray();
return array_column($result, 'value', 'name');
