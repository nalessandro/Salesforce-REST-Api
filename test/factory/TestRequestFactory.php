<?php

namespace Test\factory;

class TestRequestFactory extends \PHPUnit\Framework\TestCase
{
    public function setup() {}

    public function tearDown() {}

    public function test_create_tree_request() {
        $instance = \SfRestApi\Factory\RequestFactory::init('Tree');
        $this->assertInstanceOf(\SfRestApi\Request\TreeRequest::class, $instance);
    }

    public function test_create_batch_request() {
        $instance = \SfRestApi\Factory\RequestFactory::init('Batch');
        $this->assertInstanceOf(\SfRestApi\Request\BatchRequest::class, $instance);
    }

    public function test_create_composite_request() {
        $instance = \SfRestApi\Factory\RequestFactory::init('Composite');
        $this->assertInstanceOf(\SfRestApi\Request\CompositeRequest::class, $instance);
    }

    public function test_create_request () {
        $instance =\SfRestApi\Factory\RequestFactory::init();
        $this->assertInstanceOf(\SfRestApi\Request\Request::class, $instance);
    }

}