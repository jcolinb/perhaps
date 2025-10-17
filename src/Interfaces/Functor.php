<?php

namespace Gateless\Perhaps\Interfaces;

use Closure;

interface Functor
{
    public function map(Closure $closure) :Functor;

    public function flat() :mixed;
}
