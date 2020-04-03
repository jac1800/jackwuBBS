<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    //
    public function index()
    {
        CategoryResource::wrap('data');
        return CategoryResource::collection(Category::all());
    }

}
