<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.posts');
    }

    // Get the posts in JSON
    public function getPosts(){
        // Charming query
        $posts = DB::select(
          'SELECT 
                 	posts.*,
                    users.name,
                    users.email,
                    users.avatar,
                    comments.comments,
                    votes.upvotes,
                    votes.downvotes,
                    auth.vote
                 FROM
                 	posts
                 INNER JOIN 
                 	users
                 ON
                    posts.user_id = users.id
                 LEFT JOIN 
                 (
                 	SELECT post_id,COUNT(comments.id) as comments FROM comments GROUP BY post_id
                 ) AS comments ON posts.id = comments.post_id
                 LEFT JOIN
                 (
                 	SELECT post_id,COUNT(CASE 
                                 	WHEN votes.vote = 1 THEN 1 ELSE NULL
                        	   	    END
                    			)as upvotes,
                     	   COUNT(CASE 
                                 	WHEN votes.vote = 0 THEN 1 ELSE NULL
                        	   	    END
                    			)as downvotes
                     FROM votes GROUP BY post_id
                 ) AS votes ON posts.id = votes.post_id
                 LEFT JOIN 
                 (
                 	SELECT post_id,vote FROM votes WHERE user_id = :user_id    
                 ) as auth ON posts.id = auth.post_id
                 ORDER BY posts.id DESC',['user_id' => Auth::id()]);

        $collect = collect($posts);

        // Get the page number
        $page = $_GET['page'];
        if(!is_int($page) && $page < 1){
            abort(400);
        }

        // Posts per page
        $size =6;

        // Custom pagination
        $paginationData = new LengthAwarePaginator(
            $collect->forPage($page, $size),
            $collect->count(),
            $size,
            $page
        );

        return response()->json($paginationData);
    }


    public function getPostsForUser(int $id){

        $user = User::findOrFail($id);
        $posts = DB::select(
            "SELECT 
                 	posts.*,
                    users.name,
                    users.email,
                    users.avatar,
                    comments.comments,
                    votes.upvotes,
                    votes.downvotes,
                    auth.vote
                 FROM
                 	posts
                 INNER JOIN 
                 	users
                 ON
                    posts.user_id = users.id
                 LEFT JOIN 
                 (
                 	SELECT post_id,COUNT(comments.id) as comments FROM comments GROUP BY post_id
                 ) AS comments ON posts.id = comments.post_id
                 LEFT JOIN
                 (
                 	SELECT post_id,COUNT(CASE 
                                 	WHEN votes.vote = 1 THEN 1 ELSE NULL
                        	   	    END
                    			)as upvotes,
                     	   COUNT(CASE 
                                 	WHEN votes.vote = 0 THEN 1 ELSE NULL
                        	   	    END
                    			)as downvotes
                     FROM votes GROUP BY post_id
                 ) AS votes ON posts.id = votes.post_id
                 LEFT JOIN 
                 (
                 	SELECT post_id,vote FROM votes WHERE user_id = :user_id
                 ) as auth ON posts.id = auth.post_id
                 WHERE posts.user_id = :id
                 ORDER BY posts.id DESC",['id' => $user->id, 'user_id' => Auth::id()]);

        $collect = collect($posts);

        // Get the page number
        $page = $_GET['page'];
        if(!is_int($page) && $page < 1){
            abort(400);
        }

        // Posts per page
        $size =6;

        // Custom pagination
        $paginationData = new LengthAwarePaginator(
            $collect->forPage($page, $size),
            $collect->count(),
            $size,
            $page
        );
        return response()->json($paginationData);
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('posts.create');
    }


    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail|required|max:191',
            'body' =>'bail|required|max:800'
        ]);
        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        return redirect('/posts');
    }

    // Display the specified resource.
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $posts = DB::select(
            "SELECT 
                 	posts.*,
                    users.name,
                    users.email,
                    users.avatar,
                    votes.upvotes,
                    votes.downvotes,
                    auth.vote
                 FROM
                 	posts
                 INNER JOIN 
                 	users
                 ON
                    posts.user_id = users.id
                 LEFT JOIN
                 (
                 	SELECT post_id,COUNT(CASE 
                                 	WHEN votes.vote = 1 THEN 1 ELSE NULL
                        	   	    END
                    			)as upvotes,
                     	   COUNT(CASE 
                                 	WHEN votes.vote = 0 THEN 1 ELSE NULL
                        	   	    END
                    			)as downvotes
                     FROM votes GROUP BY post_id
                 ) AS votes ON posts.id = votes.post_id
                 LEFT JOIN 
                 (
                 	SELECT post_id,vote FROM votes WHERE user_id = :user_id
                 ) as auth ON posts.id = auth.post_id
                 WHERE posts.id = :id1
                 LIMIT 1",['id1' => $post->id, 'user_id' => Auth::id()]);

        $collect = collect($posts);
        $collect = json_encode($collect[0]);
        return view('posts.post')->with('post',$collect);
    }

    // Show the form for editing the specified resource.
    public function edit(Post $post)
    {
        return view('posts.edit')->with('post',$post);
    }

    // Update post
    public function update(Request $request,Post $post)
    {
        $request->validate([
            'title' => 'bail|required|max:191',
            'body' =>'bail|required|max:800'
        ]);

        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        $posts = DB::table('posts')->where('posts.id','=',$post->id)
            ->join('users','users.id', '=', 'posts.user_id')
            ->select('users.name','users.email','users.avatar','posts.*')->orderBy('posts.id','DESC')
            ->get();

        return redirect('/posts/'.$post->id)->with('post',$posts);
    }

    // Delete post
    public function destroy(Post $post)
    {
        if(Auth::id() === $post->user_id){
            try{
                $post->delete();
            }catch (\Exception $e){
                abort(400,$e->getMessage());
            }
        }else{
            abort(401);
        }
        return redirect('/posts');
    }
}
