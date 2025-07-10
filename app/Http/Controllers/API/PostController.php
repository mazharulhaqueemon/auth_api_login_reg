<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\API\BaseController as BaseController;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts']= Post::all();
        return $this->sendResponse($data, 'All posts data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {


        $img = $request->image;
        $imageName = time().'_'.$img->getClientOriginalName();
        $path= $img->storeAs('images', $imageName, 'public');



        $post = Post::create([
             'title' => $request->title,
             'description' => $request->description,
             'image'=>$imageName,
         ]);


        return $this->sendResponse($post, 'Post created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post']= Post::select(
            'id',
            'title',
            'description',
            'image',

        )->where(['id' => $id])->get();

    return $this->sendResponse($data, 'Post retrieved successfully');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
         $post = Post::findOrFail($id);

          if($request->hasFile('image')){
            // previous image or file delete
            $image_path = public_path('storage/images/'.$post->image);
            if(file_exists($image_path)){
                unlink($image_path);
            }
            $img = $request->image;
            $imageName = time().'_'.$img->getClientOriginalName();
            $path= $img->storeAs('images', $imageName, 'public');

          }
          else{
            $imageName=$post->image;
          }

        $post->update([
             'title' => $request->title,
             'description' => $request->description,
             'image'=>$imageName,
         ]);

        return $this->sendResponse($post, 'Post updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $post = Post::findOrFail($id);

        $image_path = public_path('storage/images/'.$post->image);
        if(file_exists($image_path)){
            unlink($image_path);
        }

        $post->delete();

        return $this->sendResponse([], 'Post deleted successfully');
    }
}
