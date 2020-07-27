<?php

namespace Tests\App\Http\Controllers;

use TestCase;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::now('UTC'));
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }

    /**
     * @test
     */
    public function index_status_code_should_be_200()
    {
        $this->get('/books')
            ->seeStatusCode(200);
    }

    /**
     * @test
     */
    public function index_should_return_a_collection_of_records()
    {
        $books = factory('App\Book', 2)->create();

        $this->get('/books');

        $content = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $content);

        foreach ($books as $book) {
            $this->seeJson([
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'author' => $book->author,
                'created' => $book->created_at->toIso8601String(),
                'updated' => $book->updated_at->toIso8601String()
            ]);
        }
    }

    /**
     * @test
     */
    public function show_should_return_a_valid_book()
    {
        $book = factory('App\Book')->create();

        $this
            ->get("/books/{$book->id}")
            ->seeStatusCode(200);

        // Get the response and assert the data key exists
        $content = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $content);
        $data = $content['data'];

        // Assert the Book Properties match
        $this->assertEquals($book->id, $data['id']);
        $this->assertEquals($book->title, $data['title']);
        $this->assertEquals($book->description, $data['description']);
        $this->assertEquals($book->author, $data['author']);
        $this->assertEquals($book->created_at->toIso8601String(), $data['created']);
        $this->assertEquals($book->updated_at->toIso8601String(), $data['created']);
    }

    /**
     * @test
     */
    public function show_should_fail_when_the_book_id_does_not_exists()
    {
        $this
            ->get('/books/99999', ['Accept' => 'application/json'])
            ->seeStatusCode(404)
            ->seeJson([
                'error' => [
                    'message' => 'Not Found',
                    'status' => 404
                ]
            ]);
    }

    /**
     * @test
     */
    public function show_route_should_not_match_an_invalid_route()
    {
        $this->get('/book/this-is-invalid');

        $this->assertNotRegExp(
            '/Book not found/',
            $this->response->getContent(),
            'BooksController@show route matching when it should not.'
        );

    }

    /**
     * @test
     */
    public function store_should_save_new_book_in_the_database()
    {
        $this
            ->post('/books', [
                'title' => 'The Invisible Man',
                'description' => 'An invisible man is trapped in the terror of his own creation',
                'author' => 'H. G. Wells'
            ]);

        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertEquals('The Invisible Man', $data['title']);
        $this->assertEquals(
            'An invisible man is trapped in the terror of his own creation',
            $data['description']
        );
        $this->assertEquals('H. G. Wells', $data['author']);
        $this->assertTrue($data['id'] > 0, 'Expected a positive integer, but did not see one.');
        $this->assertArrayHasKey('created', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['created']);
        $this->assertArrayHasKey('updated', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['updated']);
        $this->seeInDatabase('books', ['title' => 'The Invisible Man']);
    }

    /**
     * @test
     */
    public function store_should_respond_with_a_201_and_location_header_when_successful()
    {
        $this->post('/books', [
            'title' => 'The Invisible Man',
            'description' => 'And invisible man is trapped in the error of his own creation',
            'author' => 'H. G. Wells'
        ]);

        $this
            ->seeStatusCode(201)
            ->seeHeaderWithRegExp('Location', '#/books/[\d]+$#');
    }

    /**
     * @test
     */
    public function update_should_only_change_fillable_fields()
    {
        $book = factory('App\Book')->create([
            'title' => 'War of the Worlds',
            'description' => 'A science fiction masterpiece about Martians invading London',
            'author' => 'H. G. Wells',
        ]);

        $this->put("/books/{$book->id}", [
            'id' => 5,
            'title' => 'The War of the Worlds',
            'description' => 'The book is way better than the movie.',
            'author' => 'Wells, H. G.'
        ]);

        $this
            ->seeStatusCode(200)
            ->seeJson([
                'id' => $book->id,
                'title' => 'The War of the Worlds',
                'description' => 'The book is way better than the movie.',
                'author' => 'Wells, H. G.'
            ])
            ->seeInDatabase('books', [
                'title' => 'The War of the Worlds'
            ]);

        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertArrayHasKey('created', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['created']);
        $this->assertArrayHasKey('updated', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['updated']);
    }

    /**
     * @test
     */
    public function update_should_fail_with_an_invalid_id()
    {
        $this
            ->put('/books/999999999')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Book not found'
                ]
            ]);
    }

    /**
     * @test
     */
    public function update_should_not_match_and_invalid_route()
    {
        $this
            ->put('/books/this-is-invalid')
            ->seeStatusCode(404);
    }

    /** @test * */
    public function destroy_should_remove_a_valid_book()
    {
        $book = factory('App\Book')->create();

        $this
            ->delete("/books/{$book->id}")
            ->seeStatusCode(204)
            ->isEmpty();

        $this->notSeeInDatabase('books', ['id' => $book->id]);
    }

    /** @test * */
    public function destroy_should_return_a_404_with_an_invalid_id()
    {
        $this
            ->delete('/books/999999999')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Book not found'
                ]
            ]);
    }

    /** @test * */
    public function destroy_should_not_match_an_invalid_route()
    {
        $this
            ->delete('/books/this-is-invalid')
            ->seeStatusCode(404);
    }


}
