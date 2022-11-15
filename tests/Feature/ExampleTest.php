<?php
namespace Tests\Feature;
use PHPUnit\Framework\TestCase;
use SmyPhp\Core\Application;


class ExampleTest extends TestCase
{
    /**
     * Test Application
     */
    public function applicationTest()
    {
        $app = new Application;
    
        $response = $app->router->get('/', function(){
            //...code
        });

        // $response->assertStatus(200);
        $this->assertNotNull($response, "Not Null");
    }
}