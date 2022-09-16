<?php

namespace App\Http\Controllers ;

use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Support\Facades\Gate ;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\MessageBag ;
use App\Post ;
use App\User ;
use App\Rules\CategoryState ;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // User Type Access Level
        if(Gate::allows('isAdmin')){
            // Query To Database
            $posts = Post::with('user')->get() ;
        }else{
            // Query To Database
            $posts = Post::where('user_id' , Auth::user()->id)->with('user')->get() ;
        }
        // Return View
        return view('admin.posts' , compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Find The Current User
        $currentUser = Auth::user() ;
        // Return View
        return view('admin.createPost' , compact('currentUser'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $vaildator = Validator::make($request->all()  , [
            'title' =>  ['required','string','min:10' , 'max:255'] ,
            'body' =>   ['required' ,'string','min:10','max:1000'] ,
            'category' => ['required' , new CategoryState] ,
            'user_id' =>  ['required' ,'numeric']
        ]);
        // Check Fails Validator
        if($vaildator->fails()) {
            return redirect()->back()->withErrors($vaildator) ;
        }
        // Store New Post
        Post::create($request->all() ) ;
        // Success Massage
        $successMsg = [ 'successMsg' => 'Create Post successfully.' ] ;
        // Return Redirect View
        return redirect()->route('post.index')->withErrors($successMsg) ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // Return View
        return view('admin.editPost' , compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Post $post)
    {
        // Validation
        $vaildator = Validator::make($request->all()  , [
            'title' =>  ['required' ,'string','min:10','max:255'] ,
            'body' =>   ['required' ,'string','min:10','max:1000'] ,
            'category' => ['required' , new CategoryState] ,
        ]);
        // Check Fails Validator
        if($vaildator->fails()) {
            return redirect()->back()->withErrors($vaildator) ;
        }
        // Update Post
        $post->update($request->all()) ;
        // Success Massage
        $successMsg = [ 'successMsg' => 'Update Post successfully.' ] ;
        // Return Redirect View
        return redirect()->route('post.index')->withErrors($successMsg) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // Delete The Specified Post
        $post->delete() ;
        // Success Massage
        $successMsg = [ 'successMsg' => 'Delete Post successfully.' ] ;
        // Return Redirect View
        return redirect()->back()->withErrors($successMsg) ;
    }
}
