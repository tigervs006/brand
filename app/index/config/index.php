<?php

/** @var \app\services\system\ConfigServices $services */
$services = app()->make(\app\services\system\ConfigServices::class);
$result = $services->getData(['type' => 2, 'status' => 1], null, 'name, value')->toArray();
return array_column($result, 'value', 'name');