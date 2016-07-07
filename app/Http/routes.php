<?php

//Ckeditor fix temp route
Route::get('/editor', 'CKEditorController@index');

/* Authentication routes */
Route::get('/', ['as' => 'home', 'uses' => 'SessionController@authorizeUsersAndApplicants', 'https' => true]);
Route::get('/home', ['as' => 'home', 'uses' => 'SessionController@authorizeUsersAndApplicants', 'https' => true]);

Route::get('login', function() {
    return view('session.create');
});

//Don't put this into middleware as well since it will not let the applicant logout
//(applicant table doesn't use the role manager for the User Model)
Route::post('/login', 'SessionController@login');
Route::get('logout', 'SessionController@destroy');

/* Job Routes */
//Should not be in any middleware so that 
//job posting can be accessed by would be applicants(they need to view the job posting without logging in)
Route::resource('job', 'JobController');
Route::post('updateJob/{id}', 'JobController@update');
Route::get('addJobFormCompany', 'JobController@addJobFormCompany');
Route::post('addJobCompany', 'JobController@addJobCompany');

Route::get('applyToJobForm', 'JobController@getApplyToJobForm');
Route::post('applyToJob', 'JobController@applyToJob');
Route::post('saveJobNotes', 'JobController@saveJobNotes');
/* Check for duplicate emails upon Applying to a Job */
Route::post('checkApplicantDuplicateEmail', 'JobController@checkApplicantDuplicateEmail');


/* For Registration */
Route::get('register', 'UserController@getRegisterForm');
Route::post('register', 'UserController@register');

/* For Applicant */
Route::resource('a', 'ApplicantController');
Route::get('a/{id}', ['as' => 'a', 'uses' => 'ApplicantController@show', 'https' => true]);
Route::post('saveApplicantNotes', 'ApplicantController@saveApplicantNotes');
Route::post('getApplicantQuizResults', 'ApplicantController@getApplicantQuizResults');


/* Add or Remove Applicant from the User Table */
Route::post('hireApplicant', 'ApplicantController@hireApplicant');
Route::post('fireApplicant', 'ApplicantController@fireApplicant');

/* For Applicant Tags */
Route::post('addTag', 'JobController@addTag');
Route::get('getAvailableTags', 'JobController@getTags');

/* For Comments */
Route::post('addComment', 'CommentController@addComment');

/* For Organizational Chart */
Route::get('getChartData/{id}', 'CompanyController@getChartData');

/* For Assigning User Roles */
Route::post('updateRole', 'CompanyController@updateRole');

/* For Video */
Route::post('saveVideo', 'VideoController@saveVideo');
Route::post('deleteVideo', 'VideoController@deleteVideo');

/* For Video Status */
Route::post('/addVideoTag', 'VideoController@addVideoTag');
Route::get('/getAvailableVideoTags', 'VideoController@getVideoTags');

/* For Tags */
Route::post('addNewTag', 'TagController@addTag');
Route::get('getTags/{id}/{tag_type}', 'TagController@getTags');
/*
 * Quiz
 */
Route::resource('quiz', 'QuizController');
Route::any('testSort', 'QuizController@testSort');
Route::post('questionSort', 'QuizController@questionSort');
Route::get('userSlider/{id}', 'QuizController@userSlider');
Route::get('quizRanking/{id}', 'QuizController@quizRanking');
Route::post('quizAddPersonalCommunity', 'QuizController@quizAddPersonalCommunity');
Route::post('quizSearch', 'QuizController@quizSearch');
Route::any('quizElasticSearchView', 'QuizController@quizElasticSearchView');
/*
 * Indeed Applicant Importer (Don't put this in any middleware, 
 * the script should not login to insert the data from Indeed
 * */

Route::post('addJobFromCrawler', 'CrawlerController@addJobFromCrawler');
Route::post('addApplicantFromCrawler', 'CrawlerController@addApplicantFromCrawler');

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
    Route::resource('links', 'LinkController');
    Route::resource('linkCategory', 'LinkCategoryController');


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
    Route::resource('user.company', 'UserController');
    Route::resource('company', 'CompanyController');
    Route::resource('applicant', 'ApplicantController');
    Route::resource('assigneduser', 'AssignedController');

    /* For Assigning teams for each project with a team(Auto generated team) */
    Route::any('createTeam', 'CompanyController@createTeam');
    /* Unassigning Team members from a project */
    Route::any('unassignTeamMember', 'CompanyController@unassignTeamMember');

    /* For Assigning Companies to a team */
    Route::post('assignCompanyToTeam', 'CompanyController@assignCompanyToTeam');
    Route::post('unassignCompanyFromTeam', 'CompanyController@unassignCompanyFromTeam');

    /* Sharing Jobs to a User */
    Route::post('shareJobToUser', 'CompanyController@shareJobToUser');
    Route::post('unshareJobFromUser', 'CompanyController@unshareJobFromUser');

    /* Sharing Jobs to a Company */
    Route::post('shareJobToCompany', 'CompanyController@shareJobToCompany');
    Route::post('unshareJobFromCompany', 'CompanyController@unshareJobFromCompany');

    /* For assigning employees with tasks from the tasklist of a given project */
    Route::any('assignTaskList', 'CompanyController@assignTaskList');
    Route::any('unassignTaskList', 'CompanyController@unassignTaskList');

    /* For assigning tests to applicants */
    Route::any('assignTestToJob', 'CompanyController@assignTestToJob');
    Route::any('unassignTestFromJob', 'CompanyController@unassignTestFromJob');

    /* For assigning tests to jobs */
    Route::any('assignTestToApplicant', 'CompanyController@assignTestToApplicant');
    Route::any('unassignTestFromApplicant', 'CompanyController@unassignTestFromApplicant');

    /* For sharing jobs with employees per company */
    Route::any('shareToCompanyEmployee', 'CompanyController@shareToCompanyEmployee');
    Route::any('unshareFromCompanyEmployee', 'CompanyController@unshareFromCompanyEmployee');

    /* For Getting the tasklist when you're dropping an employee to a project */
    Route::any('getTaskList', 'CompanyController@getTaskList');

    /* Saving a Spreadsheet type task */
    Route::any('saveSpreadsheet', 'TaskController@saveSpreadsheet');

    //For CkEditor Image file upload
    Route::any('saveImage&responseType=json', 'TaskController@saveImage');

    Route::get('company/{id}', ['as' => 'company', 'uses' => 'CompanyController@show', 'https' => true]);

    Route::get('getCompanyProjects/{id}', 'CompanyController@getCompanyProjects');

    /* For Company Load on Demand */
    Route::get('getJobsTab/{id}', 'CompanyController@getJobsTab');
    Route::get('getEmployeesTab/{id}', 'CompanyController@getEmployeesTab');
    Route::get('getPositionsTab/{id}', 'CompanyController@getPositionsTab');
    Route::get('getAssignTab/{id}', 'CompanyController@getAssignTab');
    Route::get('getAssignProjectsTab/{id}', 'CompanyController@getAssignProjectsTab');
    Route::get('getAssignTestsTab/{id}', 'CompanyController@getAssignTestsTab');
    Route::get('getAssignAuthorityLevelsTab/{id}', 'CompanyController@getAssignAuthorityLevelsTab');
    Route::get('getShareJobsTab/{id}', 'CompanyController@getShareJobsTab');
    /* For Projects Load on Demand */
    Route::get('getSubprojects/{project_id}/{company_id}', 'CompanyController@getSubprojects');
    Route::get('getSubprojectsForCompanyEmployee/{user_id}/{project_id}/{company_id}', 'CompanyController@getSubprojectsForCompanyEmployee');
    /* For Share Companies Load on Demand */
    Route::get('getEmployees/{company_id}/{job_id}', 'CompanyController@getEmployees');
    /* For Assign Projects Load on Demand */
    Route::get('getCompanyEmployeesForProject/{project_id}/{company_id}', 'CompanyController@getCompanyEmployeesForProject');


    /**
     * CSS Reference
     */
    Route::resource('css', 'CssController');

    /**
     * Project
     */
    Route::resource('project', 'ProjectController');
    Route::get('addProjectForm', 'ProjectController@addProjectForm');
    Route::post('addProject', 'ProjectController@addProject');

    /*
     * Employees
     * */
    Route::get('employees/{id}','UserController@getEmployees');
    Route::get('addEmployeeForm', 'UserController@addEmployeeForm');
    Route::get('editEmployeeForm/{id}', 'UserController@editEmployeeForm');
    Route::get('editEmployeePermissionsForm/{id}', 'UserController@editEmployeePermissionsForm');
    Route::post('editEmployeePermissions', 'UserController@editEmployeePermissions');
    Route::post('addEmployee', 'UserController@addEmployee');
    Route::post('editEmployee', 'UserController@editEmployee');
    Route::post('removeEmployeeFromCompany', 'UserController@removeEmployeeFromCompany');
    Route::post('saveEmployeeNotes', 'UserController@saveEmployeeNotes');
    
        
    
    /*
     * Positions
     */
    Route::resource('positions', 'RoleController');
    Route::get('addPositionForm', 'RoleController@addPositionForm');
    Route::post('addPosition', 'RoleController@addPosition');
    Route::get('editPositionForm/{id}', 'RoleController@editPositionForm');
    Route::post('editPosition', 'RoleController@editPosition');
    Route::post('deletePosition', 'RoleController@deletePosition');
    Route::post('assignPositionPermission','RoleController@assignPositionPermission');
    Route::post('unassignPositionPermission','RoleController@unassignPositionPermission');
    Route::post('assignEmployeePermission','RoleController@assignEmployeePermission');
    Route::post('unassignEmployeePermission','RoleController@unassignEmployeePermission');
    
    /* 
     * Assigning 
     **/
    Route::get('assignProjects/{id}','AssignController@assignProjects');
    Route::get('assignTests/{id}','AssignController@assignTests');
    Route::get('assignAuthorityLevels/{id}','AssignController@assignAuthorityLevels');
    Route::get('assignJobs/{id}','AssignController@assignJobs');
    
    /**
     * Task List
     */
    Route::resource('task', 'TaskController');

    /* Add Briefcase(a briefcase is a Subproject) */
    Route::get('addBriefcaseForm', 'TaskController@addBriefcaseForm');
    Route::post('addBriefcase', 'TaskController@addBriefcase');

    Route::any('task/delete/{id}', 'TaskController@delete');
    Route::post('taskTimer/{id}', 'TaskController@taskTimer');
    Route::post('updateTaskTimer/{id}', 'TaskController@updateTaskTimer');
    Route::any('deleteTaskTimer/{id}', 'TaskController@deleteTaskTimer');
    Route::post('checkList', 'TaskController@checkList');
    Route::post('updateCheckList/{id}', 'TaskController@updateCheckList');
    Route::any('deleteCheckList/{id}', 'TaskController@deleteCheckList');
    Route::post('sortCheckList/{id}', 'TaskController@sortCheckList');
    Route::post('changeCheckList/{task_id}/{task_list_item_id}', 'TaskController@changeCheckList');
    Route::post('addNewTask', 'TaskController@addNewTask');
    Route::post('saveTaskCheckListHeader', 'TaskController@saveTaskCheckListHeader');
    Route::post('saveTaskCheckList', 'TaskController@saveTaskCheckList');
    Route::post('cancelAddNewTask', 'TaskController@cancelAddNewTask');
    Route::post('getTaskChecklistItem', 'TaskController@getTaskChecklistItem');
    Route::post('autoSaveEditChecklist', 'TaskController@autoSaveEditChecklist');

    Route::get('/data/{cacheKey}', 'CacheDataController@getCache');
    Route::resource('event', 'EventsController');





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
    Route::post('checkPassword', 'ProfileController@checkPassword');
    Route::post('updateProfile', 'ProfileController@updateProfile');
    Route::post('updateMyProfile', 'ProfileController@updateMyProfile');
    Route::post('deleteTimer', 'ProjectController@deleteTimer');

    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index', 'https' => true]);
    Route::get('user/{user_id}/delete', 'UserController@delete');
    Route::get('event/{event_id}/delete', 'EventsController@delete');
    Route::get('company/{company_id}/delete', 'CompanyController@delete');
    Route::get('billing/{billing_id}/delete', 'BillingController@delete');
    Route::post('deleteProject', 'ProjectController@delete');
    Route::get('bug/{bug_id}/delete', 'BugController@delete');
    Route::get('ticket/{ticket_id}/delete', 'TicketController@delete');
    /* Route::get('profile', function () {
      return View::make('user.profile', ['assets' => ['profiles']]);
      }); */
    Route::get('profile', 'ProfileController@index');
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
});

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'v1'], function () {
        
    });
});
/*
 * New Note
 */
Route::resource('newnote', 'NewNoteController');

