<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Support\Facades\Storage;

class UserTest extends TestCase
{


    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->files = [
            'provider1FileContent.json' => '
                [
                    {
                        "parentAmount":100,
                        "Currency":"USD",
                        "parentEmail":"parent1@parent.eu",
                        "statusCode":1,
                        "registrationDate": "2018-11-30",
                        "parentIdentification": "d3d29d70-1d25-11e3-8591-034165a3a613"
                    },
                    {
                        "parentAmount":200,
                        "Currency":"EGP",
                        "parentEmail":"parent1@parent.eu",
                        "statusCode":2,
                        "registrationDate": "2018-11-30",
                        "parentIdentification": "d3d29d70-1d25-11e3-8591-034165a3a613"
                    }
                ]',
                'provider2FileContent.json' => '
                [
                    {
                        "balance":300,
                        "currency":"AED",
                        "email":"parent2@parent.eu",
                        "status": 300,
                        "created_at": "22/12/2018",
                        "id": "4fc2-a8d1"
                    }
                ]'
            ];

        foreach ($this->files as $fileName => $fileContent) {
            Storage::put("providers/{$fileName}", $fileContent);
        }

        $this->apiVersion = 'api/v1';
    }

    /**
     * Test to get all users.
     *
     * @return void
     */
    public function testGetAllUsers()
    {
        $result = $this->get("{$this->apiVersion}/users")->assertStatus(200);
        $result = collect(json_decode($result->getContent(), true));
        $this->assertNotNull($result->where('id',json_decode($this->files['provider1FileContent.json'])[0]->parentIdentification));
        $this->assertNotNull($result->where('id',json_decode($this->files['provider2FileContent.json'])[0]->id));
    }

    /**
     * Test get users with pagination
     *
     * @return void
     */
    public function testGetUsersWithPagination()
    {
        $result = $this->get("{$this->apiVersion}/users?perPage=1")->assertStatus(200);
        $result = collect(json_decode($result->getContent(), true)['data']);

        $this->assertEquals(1, $result->count());
    }

    /**
     * Test get all users with provider query
     *
     * @return void
     */
    public function testGetAllUsersWithProviderQuery()
    {
        // Test with provider 1
        $result = $this->get("{$this->apiVersion}/users?provider=provider1FileContent")->assertStatus(200);
        $result = collect(json_decode($result->getContent(), true));
        $this->assertNotNull($result->where('id',json_decode($this->files['provider1FileContent.json'])[0]->parentIdentification));
        $this->assertEquals(0, $result->where('id',json_decode($this->files['provider2FileContent.json'])[0]->id)->count());

        // Test with provider 2
        $result = $this->get("{$this->apiVersion}/users?provider=provider2FileContent")->assertStatus(200);
        $result = collect(json_decode($result->getContent(), true));
        $this->assertNotNull($result->where('id',json_decode($this->files['provider2FileContent.json'])[0]->id));
        $this->assertEquals(0, $result->where('id',json_decode($this->files['provider1FileContent.json'])[0]->parentIdentification)->count());
    }

    /**
     * Test get all users with all provider query.
     *
     * @return void
     */
    public function testGetAllUsersWithAllProviderQuery()
    {
        $query = '?provider=provider1FileContent&&statusCode=authorized&&balanceMin=100&&balanceMax=300&&currency=USD';

        $result = $this->get("{$this->apiVersion}/users{$query}")->assertStatus(200);
        $result = collect(json_decode($result->getContent(), true));
        $this->assertNotEquals(0, $result->where('id',json_decode($this->files['provider1FileContent.json'])[0]->parentIdentification)->count());
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        foreach (array_keys($this->files) as $fileName ) {
            Storage::delete("providers/{$fileName}");
        }
    }
}
