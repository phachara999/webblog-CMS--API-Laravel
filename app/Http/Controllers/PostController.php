<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\post_categories;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function getPosts()
    {
        // $posts = $table('posts')
        $posts = Post::select('posts.*', 'users.name as user_fname', 'users.lname as user_lname')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->get();
        return response()->json($posts);
    }

    public function getPost($post_id)
    {
        $post = Post::select('posts.*', 'users.name as user_fname', 'users.lname as user_lname')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('posts.id', $post_id)
            ->first();
        if ($post) {
            // ดึงข้อมูลหมวดหมู่ของโพสต์จากตาราง post_category
            $categories = post_categories::select('categories.title as category_name','categories.id as cateID')
                ->join('categories', 'post_categories.cate_id', '=', 'categories.id')
                ->where('post_categories.post_id', $post->id)
                ->get();

            // เพิ่มข้อมูลหมวดหมู่ไปยังข้อมูลโพสต์
            $post->categories = $categories;

            return response()->json($post);
        } else {
            return response()->json(['message' => 'ไม่พบโพสที่ค้นหา'], 404);
        }
    }

    public function createPost(Request $request)
    {

        $img1 = $request['img_url'] ?? ' ';
        $img2 = $request['img_url2'] ?? ' ';

        $categoryIds = explode(',', $request['categories']);
        $post = Post::create([
            'title' => $request['title'],
            'body' => $request['body'],
            'user_id' => Auth::user()->id,
            'img_url' => $img1,
            'img_url2' => $img2,
        ]);

        $postId = $post->id;
        foreach ($categoryIds as $cate) {
            post_categories::create([
                'post_id' => $postId,
                'cate_id' => $cate,
            ]);
        }
        return response(['message' => 'create Success']);
    }
    public function editPost(Request $request)
    {

        if (!isset($request['post_id']) || !isset($request['categories']) || !isset($request['body']) || !isset($request['title'])) {
            return response(['message' => 'update no'], 401);
        }
        $categoryIds = explode(',', $request['categories']);

        
        $img1 = $request['img_url'] ?? ' ';
        $img2 = $request['img_url2'] ?? ' ';

        $post = Post::where('id', $request['post_id'])
            ->update([
                'title' => $request['title'],
                'body' => $request['body'],
                'img_url' => $img1,
                'img_url2' => $img2,
            ]);

        $postId = $request['post_id'];

        post_categories::where('post_id', $postId)->delete();

        foreach ($categoryIds as $cate) {
            post_categories::create([
                'post_id' => $request['post_id'],
                'cate_id' => $cate,
            ]);
        }

        return response(['message' => 'update Success']);
    }
    public function deletePost(Request $request)
    {
        $post = Post::find($request->post_id);
        // $category->title = $request->title;
        $post->delete();
        return response(['message' => 'delete Success']);
    }
    public function getMyPost()
    {
        $posts = Post::select('posts.*')
            ->where('user_id', '=', Auth::user()->id)
            ->get();
        return response()->json($posts);
    }
    public function postBycate($cateID)
    {
        $posts = post_categories::select('posts.id as id', 'users.name as user_fname', 'users.lname as user_lname', 'posts.title', 'posts.body', 'posts.img_url')
            ->join('posts', 'posts.id', '=', 'post_categories.post_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('cate_id', $cateID)
            ->get();
            return response()->json($posts);
    }
    // public function user()
    // {
    //     return Auth::user();
    // }
}
