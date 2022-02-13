<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProvidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
