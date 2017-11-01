<?php

namespace SfRestApi\Factory;

use SfRestApi\Request\TreeRequest;
use SfRestApi\Request\BatchRequest;
use SfRestApi\Request\CompositeRequest;
use SfRestApi\Request\Request;

class RequestFactory
{
    public static function init( String $type = '' ) {
        switch (strtolower( $type )) {
            case 'tree':
                return TreeRequest::getInstance();
                break;
            case 'composite':
                return CompositeRequest::getInstance();
                break;
            case 'batch':
                return BatchRequest::getInstance();
                break;
            default:
                return Request::getInstance();
        }
    }

}