<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getComments($id)
    {
        $comments = DB::table('comments')->where('post_id','=',$id)
            ->join('users','users.id','=','comments.user_id')
            ->select('users.name','users.email','users.avatar','comments.*')->orderBy('comments.created_at','DESC')->get();

        return response()->json($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {

        $request->validate([
            'body' => 'bail|string|required'
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->post_id = $id;
        $comment->body = $request->body;
        $comment->save();

        $newComment = DB::table('comments')->where('comments.id','=',$comment->id)
            ->join('users','users.id','=','comments.user_id')
            ->select('users.name','users.avatar','users.email','comments.*')->first();


        return response()->json($newComment);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'body' => 'bail|string|required'
        ]);
        $comment->body = $request->body;
        $comment->save();

        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if(Auth::id() === $comment->user_id){
            try{
                $id = $comment->id;
                $comment->delete();
            }catch(\Exception $e){
                abort(400);
            }

        }else{
            abort(401);
        }

        return response()->json($id);
    }
}
