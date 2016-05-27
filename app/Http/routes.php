<?php


//Route::resource('session', 'SessionController');
//Route::get('/', 'SessionController@create');
//Route::get('/login', 'SessionController@create');
Route::get('/',['as' => 'home', 'uses' => 'SessionController@authorizeUsersAndApplicants','https' => true]);
Route::get('/home', ['as' => 'home', 'uses' => 'SessionController@authorizeUsersAndApplicants','https' => true]);

//Route::get('/login', 'SessionController@login');
/* Authentication routes */
Route::get('login', function() {
    return view('session.create');
});

Route::post('/login', 'SessionController@login');


/*Job Routes*/
//Should not be in any middleware so that 
//job posting can be accessed by would be applicants(they need to view the job posting without logging in)
Route::resource('job', 'JobController');
Route::post('updateJob/{id}', 'JobController@update');

Route::get('applyToJobForm', 'JobController@getApplyToJobForm');
Route::post('applyToJob', 'JobController@applyToJob');

/*For Applicant*/
Route::resource('a', 'ApplicantController');
Route::get('a/{id}',['as' => 'a', 'uses' => 'ApplicantController@show','https' => true]);


/* For Applicant Tags */
Route::post('addTag', 'JobController@addTag');
Route::get('getAvailableTags', 'JobController@getTags');

/*For Comments*/
Route::post('addComment','CommentController@addComment');

/* For Video Status*/
//Route::post('/add-video-status', 'ShowController@addVideoStatus');
//Route::get('/get-available-video-tags', 'ShowController@getVideoTags');

Route::group(['middleware' => 'guest'], function () {

    Route::get('forgotPassword', function () {
        return view('session.forgotPassword');
    });
    Route::post('forgotPassword', 'ProfileController@forgotPassword');
});


Route::group(['middleware' => 'auth'], function () {

    /**
     * Links
     */
    Route::resource('links','LinkController');
    Route::resource('linkCategory','LinkCategoryController');

    /**
     *  Client
     */
    Route::group(['middleware' => 'role:client'], function () {
        Route::get('/billing/{billing_type}', ['uses' => 'BillingController@index'])
            ->where('billing_type', 'invoice|estimate');
        Route::get('/print/{billing_type}/{billing_id}', ['uses' => 'BillingController@printing'])
            ->where('billing_type', 'invoice|estimate');
    });

    /**
     * Admin only
     */
    Route::group(['middleware'=> 'role:admin'], function(){
        Route::get('/billing/{billing_type}', ['uses' => 'BillingController@index'])
            ->where('billing_type', 'invoice|estimate');
        Route::get('/billing/{billing_type}/{billing_id}', ['uses' => 'BillingController@show'])
            ->where('billing_type', 'invoice|estimate');
        Route::get('/billing/{billing_type}/{billing_id}/edit', ['uses' => 'BillingController@edit'])
            ->where('billing_type', 'invoice|estimate');
        Route::get('/print/{billing_type}/{billing_id}', ['uses' => 'BillingController@printing'])
            ->where('billing_type', 'invoice|estimate');
        Route::resource('task', 'TaskController');
        Route::resource('billing', 'BillingController');
        Route::resource('setting', 'SettingController');
        Route::resource('template', 'TemplateController');
        Route::resource('item', 'ItemController');
        Route::resource('payment', 'PaymentController');
        Route::resource('user', 'UserController');
        Route::resource('company', 'CompanyController');
        Route::resource('applicant', 'ApplicantController');
        Route::resource('assigneduser', 'AssignedController');
        
        /*For Assigning teams for each project with a team(Auto generated team)*/
        Route::any('createTeam','CompanyController@createTeam');
        /*Unassigning Team members from a project*/
        Route::any('unassignTeamMember','CompanyController@unassignTeamMember');
        
        /*For assigning employees with tasks from the tasklist of a given project*/
        Route::any('assignTaskList','CompanyController@assignTaskList');
        Route::any('unassignTaskList','CompanyController@unassignTaskList');
        
        /*For assigning tests to applicants*/
        Route::any('assignTestToJob','CompanyController@assignTestToJob');
        Route::any('unassignTestFromJob','CompanyController@unassignTestFromJob');
        
        /*For assigning tests to jobs*/
        Route::any('assignTestToApplicant','CompanyController@assignTestToApplicant');
        Route::any('unassignTestFromApplicant','CompanyController@unassignTestFromApplicant');
        
        /*For Getting the tasklist when you're dropping an employee to a project*/
        Route::any('getTaskList','CompanyController@getTaskList');
        
        //For CkEditor Image file upload
        Route::any('saveImage&responseType=json','TaskController@saveImage');
    });

    /**
     * Staff
     */

    /**
     * CSS Reference
     */
    Route::resource('css', 'CssController');

    /**
     * Task List
     */
    Route::resource('task', 'TaskController');
    Route::any('task/delete/{id}', 'TaskController@delete');
    Route::post('taskTimer/{id}', 'TaskController@taskTimer');
    Route::post('updateTaskTimer/{id}', 'TaskController@updateTaskTimer');
    Route::any('deleteTaskTimer/{id}', 'TaskController@deleteTaskTimer');
    Route::post('checkList', 'TaskController@checkList');
    Route::post('updateCheckList/{id}', 'TaskController@updateCheckList');
    Route::any('deleteCheckList/{id}', 'TaskController@deleteCheckList');
    Route::post('sortCheckList/{id}','TaskController@sortCheckList');
    Route::post('changeCheckList/{task_id}/{task_list_item_id}','TaskController@changeCheckList');
    
    Route::get('/data/{cacheKey}','CacheDataController@getCache');
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
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index','https' => true] );
    Route::get('user/{user_id}/delete', 'UserController@delete');
    Route::get('event/{event_id}/delete', 'EventsController@delete');
    Route::get('company/{company_id}/delete', 'CompanyController@delete');
    Route::get('billing/{billing_id}/delete', 'BillingController@delete');
    Route::get('project/{project_id}/delete', 'ProjectController@delete');
    Route::get('bug/{bug_id}/delete', 'BugController@delete');
    Route::get('ticket/{ticket_id}/delete', 'TicketController@delete');
    Route::get('profile', function () {
        return View::make('user.profile', ['assets' => []]);
    });
    Route::get('docs', function () {
        return View::make('docs.docs', ['assets' => []]);
    });
    Route::get('about', function () {
        return View::make('about.about', ['assets' => []]);
    });

    /*
     * Add Meeting
    */
    Route::resource('meeting', 'MeetingController');
    Route::get('meetingJson', 'MeetingController@meetingJson');
    Route::get('meetingTimezone', 'MeetingController@meetingTimezone');

    /*
     * Team Builder
    */
    Route::resource('teamBuilder', 'TeamBuilderController');
    Route::get('teamBuilderJson', 'TeamBuilderController@teamBuilderJson');
    Route::get('teamBuilderUserJson', 'TeamBuilderController@teamBuilderUserJson');
    Route::get('teamBuilderExistingUserJson', 'TeamBuilderController@teamBuilderExistingUserJson');

    /*
     * Payroll
    */
    Route::resource('payroll', 'PayrollController');
    Route::get('payrollJson', 'PayrollController@payrollJson');

    /*
     * Quiz
    */
    Route::resource('quiz', 'QuizController');
    Route::post('testSort','QuizController@testSort');
    Route::post('questionSort','QuizController@questionSort');
});

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'v1'], function () {
    });
});
/*
* New Note
*/
Route::resource('newnote', 'NewNoteController');

