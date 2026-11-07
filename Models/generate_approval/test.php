<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class generateApprovalTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/generate_approval', $payload);

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
		    "m_approval_id" => "bigint:optional",
		    "trx_id" => "bigint:required",
		    "trx_name" => "string:191:required",
		    "form_name" => "string:191:optional",
		    "trx_table" => "string:191:required",
		    "trx_nomor" => "string:191:optional",
		    "trx_date" => "date:required",
		    "trx_object" => "string:191:optional",
		    "trx_creator_id" => "bigint:optional",
		    "status" => "string:191:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "last_approve_id" => "bigint:optional",
		    "last_approve_det_id" => "bigint:optional"
		];

        $this->call('POST', '/operation/generate_approval', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('generate_approval', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}