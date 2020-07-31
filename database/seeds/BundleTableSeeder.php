<?php

use Illuminate\Database\Seeder;

class BundleTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Bundle::class, 5)->create()->each(function ($bundle) {
            $booksCount = rand(2, 5);
            $bookIds = [];

            while ($booksCount > 0) {
                $book = \App\Book::whereNotIn('id', $bookIds)
                    ->orderByRaw("RAND()")
                    ->first();

                $bundle->books()->attach($book);
                $bookIds[] = $book->id;
                $booksCount--;
            }
        });
    }
}
