<?php

namespace core\interfaces;

use think\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, \Closure $next);
}
