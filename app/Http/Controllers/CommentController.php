<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Job;
use App\Models\Applicant;
use App\Models\User;

use Auth;
use Redirect;
use Input;
use Validator;

Class CommentController extends BaseController
{

    public function index()
    {
    }

    public function show()
    {
    }

    public function create()
    {
    }

    public function edit()
    {
    }

    public function store()
    {

        $validation = Validator::make(Input::all(), ['comment' => 'required']);

        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $comment = new Comment;
        $data = Input::all();
        $data['user_id'] = Auth::user()->id;
        $comment->fill($data);
        $comment->save();

        return Redirect::back()->withSuccess('Successfully saved!!');
    }

    public function update()
    {
    }

    public function destroy($comment_id)
    {
        $comment = Comment::find($comment_id);

        if (!$comment || ($comment->username != Auth::user()->username && !Entrust::hasRole('Admin')))
            return Redirect::back()->withErrors('This is not a valid link!!');

        $comment->delete($comment_id);
        return Redirect::back()->withSuccess('Deleted successfully!!');
    }
    
    public function addComment(Request $request) {

        if ($request->user() !== NULL) {

            $user_id = $request->user()->user_id;
        } else {
            $user_id = 0;
        }
        $profile_id = $request->input('profile_id');
        $job_id = $request->input('job_id');
        $applicant_id = $request->input('applicant_id');
        $comment = $request->input('comment');
        $send_email = $request->input('send_email');
        $belongs_to = $request->input('module');

        $new_comment = new Comment([
            'user_id' => $user_id,
            'unique_id' => $applicant_id,
            'belongs_to' => $belongs_to,
            'comment' => $comment
        ]);

        $new_comment->save();

        $job_owner = Job::where('id', $job_id)->first();
        $domain = $_SERVER['SERVER_NAME'];

        if ($profile_id !== '0') {

            $new_comment_item = Comment::with('user')->where('comment_id', $new_comment->comment_id)->first();

            //Get from and to address            
            $from_email = User::where('user_id', $user_id)->first();
            $to_email = Applicant::where('id', $applicant_id)->first();

            if ($send_email === 'true') {
                Mail::queue('templates.emails.commentEmail', ['from_email' => $from_email, 'to_email' => $to_email, 'job_owner' => $job_owner, 'domain' => $domain, 'comment' => $comment], function ($message) use ($from_email, $to_email, $domain, $job_owner) {
                    $message->from(strtolower($from_email->first_name . $from_email->last_name) . '@' . $domain, 'Job.tc');
                    //$message->bcc(strtolower($from_email->first_name . $from_email->last_name) . '@' . $domain);
                    $message->to([$to_email->email, strtolower($to_email->first_name . $to_email->last_name) . '@' . $domain]);
                    $message->subject($job_owner->title);
                });
            }
        } else {
            $new_comment_item = Comment::with('applicant')->where('comment_id', $new_comment->comment_id)->first();

            //Get from and to address            
            $from_email = Applicant::where('id', $applicant_id)->first();
            $to_email = User::where('user_id', $job_owner->user_id)->first();

            if ($send_email === 'true') {
                Mail::queue('templates.emails.commentEmail', ['from_email' => $from_email, 'to_email' => $to_email, 'job_owner' => $job_owner, 'domain' => $domain, 'comment' => $comment], function ($message) use ($from_email, $to_email, $domain, $job_owner) {
                    $message->from(strtolower($from_email->first_name . $from_email->last_name) . '@' . $domain, 'Job.tc');
                    //$message->bcc(strtolower($from_email->first_name . $from_email->last_name) . '@' . $domain);
                    $message->to([$to_email->email, strtolower($to_email->first_name . $to_email->last_name) . '@' . $domain]);
                    $message->subject($job_owner->title);
                });
            }
        }

        //$username = strtolower($from_email->first_name.$from_email->last_name).'@'.$_SERVER['SERVER_NAME'];
        //$password = strtolower($from_email->first_name.$from_email->last_name);
        //Config::set('mail.username',$username);
        //Config::set('mail.password',$password);



        return view('common.commentListItem', ['comment' => $new_comment_item, 'applicant' => $applicant_id]);
    }
    
}

?>