<?php

namespace App\Http\Controllers;
use App\Book;

class BooksController extends Controller
{
    public function index(){
        return Book::all();
    }
}
