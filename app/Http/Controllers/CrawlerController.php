<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Job;

class CrawlerController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function addJobFromCrawler(Request $request) {

        $user_id = $request->input('user_id');

        $company_id = $request->input('company_id');

        $title = $request->input('title');
        $description = $request->input('description');

        $job_exists = Job::where('title', 'like', $title)->where('description', 'like', $description)->count();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('uploads/job/', $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = 'assets/user/default-avatar.jpg';
        }

        $job = new Job([
            'title' => $title,
            'user_id' => $user_id,
            'company_id' => $company_id,
            'title' => $title,
            'description' => $description,
            'photo' => $photo_path
        ]);

        if ($job_exists === 0) {
            $job->save();
        }

        $message = "Job Added";
        return $message;
    }

    public function addApplicantFromCrawler(Request $request) {

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

        $message = "Application Submitted";
        return $message;
    }

}
