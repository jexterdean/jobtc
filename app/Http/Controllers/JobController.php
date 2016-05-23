<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;
use App\Models\Applicant;
use App\Models\Status;
use App\Models\MailBox;
use App\Models\MailBoxAlias;
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
            $photo_path = 'assets/user/avatar.png';
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

        $job = Job::with('applicants')->where('id', $id)->first();

        $applicants = Applicant::with(['status' => function ($query) {
                        $query->orderBy('created_at', 'desc');
                    }])->where('job_id', $id)->orderBy('created_at', 'desc')->paginate(5);

        $assets = ['jobs'];

        return view('jobs.show', [
            'job' => $job,
            'applicants' => $applicants,
            'assets' => $assets,
            'count' => 0
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $job = Job::where('id', $id)->first();

        return view('jobs.partials.editJobForm', ['job' => $job]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user_id = $request->user()->user_id;
        $job_id = $request->input('job_id');
        $title = $request->input('title');
        $description = htmlspecialchars($request->input('description'));

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
        $description = htmlspecialchars($request->input('description'));

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

    public function addJobCrawler(Request $request) {

        $user_id = $request->input('id');

        $title = $request->input('title');
        $description = $request->input('description');


        $job_exists = Job::where('title', 'like', $title)->where('description', 'like', $description)->count();

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

        if ($job_exists === 0) {
            $job->save();
        }

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
        return view('jobs.partials.applyToJobForm');
    }

    public function applyToJob(Request $request) {

        $job_id = $request->input('job_id');
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $date = date('Y-m-d h:i:s', time());
        $username = strtolower(preg_replace('/\s+/', '', $name). '@' . $_SERVER['SERVER_NAME']);
        $password = strtolower(preg_replace('/\s+/', '', $name));
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

        $applicant->save();

        /* Switch to postfix database here */
        //Create a temporary mailbox for applicant

        /*$mailbox = new MailBox([
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

        $mailboxalias->save();*/

        $message = "Application Submitted";
        return $message;
    }

    public function addApplicantCrawler(Request $request) {

        $job = $request->input('job');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $date = date('Y-m-d h:i:s', time());
        $username = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $first_name)) . preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $last_name)) . '@' . $_SERVER['SERVER_NAME']);
        $password = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $first_name)) . preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $last_name)));

        $job_id = Job::where('title', 'like', '%' . preg_replace('/\s+/', '', $job) . '%')->first();

        $applicant_exists = Applicant::where('first_name', 'like', $first_name)->where('last_name', 'like', $last_name)->count();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('uploads/applicants/' . $job_id->id, $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = 'uploads/default-avatar.jpg';
        }

        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $resume_save = $resume->move('uploads/applicants/' . $job_id->id, $resume->getClientOriginalName());
            $resume_path = $resume_save->getPathname();
        } else {
            $resume_path = 'uploads/applicants/Resume' . $first_name . $last_name . '.pdf';
        }

        $applicant = new Applicant([
            'job_id' => $job_id->id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'photo' => $photo_path,
            'resume' => $resume_path,
            'password' => bcrypt($password)
        ]);

        if ($applicant_exists === 0) {
            $applicant->save();
        }

        /* Switch to postfix database here */
        //Create a temporary mailbox for applicant

        $mailbox = new MailBox([
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

        $mailboxalias->save();

        $message = "Application Submitted";
        return $message;
    }

    /* Get Applicants */

    public function getJobApplicants(Request $request, $id) {

        $agent = new Agent(array(), $request->header('User-Agent'));

        if ($agent->isMobile()) {
            $applicants = Applicant::with(['status' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }])->where('job_id', $id)->orderBy('created_at', 'desc')->get();

            return view('templates.show.applicantListMobile', ['applicants' => $applicants, 'count' => 0]);
        } else {
            $applicants = Applicant::with(['status' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }])->where('job_id', $id)->orderBy('created_at', 'desc')->paginate(5);

            return view('templates.show.applicantList', ['applicants' => $applicants, 'count' => 0]);
        }
    }

}
