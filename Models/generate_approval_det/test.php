<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class generateApprovalDetTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/generate_approval_det', $payload);

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
		    "generate_approval_id" => "bigint:optional",
		    "level" => "integer:required",
		    "urutan_level" => "integer:required",
		    "tipe" => "string:191:required",
		    "m_role_id" => "bigint:optional",
		    "default_user_id" => "bigint:optional",
		    "is_full_approve" => "boolean:optional",
		    "is_skippable" => "boolean:optional",
		    "assigned_at" => "datetime:required",
		    "action_type" => "string:191:optional",
		    "action_user_id" => "bigint:optional",
		    "action_at" => "datetime:optional",
		    "action_note" => "string:191:optional",
		    "is_done" => "boolean:required",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/generate_approval_det', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('generate_approval_det', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}