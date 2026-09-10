<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class mGradeDTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/m_grade_d', $payload);

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
		    "m_grade_id" => "bigint:required",
		    "keterangan" => "string:191:required",
		    "type" => "string:191:required",
		    "value" => "decimal:required",
		    "factor" => "string:191:required",
		    "day" => "string:191:optional",
		    "big_event" => "boolean:required",
		    "full_week" => "boolean:required",
		    "is_7_5" => "boolean:required",
		    "need_overtime" => "boolean:required",
		    "value_overtime" => "integer:required",
		    "not_checkin" => "boolean:required",
		    "is_month" => "boolean:required",
		    "is_active" => "boolean:required",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "deletor_id" => "bigint:optional",
		    "deleted_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/m_grade_d', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('m_grade_d', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}