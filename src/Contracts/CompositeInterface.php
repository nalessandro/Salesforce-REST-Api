<?php

namespace SfRestApi\Contracts;

interface RequestInterface
{
	public function request ( string $args ) : \stdClass;
}