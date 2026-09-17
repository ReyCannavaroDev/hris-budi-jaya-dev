<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class defaultUsersTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/default_users', $payload);

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
		    "name" => "string:191:optional",
		    "email" => "string:191:optional",
		    "username" => "string:60:optional",
		    "email_verified_at" => "datetime:optional",
		    "password" => "string:191:optional",
		    "m_comp_id" => "bigint:optional",
		    "m_dir_id" => "bigint:optional",
		    "is_active" => "boolean:required",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "remember_token" => "string:100:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "profil_image" => "string:191:optional",
		    "telp" => "string:191:optional",
		    "m_kary_id" => "bigint:optional",
		    "updated_id" => "bigint:optional",
		    "default_users_socialite" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "default_users_id" => "bigint:required:autocreate",
		            "provider" => "string:191:required",
		            "username" => "string:191:optional",
		            "email" => "string:191:optional",
		            "token" => "string:191:optional",
		            "avatar" => "string:255:optional",
		            "status" => "string:20:required",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ]
		];

        $this->call('POST', '/operation/default_users', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('default_users', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}