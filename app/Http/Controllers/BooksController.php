<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Transformer\BookTransformer;
/**
 * Class BooksController
 * @package App\Http\Controllers
 */
class BooksController extends Controller
{
    /**
     * GET /books
     * @return array
     */
    public function index()
    {
        return $this->collection(Book::all(), new BookTransformer());
        //return ['data' => Book::all()->toArray()];
        //return response()->json(Book::all());
    }

    /**
     * GET /books/{id}
     * @param integer $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->item(Book::findOrFail($id), new BookTransformer());
        //return ['data' => Book::findOrFail($id)->toArray()];
        //return response()->json(Book::find($id));
    }

    /**
     * POST /books
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $book = Book::create($request->all());
        $data = $this->item($book, new BookTransformer());

        return response()->json(
            $data,
            201,
            ['location' => route('books.show', ['id' => $book->id])]
        );

        /*
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:authors',
            'location' => 'required|alpha'
        ]);

        $book = Book::create($request->all());

        return response()->json($book, 201);
        */
    }

    /**
     * PUT /books/{id}
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }

        $book->fill($request->all());
        $book->save();

        return $this->item($book, new BookTransformer());
        //return ['data' => $book->toArray()];
        /*
        $book = Book::findOrFail($id);
        $book->update($request->all());

        return response()->json($book, 200);
        */
    }

    /**
     * DELETE /books/{id}
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }
        $book->delete();

        return response(null, 204);

        /*
        Book::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
        */
    }
}
