<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use App\Helpers\FileUploads;
use App\Models\PostLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostsRequest;

class PostsController extends Controller
{

    public function postData($post, $request)
    {
        //file name
        $newImageName = 'thumb-'.$request->name;
        $filePath = 'posts/'. auth()->user()->id;

        $post->name = $request->name;
        $post->description = $request->description;
        $post->status = $request->status;
        $post->category_id = $request->category_id;
        $post->price = $request->price;
        $post->thumbnail =  (new FileUploads)->uploadPhotos($request->thumbnail, $filePath, $newImageName);
        //address
        $post->gps_points = $request->gps_points;

        $post->user_id = auth()->user()->id;
    }

    public function postImage($image, $id, $name)
    {
        $filePath = 'posts/'. auth()->user()->id;

        $post_image = new PostImage();
        $post_image->post_id = $id;
        $post_image->image =  (new FileUploads)->uploadPhotos($image, $filePath, $name);
        $post_image->save();
        return $post_image;
    }

    public function index(Request $request)
    {
        DB::connection()->enableQueryLog();
        $posts = PostLocation::filterAddress($request)
            ->with('post')
            ->paginate();

        return response()->json([$posts, DB::getQueryLog()]);
    }

    public function store(PostsRequest $request)
    {
        DB::beginTransaction();
        try{
            $post = new Post();
            $this->postData($post, $request);
            $post->save();

            //post the data on location table
            $post->location()->create($request->only([
                'address', 'city', 'country', 'state', 'postal_code',
            ]));

            //images
            $post_image = array();
            foreach($request->images as $image)
            {
                $post_image[] = $this->postImage($image, $post->id, $request->name);
            }
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            return $e;
        }

        return response()->json([$post, $post_image]);
    }


    public function show($id)
    {
        $post = Post::with('images')->findOrFail($id);
        return $post;
    }

    public function update(PostsRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $this->postData($post, $request);
        $post->save();

        $images = $request->images();

        //post the data on location table
        $post->location()->update($request->only([
            'address', 'city', 'country', 'state', 'postal_code',
        ]));

        //images
        $post_image = array();
        foreach($request->images as $image)
        {
            if(!$images->contains('image', $image))
            {
                $post_image[] = $this->postImage($image, $post->id, $request->name);
            }
        }
        return response()->json([$post,$post_image]);
    }

    public function destroy($id)
    {
        //
    }
}
