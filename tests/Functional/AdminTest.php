<?php


namespace Test\Functional;


use Tests\BaseTestCase;

class AdminTest extends BaseTestCase
{
    /**
     * Test if we can successful login
     */
    public function testSuccessfulLogin()
    {
        $response = $this->runApp('GET', '/admin', null, [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test if we can fail the login
     */
    public function testFailedLogin()
    {
        $response = $this->runApp('GET', '/admin', null, [
            'PHP_AUTH_USER' => 'wrong_login',
            'PHP_AUTH_PW' => 'wrong_password',
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function setUp()
    {
        $this->customSettings['auth'] = [
            'login' => 'admin',
            'password' => 'admin',
        ];
        parent::setUp();
    }
}
