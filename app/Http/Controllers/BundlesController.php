<?php

namespace App\Http\Controllers;

use App\Book;
use App\Bundle;
use App\Transformer\BundleTransformer;

/**
 * Class BundlesController
 * @package App\Http\Controllers
 */
class BundlesController extends Controller
{
    public function show($id)
    {
        $bundle = Bundle::findOrFail($id);
        $data = $this->item($bundle, new BundleTransformer());
        return response()->json($data);
    }

    /**
     * @param $bundleId
     * @param $bookId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBook($bundleId, $bookId)
    {
        $bundle = Bundle::findOrFail($bundleId);
        $book = Book::findOrFail($bookId);

        $bundle->books()->attach($book);
        $data = $this->item($bundle, new BundleTransformer());

        return response()->json($data);
    }

    public function removeBook($bundleId, $bookId)
    {
        $bundle = Bundle::findOrFail($bundleId);
        $book = Book::findOrFail($bookId);

        $bundle->books()->detach($book);

        return response(null, 204);
    }
}
