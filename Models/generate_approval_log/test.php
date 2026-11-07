<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class generateApprovalLogTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/generate_approval_log', $payload);

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
		    "m_comp_id" => "bigint:optional",
		    "m_dir_id" => "bigint:optional",
		    "nomor" => "string:191:optional",
		    "generate_approval_id" => "bigint:optional",
		    "generate_approval_det_id" => "bigint:optional",
		    "trx_table" => "string:191:required",
		    "trx_id" => "bigint:required",
		    "trx_name" => "string:191:required",
		    "trx_nomor" => "string:191:optional",
		    "trx_date" => "date:required",
		    "trx_object" => "string:191:optional",
		    "trx_creator_id" => "bigint:optional",
		    "action_type" => "string:191:required",
		    "action_user_id" => "bigint:optional",
		    "action_at" => "datetime:optional",
		    "action_note" => "string:191:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/generate_approval_log', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('generate_approval_log', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}