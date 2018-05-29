<?php


namespace Test\Functional;


use Tests\BaseTestCase;

class HomepageTest extends BaseTestCase
{
    /**
     * Test that the index route returns a rendered response of homepage
     */
    public function testGetHomepage()
    {
        $response = $this->runApp('GET', '/');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Hello world', (string) $response->getBody());
        $this->assertNotContains('SlimFramework', (string) $response->getBody());
    }
}
