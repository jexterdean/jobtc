<?php


Route::resource('session', 'SessionController');
Route::get('/', 'SessionController@create');
Route::get('/login', 'SessionController@create');

Route::group(['middleware' => 'guest'], function () {
    Route::get('forgotPassword', function () {
        return view('session.forgotPassword');
    });
    Route::post('forgotPassword', 'ProfileController@forgotPassword');
});

Route::group(['middleware' => 'auth'], function () {

    if (Entrust::hasRole('Admin')) {
        Route::get('/billing/{billing_type}', ['uses' => 'BillingController@index'])
            ->where('billing_type', 'invoice|estimate');
        Route::get('/billing/{billing_type}/{billing_id}', ['uses' => 'BillingController@show'])
            ->where('billing_type', 'invoice|estimate');
        Route::get('/billing/{billing_type}/{billing_id}/edit', ['uses' => 'BillingController@edit'])
            ->where('billing_type', 'invoice|estimate');
        Route::get('/print/{billing_type}/{billing_id}', ['uses' => 'BillingController@printing'])
            ->where('billing_type', 'invoice|estimate');
        Route::resource('billing', 'BillingController');
        Route::resource('setting', 'SettingController');
        Route::resource('template', 'TemplateController');
        Route::resource('item', 'ItemController');
        Route::resource('payment', 'PaymentController');
        Route::resource('user', 'UserController');
        Route::resource('client', 'ClientController');
        Route::resource('assigneduser', 'AssignedController');
    }

    if (Entrust::hasRole('Client')) {
        Route::get('/billing/{billing_type}', ['uses' => 'BillingController@index'])
            ->where('billing_type', 'invoice|estimate');
        Route::get('/print/{billing_type}/{billing_id}', ['uses' => 'BillingController@printing'])
            ->where('billing_type', 'invoice|estimate');
    }

    if (!Entrust::hasRole('Client')) {
        Route::resource('task', 'TaskController');
    }

    Route::resource('event', 'EventsController');
    Route::resource('project', 'ProjectController');
    Route::resource('bug', 'BugController');
    Route::resource('note', 'NoteController');
    Route::resource('comment', 'CommentController');
    Route::resource('attachment', 'AttachmentController');
    Route::resource('message', 'MessageController');
    Route::resource('ticket', 'TicketController');
    Route::post('startTimer', 'ProjectController@startTimer');
    Route::post('endTimer', 'ProjectController@endTimer');
    Route::post('updateTaskStatus', 'TaskController@updateTaskStatus');
    Route::post('updateProgress', 'ProjectController@updateProgress');
    Route::post('updateBugStatus', 'BugController@updateBugStatus');
    Route::post('updateTicketStatus', 'TicketController@updateTicketStatus');
    Route::post('changePassword', 'ProfileController@changePassword');
    Route::post('updateProfile', 'ProfileController@updateProfile');
    Route::post('deleteTimer', 'ProjectController@deleteTimer');
    Route::get('logout', 'SessionController@destroy');
    Route::get('dashboard', 'DashboardController@index');
    Route::get('user/{user_id}/delete', 'UserController@delete');
    Route::get('event/{event_id}/delete', 'EventsController@delete');
    Route::get('client/{client_id}/delete', 'ClientController@delete');
    Route::get('billing/{billing_id}/delete', 'BillingController@delete');
    Route::get('project/{project_id}/delete', 'ProjectController@delete');
    Route::get('bug/{bug_id}/delete', 'BugController@delete');
    Route::get('ticket/{ticket_id}/delete', 'TicketController@delete');
    Route::get('profile', function () {
        return View::make('user.profile',['assets'=> []]);
    });
    Route::get('docs', function () {
        return View::make('docs.docs',['assets'=> []]);
    });
    Route::get('about', function () {
        return View::make('about.about',['assets'=>[]]);
    });


});

