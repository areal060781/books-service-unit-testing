<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('books')->insert([
            'title' => 'War of Worlds',
            'description' => 'A sciencie function masterpiece about Martians invading London',
            'author' => 'H. G. Wells',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('books')->insert([
            'title' => 'A Wrinkle in Time',
            'description' => 'A young girls goes on a mission to save her father who has gone missing after working on a mysterious project called a tesseract.',
            'author' => "Madeleine L'Engle",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
