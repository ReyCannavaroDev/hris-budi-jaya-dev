<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class defaultErrorLogsTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/default_error_logs', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        ff( $responseArr, 'dump data' );

        $this->assertTrue(true);
    }

    public function testCreatingData()
    {
        $user = User::where('username', 'USERNAME')->first();
        $this->assertNotEmpty( $user );
        
        Passport::actingAs($user);

        $payload = [
		    "id" => "bigint:optional:autocreate",
		    "modul" => "string:191:optional",
		    "username" => "string:100:optional",
		    "user_ip" => "string:100:optional",
		    "type" => "string:100:optional",
		    "url" => "string:191:optional",
		    "url_frontend" => "string:191:optional",
		    "payload" => "text:optional",
		    "error_log" => "text:optional",
		    "exception_code" => "string:25:optional",
		    "http_code" => "string:25:optional",
		    "file" => "text:optional",
		    "line" => "string:20:optional",
		    "method" => "string:20:optional",
		    "status" => "string:100:optional",
		    "developer" => "string:191:optional",
		    "developer_note" => "text:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/default_error_logs', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('default_error_logs', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}