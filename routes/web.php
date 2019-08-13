<?php

Auth::routes();
// Custom route for login to return available emails for login
Route::get('/login','PagesController@login')->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'PagesController@home')->name('pages.home');

    Route::get('/profile/{id}', 'PagesController@profile')->name('pages.profile');
    Route::post('/profile', 'UserController@update_avatar');

    Route::post('/comment/{id}', 'CommentController@store')->name('comments.store');
    Route::put('/comment/{comment}', 'CommentController@update')->name('comments.update');
    Route::delete('/comment/{comment}', 'CommentController@destroy')->name('comments.destroy');
    // /comments/{id} <----- Post id  ---> get comments for that post in JSON
    Route::get('/comments/{id}', 'CommentController@getComments')->name('comments.get');

    Route::put('/upvote/{id}', 'VoteController@upvote')->name('votes.upvote');
    Route::put('/downvote/{id}', 'VoteController@downvote')->name('votes.downvote');

    Route::resource('posts', 'PostController');
    Route::get('/get/posts', 'PostController@getPosts')->name('posts.get');
    Route::get('/get/posts/{id}', 'PostController@getPostsForUser')->name('posts.user');

    Route::get('/home', 'HomeController@index')->name('home');
});
