<?php

namespace SfRestApi\Contracts;

interface RequestInterface
{
    public function query ( string $q );

    public function insert ( array $args );

    public function update ( array $args );

    public function delete ( array $args );

}