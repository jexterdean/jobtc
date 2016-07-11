<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Applicant;
use App\Models\ApplicantTag;
use App\Models\MailBox;
use App\Models\MailBoxAlias;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\ShareJob;
use Auth;
use Redirect;

class JobController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //Get logged in User
        $user_id = $request->user()->user_id;

        $users = User::find($user_id);

        $jobs = Job::paginate(3);

        $assets = ['jobs'];

        return view('jobs.index', ['name' => $users->name, 'user_id' => $user_id, 'jobs' => $jobs, 'assets' => $assets, 'count' => 0]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $user_id = Auth::user()->user_id;
        $company_id = $request->input('company_id');
        $title = $request->input('title');
        $description = $request->input('description');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('assets/job/', $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = '';
        }


        $job = new Job();

        $job->user_id = $user_id;
        $job->company_id = $company_id;
        $job->title = $title;
        $job->description = $description;
        $job->photo = $photo_path;
        $job->save();

        return Redirect::to('job/' . $job->id)->withSuccess('Job added successfully!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        if (Auth::check()) {
            $user_id = Auth::user('user')->user_id;

            $job = Job::with('applicants')->where('id', $id)->first();

            $applicants = $this->getApplicantsInfo($id);

            $user_profile_role_count = Profile::where('user_id', $user_id)
                    ->where('company_id', $job->company_id)
                    ->count();

            $is_shared = ShareJob::where('user_id', $user_id)
                    ->where('job_id', $job->id)
                    ->count();

            if ($user_profile_role_count > 0) {

                $user_profile_role = Profile::where('user_id', $user_id)
                        ->where('company_id', $job->company_id)
                        ->first();

                $permissions_list = [];

                $permissions_role = PermissionRole::with('permission')
                        ->where('company_id', $job->company_id)
                        ->where('role_id', $user_profile_role->role_id)
                        ->get();

                foreach ($permissions_role as $role) {
                    array_push($permissions_list, $role->permission_id);
                }

                $module_permissions = Permission::whereIn('id', $permissions_list)->get();
            }

            if ($user_profile_role_count === 0 && $is_shared === 0) {
                $module_permissions = Permission::where('slug', 'view.jobs')->get();
            }

            $assets = ['jobs', 'slider'];

            return view('jobs.show', [
                'job' => $job,
                'applicants' => $applicants,
                'module_permissions' => $module_permissions,
                'assets' => $assets,
                'count' => 0
            ]);
        } else {

            $job = Job::with('applicants')->where('id', $id)->first();

            $applicants = $this->getApplicantsInfo($id);

            $assets = ['jobs', 'slider'];

            return view('jobs.show', [
                'job' => $job,
                'applicants' => $applicants,
                'assets' => $assets,
                'count' => 0
            ]);
        }
    }
    private function getApplicantsInfo($id){
        //get test per Job and Applicants
        $test_jobs_id = \DB::table('test_per_job')
            ->where('job_id', $id)
            ->lists('test_id');
        $test_applicants_id = \DB::table('test_per_applicant')
            ->leftJoin('applicants', 'applicants.id', '=', 'test_per_applicant.applicant_id')
            ->where('job_id', $id)
            ->lists('test_id');
        $test_id = array_unique(array_merge($test_jobs_id, $test_applicants_id));

        $applicants = Applicant::with(['tags' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->select(\DB::raw('
                fp_applicants.*,
                IF(fp_test.default_tags != "", LOWER(fp_test.default_tags), "general") as default_tags,
                SUM(
                    IF(
                        fp_test_result.result = 1,
                        IF(fp_question.question_type_id = 3, fp_test_result.points, fp_question.points),
                        0
                    )
                ) as total_score,
                SUM(
                    IF(fp_question.question_type_id = 3, fp_question.max_point, fp_question.points)
                ) as max_points,
                (ROUND(
                    SUM(
                        IF(
                            fp_test_result.result = 1,
                            IF(fp_question.question_type_id = 3, fp_test_result.points, fp_question.points),
                            0
                        )
                    )/SUM(
                        IF(fp_question.question_type_id = 3, fp_question.max_point, fp_question.points)
                    ),
                    4
                ) * 100) as average
            '))
            ->leftJoin('test_result', function($join){
                $join->on('test_result.unique_id', '=', 'applicants.id');
            })
            ->leftJoin('test', 'test.id', '=', 'test_result.test_id')
            ->leftJoin('question', function($join){
                $join->on('question.id', '=', 'test_result.question_id');
            })
            ->where('applicants.job_id', $id)
            ->whereIn('test_result.test_id', $test_id)
            ->orderBy('average', 'desc')
            ->orderBy('applicants.created_at', 'desc')
            ->groupBy('applicants.id')
            ->paginate(5);

        return $applicants;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $job = Job::where('id', $id)->first();

        return view('forms.editJobForm', ['job' => $job]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //$user_id = $request->user()->user_id;
        //$job_id = $request->input('job_id');
        $title = $request->input('title');
        $description = $request->input('description');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('assets/job/', $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = Job::where('id', $id)->pluck('photo');
        }

        $job = Job::find($id);
        $job->title = $title;
        $job->description = $description;
        $job->photo = $photo_path;
        $job->save();

        $message = 'Job Updated';
        return $message;
    }

    public function updateJob(Request $request, $id) {
        $user_id = $request->user()->user_id;
        $job_id = $request->input('job_id');
        $title = $request->input('title');
        $description = $request->input('description');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('assets/job/', $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = Job::where('id', $id)->pluck('photo');
        }

        $job = Job::find($id);
        $job->title = $title;
        $job->description = $description;
        $job->photo = $photo_path;
        $job->save();

        $message = 'Job Updated';
        return $message;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user_id = Auth::user()->user_id;

        $job = Job::find($id);
        $job->delete();

        $message = "Job Deleted";

        return $message;
    }

    /* For Job Posting Dashboard */

    public function getJobs(Request $request) {
        if (Auth::check("user") || Auth::viaRemember("user")) {

            //Get logged in User
            $user_id = $request->user()->id;

            $users = User::find($user_id);

            $user_info = User::where('id', $user_id)->with('profile', 'evaluation')->first();

            $agent = new Agent(array(), $request->header('User-Agent'));

            if ($agent->isMobile()) {
                $is_mobile = 'true';
                $jobs = Job::with('applicants')->paginate(1);
            } else {
                $is_mobile = 'false';
                $jobs = Job::with('applicants')->paginate(3);
            }
            return view('jobs', ['name' => $users->first_name, 'user' => $users->user_type, 'user_info' => $user_info, 'user_id' => $user_id, 'jobs' => $jobs, 'is_mobile' => $is_mobile, 'count' => 0]);
        } else if (Auth::check("applicant") || Auth::viaRemember("applicant")) {
            return redirect()->route('a', [Auth::user("applicant")->id]);
        } else {
            return view('home');
        }
    }

    public function addJobForm(Request $request) {
        return view('templates.forms.addJobForm');
    }

    public function getEditJobForm(Request $request, $id) {

        $job = Job::where('id', $id)->first();

        return view('templates.forms.editJobForm', ['job' => $job]);
    }

    public function addJob(Request $request) {

        $user_id = $request->user()->id;

        $title = $request->input('title');
        $description = $request->input('description');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('uploads/jobs/' . $user_id, $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = 'uploads/default-avatar.jpg';
        }

        $job = new Job([
            'title' => $title,
            'user_id' => $user_id,
            'description' => $description,
            'photo' => $photo_path
        ]);

        $job->save();

        $message = "Job Added";
        return $message;
    }

    public function editJob(Request $request) {

        $user_id = $request->user()->id;
        $job_id = $request->input('job_id');
        $title = $request->input('title');
        $description = htmlspecialchars($request->input('description'));

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('uploads/jobs/' . $user_id, $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = Job::where('id', $job_id)->pluck('photo');
        }


        Job::where('id', $job_id)->update([
            'title' => $title,
            'description' => $description,
            'photo' => $photo_path,
        ]);

        $message = 'Job Updated';
        return $message;
    }

    public function deleteJob(Request $request) {

        $job_id = $request->input('job_id');

        Job::where('id', $job_id)->delete();

        $message = 'Job Deleted';
        return $message;
    }

    /* For Single Job Posting */

    public function getJobPosting(Request $request, $id) {

        /* $job_posting = Job::with('applicants')->where('id', $id)->first();
          if ($job_posting !== NULL) {


          $applicants = Applicant::with(['status' => function ($query) {
          $query->orderBy('created_at', 'desc');
          }])->where('job_id', $id)->orderBy('created_at', 'desc')->paginate(5);

          return view('templates.show.jobPost', ['job' => $job_posting, 'applicants' => $applicants, 'count' => 0]);
          } else {

          return redirect()->route('home');
          } */

        $job = Job::with('applicants')->where('id', $id)->first();

        $assets = ['jobs'];


        return view('jobs.show', [
            'job' => $job,
            'assets' => $assets,
            'count' => 0
        ]);
    }

    public function getApplyToJobForm(Request $request) {
        return view('forms.applyToJobForm');
    }

    public function applyToJob(Request $request) {

        $job_id = $request->input('job_id');
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $date = date('Y-m-d h:i:s', time());
        $username = strtolower(preg_replace('/\s+/', '', $name) . '@' . $_SERVER['SERVER_NAME']);
        $password = $request->input('password');
        $remember_token = $request->input('remember');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('assets/applicant/photos/', $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = 'assets/user/avatar.png';
        }

        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $resume_save = $resume->move('assets/applicant/resumes/', $resume->getClientOriginalName());
            $resume_path = $resume_save->getPathname();
        } else {
            $resume_path = 'assets/applicant/';
        }

        $applicant = new Applicant([
            'job_id' => $job_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'photo' => $photo_path,
            'resume' => $resume_path,
            'password' => bcrypt($password)
        ]);

        if ($name !== '' || $email !== '') {
            $applicant->save();
            $message = "Application Submitted";

            Auth::loginUsingId("applicant", $applicant->id);
        } else {
            $message = "Application Denied";
        }

        /* Switch to postfix database here */
        //Create a temporary mailbox for applicant

        /* $mailbox = new MailBox([
          'username' => $username,
          'password' => preg_replace('/\s+/', '', shell_exec("doveadm pw -s SHA512-CRYPT -p " . $password)),
          //'password' => '',
          'name' => $username,
          'maildir' => $_SERVER['SERVER_NAME'] . '/' . $username,
          'quota' => 0,
          'local_part' => $username,
          'domain' => $_SERVER['SERVER_NAME'],
          'created' => $date,
          'modified' => '',
          'active' => 1
          ]);

          $mailbox->save();

          //Create alias to map to itself
          $mailboxalias = new MailBoxAlias([
          'address' => $username . '@' . $_SERVER['SERVER_NAME'],
          'goto' => $username . '@' . $_SERVER['SERVER_NAME'],
          'domain' => $_SERVER['SERVER_NAME'],
          'created' => $date,
          'modified' => '',
          'active' => 1
          ]);

          $mailboxalias->save(); */

        //return $message; 

        return $applicant->id;
    }

    /* Get Applicants */

    public function getJobApplicants(Request $request, $id) {

        $agent = new Agent(array(), $request->header('User-Agent'));

        if ($agent->isMobile()) {
            $applicants = Applicant::with(['tags' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }])->where('job_id', $id)->orderBy('created_at', 'desc')->get();

            return view('templates.show.applicantListMobile', ['applicants' => $applicants, 'count' => 0]);
        } else {
            $applicants = Applicant::with(['tags' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }])->where('job_id', $id)->orderBy('created_at', 'desc')->paginate(5);

            return view('templates.show.applicantList', ['applicants' => $applicants, 'count' => 0]);
        }
    }

    /* For Tags */

    public function addTag(Request $request) {
        $user_id = $request->user()->user_id;
        $job_id = $request->input('job_id');
        $applicant_id = $request->input('applicant_id');
        $tags = $request->input('tags');

        $tag_exists = ApplicantTag::where('job_id', $job_id)->where('applicant_id', $applicant_id)->where('user_id', $user_id)->count();

        if ($tag_exists === 0) {

            $new_tag = new ApplicantTag([
                'user_id' => $user_id,
                'job_id' => $job_id,
                'applicant_id' => $applicant_id,
                'tags' => $tags
            ]);

            $new_tag->save();

            $tag_item = ApplicantTag::where('id', $new_tag->id)->first();
        } else {
            $update_tag = ApplicantTag::where('job_id', $job_id)->where('applicant_id', $applicant_id)->where('user_id', $user_id)->update([
                'tags' => $tags
            ]);

            $tag_item = ApplicantTag::where('id', $update_tag->id)->first();
        }

        return $tag_item->tags;
    }

    /* Get all tags made by all users */

    public function getTags(Request $request) {

        $term = $request->input('term');

        $entries = ApplicantTag::where('tags', 'like', '%' . $term . '%')->get();
        $tags = [];

        foreach ($entries as $entry) {
            $tags_string = explode(',', $entry->tags);
            foreach ($tags_string as $string) {
                $tags[] = $string;
            }
        }

        return $tags;
    }

    public function saveJobNotes(Request $request) {
        $job_id = $request->input('job_id');
        $notes = $request->input('notes');

        $job = Job::where('id', $job_id);
        $job->update([
            'notes' => $notes
        ]);

        return "true";
    }

    public function checkApplicantDuplicateEmail(Request $request) {

        $email = $request->input('email');

        $applicant = Applicant::where('email', $email)->count();

        if ($applicant > 0) {

            //There is a duplicate, return false to the jquery Validator
            return "false";
        } else {
            //No Duplicates, return true to the jquery Validator
            return "true";
        }
    }

    public function addJobFormCompany() {
        return view('forms.addJobForm');
    }

    public function addJobCompany(Request $request) {

        $user_id = Auth::user('user')->user_id;
        $company_id = $request->input('company_id');
        $job_title = $request->input('job_title');

        $job = new Job();
        $job->user_id = $user_id;
        $job->company_id = $company_id;
        $job->title = $job_title;
        $job->save();


        return view('jobs.partials._newjob', [
            'job' => $job,
            'company_id' => $company_id
        ]);
    }

}
