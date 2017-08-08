<?php

use gdpa\PepGateway\PepGateway;
use PHPUnit\Framework\TestCase;

class PepGatewayTest extends TestCase {

    public function testBuy()
    {
        $pepGateway = new PepGateway('111111', '111111', './pep-certificate');
        $date = date('Y-m-d H:i:s');
        $this->assertNotEmpty($pepGateway->buy(1, $date, 1000, 'http://example.com/', $date));
    }

    public function testReverse()
    {
        $pepGateway = new PepGateway('111111', '111111', './pep-certificate');
        $date = date('Y-m-d H:i:s');
        $this->assertNotEmpty($pepGateway->reverse(1, $date, 1000, 'http://example.com/', $date));
    }
}