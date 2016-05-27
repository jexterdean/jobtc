<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Applicant;
use App\Models\ApplicantTag;
use App\Models\ApplicantRating;
use App\Models\Comment;
use App\Models\Video;
use App\Models\VideoTag;
use App\Models\Test;
use App\Models\TestPerApplicant;
use App\Models\TestPerJob;
use App\Models\Question;

class ApplicantController extends Controller {

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
    public function show(Request $request, $id) {
        $applicant = Applicant::where('id', $id)->first();

        if ($applicant !== NULL) {

            //Get logged in User
            if ($request->user() !== NULL) {
                $user_id = $request->user()->user_id;

                $user_info = User::where('user_id', $user_id)->with('profile')->first();

                //$comments = Comment::with('user', 'profile')->where('applicant_id', $id)->orderBy('id', 'desc')->get();

                $comments = Comment::with('user')->where('belongs_to', 'applicant')->where('unique_id', $id)->orderBy('comment_id', 'desc')->get();
            } else {

                $user_info = Applicant::where('id', $id)->first();

                //$comments = Comment::with('applicant')->where('applicant_id', $id)->orderBy('id', 'desc')->get();
                //$comments = Applicant::with('comment')->where('id',$id)->orderBy('id', 'desc')->get();
                $comments = Comment::with('user','applicant')->where('belongs_to', 'applicant')->where('unique_id', $id)->orderBy('comment_id', 'desc')->get();
            }


            $job = Job::where('id', $applicant->job_id)->first();

            $statuses = ApplicantTag::where('applicant_id', $id)->first();

            $prevApplicant = Applicant::where('id', '>', $id)->where('job_id', $applicant->job_id)->min('id');
            $nextApplicant = Applicant::where('id', '<', $id)->where('job_id', $applicant->job_id)->max('id');

            $rating = ApplicantRating::where('applicant_id', $id)->first();

            $videos = Video::with('video_tags')->where('applicant_id', $id)->orderBy('id', 'desc')->get();

            //Get the test permissions

            $test_ids = [];
            $test_jobs = TestPerJob::where('job_id', $applicant->job_id)->get();
            $test_applicants = TestPerApplicant::where('applicant_id', $applicant->id)->get();

            foreach ($test_jobs as $test_job) {
                array_push($test_ids, $test_job->test_id);
            }

            foreach ($test_applicants as $test_applicant) {
                array_push($test_ids, $test_applicant->test_id);
            }

            $tests = Test::whereIn('id', array_unique($test_ids))->get();
            $questions = Question::whereIn('test_id', array_unique($test_ids))
                    ->orderBy('order', 'ASC')
                    ->get();

            if (count($questions) > 0) {
                foreach ($questions as $v) {
                    $v->question_choices = json_decode($v->question_choices);
                }
            }

            $assets = ['applicants','quizzes'];

            return view('applicants.show', [
                'applicant' => $applicant,
                'user_info' => $user_info,
                'comments' => $comments,
                'statuses' => $statuses,
                'job' => $job,
                'tests' => $tests,
                'questions' => $questions,
                'previous_applicant' => $prevApplicant,
                'next_applicant' => $nextApplicant,
                'rating' => $rating,
                'videos' => $videos,
                'assets' => $assets,
                'count' => 0]);
        }
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

            return view('jobs.partials.applicantList', ['applicants' => $applicants, 'count' => 0]);
        }
    }

    public function getSearchApplicants(Request $request, $term) {

        $applicants_with_status = ApplicantTag::get();
        $applicant_id_array = [];

        $agent = new Agent(array(), $request->header('User-Agent'));

        foreach ($applicants_with_status as $applicant) {
            $statuses = explode(",", $applicant->tag);
            foreach ($statuses as $status) {
                if ($status === $term) {
                    $applicant_id_array[] = $applicant->applicant_id;
                }
            }
        }

        if ($agent->isMobile()) {
            $applicants = Applicant::with(['tags' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }])->whereIn('id', $applicant_id_array)->orderBy('created_at', 'desc')->get();

            return view('templates.show.applicantListMobile', ['applicants' => $applicants, 'count' => 0]);
        } else {
            $applicants = Applicant::with(['tags' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }])->whereIn('id', $applicant_id_array)->orderBy('created_at', 'desc')->paginate(5);

            $is_mobile = false;
            return view('applicants', ['applicants' => $applicants, 'is_mobile' => $is_mobile, 'count' => 0]);
        }
    }

    public function getApplicants(Request $request, $id) {
        $agent = new Agent(array(), $request->header('User-Agent'));

        if ($agent->isMobile()) {
            $is_mobile = 'true';
            $applicants = Applicant::with(['tags' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }])->where('job_id', $id)->orderBy('created_at', 'desc')->get();

            return view('templates.show.applicantListMobile', ['applicants' => $applicants, 'count' => 0]);
        } else {
            $is_mobile = 'false';
            $applicants = Applicant::with(['tags' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }])->where('job_id', $id)->orderBy('created_at', 'desc')->paginate(18);

            $job = Job::where('id', $id)->first();

            return view('applicants', ['applicants' => $applicants, 'job' => $job, 'is_mobile' => $is_mobile, 'count' => 0]);
        }
    }

    public function getApplicantPosting(Request $request, $id) {
        if (Auth::check("user") || Auth::viaRemember("user") || Auth::check("applicant") || Auth::viaRemember("applicant")) {
            $applicant = Applicant::where('id', $id)->first();

            if ($applicant !== NULL) {

                //Get logged in User
                if ($request->user() !== NULL) {
                    $user_id = $request->user()->id;

                    $user_info = User::where('id', $user_id)->with('profile')->first();

                    $comments = Comment::with('user', 'profile')->where('applicant_id', $id)->orderBy('id', 'desc')->get();
                } else {

                    $user_info = Applicant::where('id', $id)->first();

                    $comments = Comment::with('applicant')->where('applicant_id', $id)->orderBy('id', 'desc')->get();
                    //$comments = Applicant::with('comment')->where('id',$id)->orderBy('id', 'desc')->get();
                }


                $job = Job::where('id', $applicant->job_id)->first();

                $statuses = ApplicantTag::where('applicant_id', $id)->first();

                $prevApplicant = Applicant::where('id', '>', $id)->where('job_id', $applicant->job_id)->min('id');
                $nextApplicant = Applicant::where('id', '<', $id)->where('job_id', $applicant->job_id)->max('id');

                $rating = Rating::where('applicant_id', $id)->first();

                $videos = Video::with('video_statuses')->where('applicant_id', $id)->orderBy('id', 'desc')->get();

                $agent = new Agent(array(), $request->header('User-Agent'));

                if ($agent->isMobile()) {
                    $is_mobile = 'true';
                } else {
                    $is_mobile = 'false';
                }
                return view('templates.show.applicant', ['applicant' => $applicant, 'user_info' => $user_info, 'comments' => $comments, 'statuses' => $statuses, 'job' => $job, 'previous_applicant' => $prevApplicant, 'next_applicant' => $nextApplicant, 'rating' => $rating, 'videos' => $videos, 'is_mobile' => $is_mobile, 'count' => 0]);
            } else {

                return redirect()->route('dashboard');
            }
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function deleteApplicant(Request $request) {
        $applicant_id = $request->input('applicant_id');

        Applicant::where('id', $applicant_id)->delete();
        $message = "Applicant Deleted";
        return $message;
    }

}
