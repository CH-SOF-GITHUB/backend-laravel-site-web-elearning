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
        LanguageMedium::create(['language_name' => 'English', 'code' => 'en']);
        LanguageMedium::create(['language_name' => 'French', 'code' => 'fr']);
        LanguageMedium::create(['language_name' => 'Spanish', 'code' => 'es']);
    }
}
