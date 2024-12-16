<?php

namespace Database\Seeders;

use App\Models\LanguageMedium;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageMediumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LanguageMedium::insert([
            ['language_name' => 'English', 'code' => 'en'],
            ['language_name' => 'French', 'code' => 'fr'],
            ['language_name' => 'Spanish', 'code' => 'es'],
            ['language_name' => 'German', 'code' => 'de']
        ]);
    }
}
