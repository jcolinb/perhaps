<?php
namespace Gateless\Perhaps;
use Gateless\Perhaps\Interfaces\Functor;
use Closure;
use Throwable;

class Perhaps implements Functor
{
    private function __construct(protected mixed $thing) {}

    public static function return (mixed $thing,string $message='Nothing') :self | Nothing
    {
        if (is_null($thing)) return Nothing::message($message);
        if ($thing instanceof Nothing) return $thing;
        return new self($thing);
    }

    public function map (Closure $function) :Functor
    {
        if ($this->thing) {
            try
            {
                $return = $function($this->thing);
            }
            catch (Throwable $e)
            {
                $return = Nothing::message($e->getMessage());
            }

            return self::return($return);
        }

        return $this;
    }

    public function __toString() :string
    {
        return "Perhaps<" . getType($this->thing) . ">";
    }

    public function ifNot (Closure $function) :self
    {
        return $this;
    }

    public static function isNothing (mixed $param) :bool
    {
        return $param instanceof Nothing;
    }

    public function flat() :mixed
    {
        return $this->thing;
    }
}