<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function getCategories(){
        $categories = Category::orderBy('created_at','DESC')->limit(10)->get();
        return response()->json($categories);
    }
    public function getCategory($cate_id){
        $category = Category::find($cate_id);
        return response()->json($category);
    }
    public function createCategory(Request $request){
        $category = Category::create([
            'title'=> $request['title'],
        ]);

        return $category;
    }
    public function updateCategory(Request $request){
        $category = Category::find($request['category_id']);
        $category->title = $request->title;
        $category->save();
        return $category;
    }

    public function deleteCategory(Request $request){
        $category = Category::find($request->category_id);
        $category->title = $request->title;
        $category->delete();
        return $category;
    }
}
