<?php
namespace Gateless\Perhaps;
use Gateless\Perhaps\Interfaces\Functor;
use Closure;

class Nothing implements Functor
{
    private function __construct(protected ?string $message='Nothing') {}

    public function __toString() :string
    {
        return "Nothing<$this->message>";
    }

    public static function message (?string $message='Nothing') :self
    {
        return new self($message);
    }

    public function map (Closure $fn) :self
    {
        return $this;
    }

    public function flat () :string
    {
        return $this->reason();
    }

    public function reason () :string
    {
        return $this->message;
    }

    public function ifNot (Closure $function) :self
    {
        $function($this->reason());
        return $this;
    }
}