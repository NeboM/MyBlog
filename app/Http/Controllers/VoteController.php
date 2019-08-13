<?php

namespace App\Http\Controllers;

use App\Vote;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    // Down_vote   OR   up_vote  BUT not both

    /*
     * @return Vote
     */
    private static function getVote(/* Post id */int $id){
        return Vote::where('user_id','=',Auth::id())
            ->where('post_id','=',$id)
            ->first();
    }
    private static function deleteVote(/* Vote id */int $id) : void{
        Vote::find($id)->delete();
    }
    /*
     * @return Vote
     */
    private static function createNewVote(int $id,int $vote){
        $innerVote = new Vote();
        $innerVote->user_id = Auth::id();
        $innerVote->post_id = $id;
        $innerVote->vote = $vote;
        $innerVote->save();
        return $innerVote;
    }

    public function upvote(/* Post id */$id){

        $vote = self::getVote($id);

        // If the user already voted
        if(!empty($vote)){
            // And if it is downvote
            if($vote->vote == 0){
                // Make it upvote
                $vote->vote = 1;
                $vote->save();
                // 2 --> add one to upvote and take one from downvote
                return response()->json(2);
            }else{
                // Else delete the Vote
                self::deleteVote($vote->id);
                // -1 --> take one from upvote
                return response()->json(-1);

            }
        }else{
            // Else the user votes for first time --> create new vote
            $vote = self::createNewVote($id,1);
            // 1 --> add one to upvote
            return response()->json(1);
        }



    }

    public function downvote(/* Post id */$id){

        $vote = self::getVote($id);


        // If the user already voted
        if(!empty($vote)){
            // And if it is upvote
            if($vote->vote != 0){
                // Make it downvote
                $vote->vote = 0;
                $vote->save();
                // 2 --> add one to downvote and take one from upvote
                return response()->json(2);
            }else{
                // Else delete the Vote
                self::deleteVote($vote->id);
                // Take one from downvote
                return response()->json(-1);

            }
        }else{
            // Else the user votes for first time --> create new vote
            $vote = self::createNewVote($id,0);
            // 1 --> add one to downvote
            return response()->json(1);
        }



    }



}
