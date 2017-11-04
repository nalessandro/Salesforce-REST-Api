<?php

namespace SfRestApi\Contracts;

interface RequestInterface
{
    public function query ( string $q ): \stdClass;

    public function insert ( string $args ): \stdClass;

    public function update ( string $args ): \stdClass;

    public function delete ( string $args ): \stdClass;

}