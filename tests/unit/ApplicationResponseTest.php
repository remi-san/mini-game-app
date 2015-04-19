<?php
namespace MiniGameApp\Test;

use MiniGameApp\Application\Response\ApplicationResponse;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class ApplicationResponseTest extends \PHPUnit_Framework_TestCase {
    use MiniGameAppMocker;

    /**
     * @test
     */
    public function test()
    {
        $message = 'message';
        $user = $this->getApplicationUser(42, 'adam');

        $response = new ApplicationResponse($user, $message);

        $this->assertEquals($user, $response->getUser());
        $this->assertEquals($message, $response->getMessage());
    }
} 