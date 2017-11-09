<?php

namespace SfRestApi\Contracts;

interface CompositeInterface
{
    public static function getInstance();

	public function request ( string $args ) : \stdClass;
}