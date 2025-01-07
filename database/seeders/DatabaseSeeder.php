<?php

namespace Database\Seeders;

use App\Models\AttChar;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Character;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Category::create([
            'name' => 'avtomobil',
        ]);
        Category::create([
            'name' => 'texnika',
        ]);
        Attribute::create([
            'name' => 'rangi',
            'category_id' => 1
        ]);
        Attribute::create([
            'name' => 'rangi',
            'category_id' => 2
        ]);
        Attribute::create([
            'name' => 'yili',
            'category_id' => 1
        ]);
        Attribute::create([
            'name' => 'yili',
            'category_id' => 2
        ]);
        Character::create([
            'name' => '2024'
        ]);
        Character::create([
            'name' => '2020'
        ]);
        Character::create([
            'name' => 'qora'
        ]);
        Character::create([
            'name' => 'oq'
        ]);
        AttChar::create([
            'att_id' => 1,
            'char_id' => 3
        ]);
        AttChar::create([
            'att_id' => 2,
            'char_id' => 3
        ]);
        Attchar::create([
            'att_id' => 1,
            'char_id' => 4
        ]);
        Attchar::create([
            'att_id' => 2,
            'char_id' => 4
        ]);
        Attchar::create([
            'att_id' => 3,
            'char_id' => 1
        ]);
        Attchar::create([
            'att_id' => 3,
            'char_id' => 2
        ]);
        Attchar::create([
            'att_id' => 4,
            'char_id' => 1
        ]);
        Attchar::create([
            'att_id' => 4,
            'char_id' => 2
        ]);
    }
}
