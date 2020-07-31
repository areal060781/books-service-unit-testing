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
        factory(\App\Author::class, 10)->create()->each(function($author){
            $author->ratings()->saveMany(
                factory(\App\Rating::class, rand(20,50))->make()
            );
            $booksCount = rand(1,5);

            while ($booksCount>0){
                $book = factory(\App\Book::class)->make();
                $author->books()->save($book);
                $book->ratings()->saveMany(
                    factory(\App\Rating::class, rand(20,50))->make()
                );
                $booksCount--;
            }
        });
    }
}
