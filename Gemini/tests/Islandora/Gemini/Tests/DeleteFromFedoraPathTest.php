<?php

namespace Islandora\Gemini\Tests;

use Islandora\Gemini\Controller\GeminiController;
use Islandora\Crayfish\Commons\PathMapper\PathMapper;

class DeleteFromFedoraPathTest extends \PHPUnit_Framework_TestCase
{
    public function testReturns500OnException()
    {
        $prophecy = $this->prophesize(PathMapper::class);
        $prophecy->deleteFromFedoraPath("foo")
            ->willThrow(new \Exception("Exception", 500));
        $mock_service = $prophecy->reveal();
        $controller = new GeminiController($mock_service);

        $response = $controller->deleteFromFedoraPath("foo");

        $this->assertTrue(
            $response->getStatusCode() == 500,
            "Response must be 500 when Exception occurs"
        );
    }

    public function testReturns404WhenNotFound()
    {
        $mock_service = $this->prophesize(PathMapper::class)
            ->reveal();
        $controller = new GeminiController($mock_service);

        $response = $controller->deleteFromFedoraPath("foo");

        $this->assertTrue(
            $response->getStatusCode() == 404,
            "Response must be 404 when not found"
        );
    }

    public function testReturns204WhenDeleted()
    {
        $prophecy = $this->prophesize(PathMapper::class);
        $prophecy->deleteFromFedoraPath("foo")
            ->willReturn(true);
        $mock_service = $prophecy->reveal();
        $controller = new GeminiController($mock_service);

        $response = $controller->deleteFromFedoraPath("foo");

        $this->assertTrue(
            $response->getStatusCode() == 204,
            "Response must be 204 when deleted"
        );
    }
}
