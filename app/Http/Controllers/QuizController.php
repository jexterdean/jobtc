<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use \View;
use \DB;
use \Validator;
use \Input;
use \Redirect;
use \Auth;
use App\Models\Test;
use App\Models\Question;
use App\Models\TestResultModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class QuizController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = [
            'assets' => ['input-mask', 'waiting', 'select', 'tags'],
            'page' => 'quiz'
        ];
        $this->setData($data);

        return View::make('quiz.default', $data);
    }

    private function setData(&$data) {
        $t = DB::table('question_type')
                ->select('id', 'type')
                ->get();
        $question_type = array_pluck($t, 'type', 'id');
        $data['question_type'] = $question_type;

        $result = DB::table('test_result')
                ->select(DB::raw(
                                'fp_test.id as test_id,
                fp_test.title,
                fp_user.name,
                (
                    select count(a.id)
                    from fp_test_result as a
                    where
                        a.test_id = fp_test_result.test_id AND
                        a.unique_id = fp_test_result.unique_id AND
                        a.result = 1 AND
                        fp_test_result.belongs_to = "employee"
                ) as score,
                (
                    select count(b.id)
                    from fp_test_result as b
                    where
                        b.test_id = fp_test_result.test_id AND
                        b.unique_id = fp_test_result.unique_id
                        AND fp_test_result.belongs_to = "employee"
                ) as total_question'
                ))
                ->groupBy('test_result.unique_id', 'test_result.test_id')
                ->leftJoin('test', 'test.id', '=', 'test_result.test_id')
                ->leftJoin('user', 'user.user_id', '=', 'test_result.unique_id')
                ->orderBy('test_result.created_at', 'asc')
                ->get();
        if (count($result) > 0) {
            foreach ($result as $r) {
                $average = $r->score > 0 ? $r->score / $r->total_question : $r->score;
                $average *= 100;
                $r->average = number_format($average) . '%';
            }
        }

        $data['result'] = $result;

        $test = DB::table('test')
                ->select(DB::raw('
                fp_test.*,
                (
                    SELECT count(fp_question.id)
                    FROM fp_question
                    WHERE
                        fp_question.test_id = fp_test.id
                ) as num_question,
                (
                    SELECT count(fp_test_result.id) > 0
                    FROM fp_test_result
                    WHERE
                        fp_test_result.test_id = fp_test.id AND
                        fp_test_result.unique_id = ' . Auth::user('user')->user_id . '
                ) as review_only
            '))
                ->orderBy('order', 'asc')
                ->get();
        if (count($test) > 0) {
            foreach ($test as $t) {
                $t->total_time = 0;
                $questions = DB::table('question')
                        ->where('test_id', '=', $t->id)
                        ->orderBy('order', 'ASC')
                        ->get();
                if (count($questions) > 0) {
                    foreach ($questions as $q) {
                        sscanf($q->length, "%d:%d:%d", $hours, $minutes, $seconds);
                        $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
                        $t->total_time += $time_seconds;
                        $q->question_choices = json_decode($q->question_choices);
                    }
                }
                $t->question = $questions;

                $score = 0;
                $taker_count = 0;
                if (count($result) > 0) {
                    foreach ($result as $r) {
                        if ($r->test_id == $t->id) {
                            $average = $r->score > 0 ? $r->score / count($questions) : $r->score;
                            $average *= 100;
                            $average = (float) number_format($average);
                            $score += $average;
                            $taker_count ++;
                        }
                    }
                }
                $t->average = $score > 0 ? number_format($score / $taker_count) : $score;
                $t->average .= '%';
            }
        }
        $data['test'] = $test;

        //get shared files
        $file_dir = public_path() . '/assets/shared-files';
        $files = is_dir($file_dir) ? \File::allFiles($file_dir) : array();
        $data['files'] = $files;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $page = isset($_GET['p']) ? $_GET['p'] : 'test';
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $test_info = DB::table('test')
            ->where('id', '=', $id)
            ->first();

        $data = [
            'assets' => ['input-mask', 'waiting'],
            'page' => 'edit',
            'test_id' => $id,
            'test_info' => $test_info
        ];
        $this->setData($data);

        //get shared files
        $file_dir = public_path() . '/assets/shared-files';
        $image_files = \File::allFiles($file_dir . '/image');
        $data['image_files'] = $image_files;
        $sound_files = \File::allFiles($file_dir . '/sound');
        $data['sound_files'] = $sound_files;

        return View::make('quiz.' . $page . '.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $page = isset($_GET['p']) ? $_GET['p'] : 'test';
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $label = '';

        $validation = '';
        if ($page == "test") {
            $label = 'Test';
            $validation = Validator::make($request->all(), [
                        'title' => 'required',
                        'description' => 'required',
                        'start_message' => 'required',
                        'completion_message' => 'required'
            ]);
        } else if ($page == "question") {
            $label = 'Question';
            $required = [
                'question_type_id' => 'required',
                'question' => 'required'
            ];
            if (Input::get('question_type_id') != 3) {
                $required['points'] = 'required';
            }

            $validation = Validator::make($request->all(), $required);
        } else if ($page == "exam") {
            $label = 'Exam';
            $validation = Validator::make($request->all(), [
            ]);
        }

        if ($validation->fails()) {
            return Redirect::to('quiz')
                            ->withInput()
                            ->withErrors($validation->messages());
        }

        DB::beginTransaction();

        try {
            if ($page == "test") {
                $test = new Test();
                $test->user_id = Auth::user()->user_id;
                $test->title = Input::get('title');
                $test->description = Input::get('description');
                $test->start_message = Input::get('start_message');
                $test->completion_message = Input::get('completion_message');
                $test->default_time = Input::get('default_time') ? '00:' . Input::get('default_time') : '';
                $test->completion_image = Input::get('completion_image');
                $test->completion_sound = Input::get('completion_sound');
                $test->default_tags = Input::get('default_tags');
                $test->save();

                if (Input::file('completion_image_upload')) {
                    $shared_dir = public_path() . '/assets/shared-files/image/';
                    if(!is_dir($shared_dir)){
                        mkdir($shared_dir, 0777, TRUE);
                    }

                    $fileName = Input::file('completion_image_upload')->getClientOriginalName();

                    Input::file('completion_image_upload')->move($shared_dir, $fileName);

                    DB::table('test')
                        ->where('id', '=', $test->id)
                        ->update(['completion_image' => $fileName]);
                }
                if (Input::file('completion_sound_upload')) {
                    $shared_dir = public_path() . '/assets/shared-files/sound/';
                    if(!is_dir($shared_dir)){
                        mkdir($shared_dir, 0777, TRUE);
                    }

                    $fileName = Input::file('completion_sound_upload')->getClientOriginalName();

                    Input::file('completion_sound_upload')->move($shared_dir, $fileName);

                    DB::table('test')
                        ->where('id', '=', $test->id)
                        ->update(['completion_sound' => $fileName]);
                }
                if (Input::file('test_photo')) {
                    $photo_dir = public_path() . '/assets/img/test/';
                    if (!is_dir($photo_dir)) {
                        mkdir($photo_dir, 0777, TRUE);
                    }

                    $extension = Input::file('test_photo')->getClientOriginalExtension();
                    $fileName = $test->id . "." . $extension;

                    Input::file('test_photo')->move($photo_dir, $fileName);

                    DB::table('test')
                            ->where('id', '=', $test->id)
                            ->update(['test_photo' => $fileName]);
                }
            } else if ($page == "question") {
                $question = new Question();
                $question->test_id = $id;
                $question->question_type_id = Input::get('question_type_id');
                $question->question = Input::get('question');
                $question->question_choices = Input::has('question_choices') ? json_encode(Input::get('question_choices')) : '[]';
                $question->question_answer = Input::get('question_answer');
                $question->length = Input::get('length') ? '00:' . Input::get('length') : '';
                if(Input::get('points')) {
                    $question->points = Input::get('points');
                }
                $question->explanation = Input::get('explanation');
                if(Input::get('marking_criteria')) {
                    $question->marking_criteria = Input::get('marking_criteria');
                }
                if(Input::get('max_point')) {
                    $question->max_point = Input::get('max_point');
                }
                $question->save();

                $file_key = 'question_photo';
                if (Input::file('question_photo')) {
                    $photo_dir = public_path() . '/assets/img/question/';
                    if (!is_dir($photo_dir)) {
                        mkdir($photo_dir, 0777, TRUE);
                    }

                    $extension = Input::file($file_key)->getClientOriginalExtension();
                    $fileName = $question->id . "." . $extension;
                    Input::file('question_photo')->move($photo_dir, $fileName);

                    DB::table('question')
                            ->where('id', '=', $id)
                            ->update(['question_photo' => $fileName]);
                }
            } else if ($page == "exam") {
                $q = Question::where('id', Input::get('question_id'))
                        ->first();

                $r = $q->question_type_id == 3 ?
                        0 :
                        (
                        $q->question_type_id == 1 ?
                                ($q->question_answer == Input::get('answer') ? 1 : 0) :
                                (strtolower($q->question_answer) == strtolower(Input::get('answer')) ? 1 : 0)
                        );

                $result = new TestResultModel();
                $result->test_id = $id;
                $result->question_id = Input::get('question_id');
                //$result->user_id = Auth::user()->user_id;

                if (Auth::check('user')) {
                    $result->unique_id = Auth::user('user')->user_id;
                    $result->belongs_to = 'employee';
                }

                if (Auth::check('applicant')) {
                    $result->unique_id = Auth::user('applicant')->id;
                    $result->belongs_to = 'applicant';
                }

                $result->answer = Input::get('answer');
                $result->result = $r;
                $result->save();
            }

            DB::commit();

            return Redirect::to('quiz')
                            ->withSuccess($label . " added successfully!");
        } catch (\Exception $e) {
            DB::rollback();

            return Redirect::to('quiz')
                            ->withErrors($label . " failure when adding!");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $page = isset($_GET['p']) ? $_GET['p'] : 'view';

        //region if already taken the exam redirect to review page
        if (Auth::check('user')) {
            $taken_question = DB::table('test_result')
                    ->where('test_id', '=', $id)
                    ->where('unique_id', '=', Auth::user('user')->user_id)
                    ->where('belongs_to','employee')
                    ->count();
        }
        
        if (Auth::check('applicant')) {
            $taken_question = DB::table('test_result')
                    ->where('test_id', '=', $id)
                    ->where('unique_id', '=', Auth::user('applicant')->id)
                    ->where('belongs_to','applicant')
                    ->count();
        }

        $total_question = DB::table('question')
                ->where('test_id', '=', $id)
                ->count();
        $hasTaken = $taken_question == $total_question;
        if ($hasTaken && $page != 'review') {
            return Redirect::to('quiz/' . $id . '?p=review');
        }
        //endregion

        $data = [
            'assets' => [],
            'page' => $page
        ];
        $this->setData($data);

        $tests_info = DB::table('test')
                ->where('id', '=', $id)
                ->first();
        $questions_info = array();
        if ($page == 'review') {
            $questions_info = DB::table('question')
                    ->where('test_id', '=', $id)
                    ->orderBy('order', 'ASC')
                    ->get();
        } else {
            $questions_info = DB::table('question')
                    ->where('test_id', '=', $id)
                    ->whereRaw('
                    (
                        SELECT count(fp_test_result.id)
                        FROM fp_test_result
                        WHERE
                            fp_test_result.question_id = fp_question.id AND
                            fp_test_result.unique_id = ' . Auth::user()->user_id . '
                    ) = 0
                ')
                    ->orderBy('order', 'ASC')
                    ->get();
        }
        if (count($questions_info) > 0) {
            foreach ($questions_info as $v) {
                $v->question_choices = json_decode($v->question_choices);
            }
        }

        if ($page == 'review') {
            $r = DB::table('test_result')
                    ->where('test_result.test_id', '=', $id)
                    ->get();
            $review_result = array();
            if (count($r) > 0) {
                foreach ($r as $v) {
                    $review_result[$v->question_id] = (Object) array(
                                'answer' => $v->answer,
                                'result' => $v->result,
                    );
                }
            }
            $data['review_result'] = $review_result;
        }

        $data['tests_info'] = $tests_info;
        $data['questions_info'] = $questions_info;

        return View::make('quiz.default', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $page = isset($_GET['p']) ? $_GET['p'] : 'test';
        $data = [
            'assets' => ['input-mask', 'waiting'],
            'page' => 'edit'
        ];
        $this->setData($data);

        //get shared files
        $file_dir = public_path() . '/assets/shared-files';
        $image_files = \File::allFiles($file_dir . '/image');
        $data['image_files'] = $image_files;
        $sound_files = \File::allFiles($file_dir . '/sound');
        $data['sound_files'] = $sound_files;

        if($page == "test") {
            $tests_info = DB::table('test')
                    ->where('id', '=', $id)
                    ->first();
            $data['tests_info'] = $tests_info;
        } else {
            $questions_info = DB::table('question')
                    ->where('id', '=', $id)
                    ->first();
            $data['questions_info'] = $questions_info;
        }

        return View::make('quiz.' . $page . '.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $page = isset($_GET['p']) ? $_GET['p'] : 'test';

        $validation = '';
        if ($page == "test") {
            $validation = Validator::make($request->all(), [
                        'title' => 'required',
                        'description' => 'required',
                        'start_message' => 'required',
                        'completion_message' => 'required'
            ]);
        } else {
            $validation = Validator::make($request->all(), [
                'question_type_id' => 'required',
                'question' => 'required'
            ]);
            if(Input::get('question_type_id') != 3){
                $required['points'] = 'required';
            }
        }
        if ($validation->fails()) {
            return Redirect::to('quiz')
                            ->withInput()
                            ->withErrors($validation->messages());
        }

        DB::beginTransaction();

        try {
            if ($page == "test") {
                $test = Test::find($id);
                $test->user_id = Auth::user()->user_id;
                $test->title = Input::get('title');
                $test->description = Input::get('description');
                $test->start_message = Input::get('start_message');
                $test->completion_message = Input::get('completion_message');
                $test->default_time = Input::get('default_time') ? '00:' . Input::get('default_time') : '';
                $test->completion_image = Input::get('completion_image');
                $test->completion_sound = Input::get('completion_sound');
                $test->default_tags = Input::get('default_tags');
                $test->save();

                if (Input::file('completion_image_upload')) {
                    $shared_dir = public_path() . '/assets/shared-files/image/';
                    if(!is_dir($shared_dir)){
                        mkdir($shared_dir, 0777, TRUE);
                    }

                    $fileName = Input::file('completion_image_upload')->getClientOriginalName();

                    Input::file('completion_image_upload')->move($shared_dir, $fileName);

                    DB::table('test')
                        ->where('id', '=', $test->id)
                        ->update(['completion_image' => $fileName]);
                }
                if (Input::file('completion_sound_upload')) {
                    $shared_dir = public_path() . '/assets/shared-files/sound/';
                    if(!is_dir($shared_dir)){
                        mkdir($shared_dir, 0777, TRUE);
                    }

                    $fileName = Input::file('completion_sound_upload')->getClientOriginalName();

                    Input::file('completion_sound_upload')->move($shared_dir, $fileName);

                    DB::table('test')
                        ->where('id', '=', $test->id)
                        ->update(['completion_sound' => $fileName]);
                }
                if (Input::file('test_photo')) {
                    $photo_dir = public_path() . '/assets/img/test/';
                    if (!is_dir($photo_dir)) {
                        mkdir($photo_dir, 0777, TRUE);
                    }

                    $extension = Input::file('test_photo')->getClientOriginalExtension();
                    $fileName = $test->id . "." . $extension;
                    Input::file('test_photo')->move($photo_dir, $fileName);

                    DB::table('test')
                            ->where('id', '=', $test->id)
                            ->update(['test_photo' => $fileName]);
                }
            } else {
                $question = Question::find($id);
                $question->question_type_id = Input::get('question_type_id');
                $question->question = Input::get('question');
                $question->question_choices = Input::has('question_choices') ? json_encode(Input::get('question_choices')) : '[]';
                $question->question_answer = Input::get('question_answer');
                $question->length = Input::get('length') ? '00:' . Input::get('length') : '';
                if(Input::get('points')) {
                    $question->points = Input::get('points');
                }
                $question->explanation = Input::get('explanation');
                if(Input::get('marking_criteria')) {
                    $question->marking_criteria = Input::get('marking_criteria');
                }
                if(Input::get('max_point')) {
                    $question->max_point = Input::get('max_point');
                }
                $question->question_tags = Input::get('question_tags');
                if (Input::get('clear_photo')) {
                    $photo_dir = public_path() . '/assets/img/question/';
                    $photo_dir .= $question->question_photo;
                    if (file_exists($photo_dir)) {
                        unlink($photo_dir);
                        $question->question_photo = '';
                    }
                }
                $question->save();

                $file_key = 'question_photo';
                if (Input::file('question_photo')) {
                    $photo_dir = public_path() . '/assets/img/question/';
                    if (!is_dir($photo_dir)) {
                        mkdir($photo_dir, 0777, TRUE);
                    }

                    $extension = Input::file($file_key)->getClientOriginalExtension();
                    $fileName = $question->id . "." . $extension;
                    Input::file('question_photo')->move($photo_dir, $fileName);

                    DB::table('question')
                            ->where('id', '=', $id)
                            ->update(['question_photo' => $fileName]);
                }
            }

            DB::commit();

            return Redirect::to('quiz')
                            ->withSuccess(($page == "test" ? "Test" : "Question") . " updated successfully!");
        } catch (\Exception $e) {
            DB::rollback();

            return Redirect::to('quiz')
                            ->withErrors(($page == "test" ? "Test" : "Question") . " failure when adding!");
        }
    }

    /**
     * Update test sort the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function testSort(Request $request) {
        $validation = Validator::make($request->all(), [
                    'id' => 'required'
        ]);
        if ($validation->fails()) {
            echo 0;
        }

        if (count(Input::get('id')) > 0) {
            $order = 1;
            foreach (Input::get('id') as $id) {
                $test = Test::find($id);
                $test->order = $order;
                $test->save();

                $order ++;
            }
        }
    }

    /**
     * Update question sort the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function questionSort(Request $request) {
        $validation = Validator::make($request->all(), [
                    'id' => 'required'
        ]);
        if ($validation->fails()) {
            echo 0;
        }

        if (count(Input::get('id')) > 0) {
            $order = 1;
            foreach (Input::get('id') as $id) {
                $test = Question::find($id);
                $test->order = $order;
                $test->save();

                $order ++;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $t = isset($_GET['t']) ? $_GET['t'] : 1;
        if ($t == 1) {
            DB::table('test')
                    ->where('id', '=', $id)
                    ->delete();
            DB::table('question')
                    ->where('test_id', '=', $id)
                    ->delete();
        } else {
            DB::table('question')
                    ->where('id', '=', $id)
                    ->delete();
        }
    }

}
