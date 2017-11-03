<?php

namespace SfRestApi\Contracts;

interface RequestInterface
{
    public function query( string $q ): \stdClass;

    public function insert( array $args ): \stdClass;

    public function update( array $args ): \stdClass;

    public function delete( array $args ): \stdClass;

}