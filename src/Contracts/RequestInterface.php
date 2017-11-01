<?php

namespace SfRestApi\Contracts;

interface RequestInterface
{
    /**
     * @returns ClientConfig
     */
    public function getConfig();

    public function query();

    public function insert();

    public function update();

    public function delete();

}