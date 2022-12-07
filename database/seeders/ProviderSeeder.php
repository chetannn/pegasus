<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        Provider::insert([
            ['name' => 'GitHub'],
            ['name' => 'GitLab'],
            ['name' => 'Bitbucket'],
        ]);
    }
}
