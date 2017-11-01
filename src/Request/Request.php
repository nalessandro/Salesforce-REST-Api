<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\ClientConfigInterface;

/**
 * Class Request
 *
 * @todo DELETE
 *
 * @package SfRestApi\Config
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Request extends BaseRequest
{
    /**
     * Request constructor.
     * @param ClientConfigInterface $config
     */
    public function __construct(ClientConfigInterface $config)
    {
        parent::__construct($config);
    }


}