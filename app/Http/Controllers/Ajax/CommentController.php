<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class CommentController extends Controller
{
    /**
     * Handle listing comments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {
            $request->validate([
                'idEvent' => ['required', 'integer', 'gt:0', 'exists:event,id_event'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response('The given data was invalid.', 400);
        }

        $idEvent = (int)$request->idEvent;

        $comments = DB::table('comment')
            ->join('user', 'comment.id_user', '=', 'user.id_user')
            ->where('id_event', $idEvent)
            ->orderBy('comment.created_datetime', 'desc')
            ->select('comment.*', 'user.name as user_name', 'user.surname as user_surname')
            ->simplePaginate(config('ticketi.pagination'));

        return view('shared.comments', ['comments' => $comments]);
    }

    /**
     * Handle adding comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'idEvent' => ['required', 'integer', 'gt:0', 'exists:event,id_event'],
                'comment' => ['required', 'string', 'max:200'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response('The given data was invalid.', 400);
        }

        $idEvent = (int)$request->idEvent;
        $throttleKey = 'addComment:' . $idEvent . '|' . $request->user()->id_user;

        if (RateLimiter::tooManyAttempts($throttleKey, 2)) {
            return response('Rate limit exceeded.', 429);
        }

        RateLimiter::hit($throttleKey);

        $user = Auth::user();
        $comment = $request->comment;

        $idComment = DB::table('comment')
            ->insertGetId([
                "content" => $comment,
                "id_user" => $user->id_user,
                "id_event" => $idEvent,
            ]);

        $comments = DB::table('comment')
            ->join('user', 'comment.id_user', '=', 'user.id_user')
            ->where('id_comment', $idComment)
            ->select('comment.*', 'user.name as user_name', 'user.surname as user_surname')
            ->get();

        return response()->view('shared.comment', ['comment' => $comments->first()])->setStatusCode(201);
    }

    /**
     * Handle delete comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        try {
            $request->validate([
                'idComment' => ['required', 'integer', 'gt:0', 'exists:comment,id_comment'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response('The given data was invalid.', 400);
        }

        $idComment = (int)$request->idComment;

        DB::table('comment')
            ->where('id_comment', $idComment)
            ->delete();

        return response('', 200);
    }

    /**
     * Handle like comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request)
    {
        try {
            $request->validate([
                'idComment' => ['required', 'integer', 'gt:0', 'exists:comment,id_comment'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response('The given data was invalid.', 400);
        }

        $idComment = (int)$request->idComment;
        $throttleKey = 'likeComment:' . $idComment . '|' . optional($request->user())->id_user ?: $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            return response('Rate limit exceeded', 429);
        }

        RateLimiter::hit($throttleKey);

        $query = DB::table('comment')->where('id_comment', $idComment);

        $query->increment('likes_count');

        $count = ($query->get())->first()->likes_count;

        return response($count, 200);
    }
}
