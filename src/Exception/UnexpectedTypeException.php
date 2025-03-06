<?php

namespace App\Exception;

class UnexpectedTypeException extends \LogicException
{
    public function __construct(string $expectedType, mixed $actual)
    {
        $actualType = is_object($actual) ? get_class($actual) : gettype($actual);
        parent::__construct(sprintf('Expected type "%s", but got "%s"', $expectedType, $actualType));
    }
}
