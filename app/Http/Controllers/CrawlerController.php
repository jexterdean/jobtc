<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Applicant;

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

        $job_exists = Job::where('title', 'like', $title)
                ->where('description', 'like', $description)
                ->where('user_id',$user_id)
                ->where('company_id',$company_id)
                ->count();

        $job = new Job([
            'title' => $title,
            'user_id' => $user_id,
            'company_id' => $company_id,
            'title' => $title,
            'description' => $description,
            'photo' => ''
        ]);

        if ($job_exists === 0) {
            $job->save();
        }

        $message = "Job Added";
        return $message;
    }

    public function addApplicantFromCrawler(Request $request) {

        $job = $request->input('job');
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $date = date('Y-m-d h:i:s', time());
        

        $job_id = Job::where('title', 'like', '%' . preg_replace('/\s+/', '', $job) . '%')->first();

        $applicant_exists = Applicant::where('name', 'like', $name)->count();

        $photo_path = 'assets/user/default-avatar.jpg';
        
        $resume_path = 'assets/applicant/resumes/Resume'.str_replace(' ','',$name).'.pdf';
        
        $applicant = new Applicant([
            'job_id' => $job_id->id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'photo' => $photo_path,
            'resume' => $resume_path,
            'password' => bcrypt($email)
        ]);

        if ($applicant_exists ===  0) {
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
