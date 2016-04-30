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

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QuizController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'assets' => ['input-mask', 'waiting'],
            'page' => 'add'
        ];
        $this->setData($data);

        return View::make('quiz.default', $data);
    }

    private function setData(&$data){
        $test = DB::table('test')
            ->select(DB::raw('
                fp_test.*,
                (
                    SELECT count(fp_question.id)
                    FROM fp_question
                    WHERE
                        fp_question.test_id = fp_test.id
                ) as num_question
            '))
            ->get();
        $data['test'] = $test;

        $t = DB::table('question_type')
            ->select('id', 'type')
            ->get();
        $question_type = array_pluck($t, 'type', 'id');
        $data['question_type'] = $question_type;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'start_message' => 'required',
            'completion_message' => 'required',
            'question_type_id' => 'required',
            'question' => 'required',
            'length' => 'required'
        ]);
        if ($validation->fails()) {
            return Redirect::to('quiz')
                ->withInput()
                ->withErrors($validation->messages());
        }

        DB::beginTransaction();

        try {
            $test = new Test();
            $test->author_id = Auth::user()->user_id;
            $test->title = Input::get('title');
            $test->description = Input::get('description');
            $test->start_message = Input::get('start_message');
            $test->completion_message = Input::get('completion_message');
            $test->save();

            if (Input::file('test_photo')) {
                $photo_dir = public_path() . '/assets/img/test/';
                if(!is_dir($photo_dir)){
                    mkdir($photo_dir, 0777, TRUE);
                }

                $extension = Input::file('test_photo')->getClientOriginalExtension();
                $fileName = $test->id . "." . $extension;
                Input::file('test_photo')->move($photo_dir, $fileName);

                DB::table('test')
                    ->where('id', '=', $test->id)
                    ->update(['test_photo' => $fileName]);
            }
            $t = Input::get('question_type_id');
            $q = Input::get('question');
            $c = Input::has('question_choices') ? Input::get('question_choices') : array();
            $a = Input::has('question_answer') ? Input::get('question_answer') : array();
            $l = Input::get('length');
            $p = Input::get('points');
            if (count($t) > 0) {
                $order = 1;
                foreach ($t as $k => $i) {
                    $question = new Question();
                    $question->test_id = $test->id;
                    $question->question_type_id = $i;
                    $question->question = array_key_exists($k, $q) ? $q[$k] : '';
                    $question->question_choices = array_key_exists($k, $c) ? json_encode($c[$k]) : '[]';
                    $question->question_answer = array_key_exists($k, $a) ? $a[$k] : '';
                    $question->length = array_key_exists($k, $l) ? '00:' . $l[$k] : '';
                    $question->points = array_key_exists($k, $p) ? $p[$k] : 1;
                    $question->order = $order;
                    $question->save();

                    if (Input::file('question_photo_' . $k)) {
                        $photo_dir = public_path() . '/assets/img/question/';
                        if(!is_dir($photo_dir)){
                            mkdir($photo_dir, 0777, TRUE);
                        }

                        $extension = Input::file('question_photo_' . $k)->getClientOriginalExtension();
                        $fileName = $question->id . "." . $extension;
                        Input::file('question_photo_' . $k)->move($photo_dir, $fileName);

                        DB::table('question')
                            ->where('id', '=', $question->id)
                            ->update(['question_photo' => $fileName]);
                    }

                    $order++;
                }
            }

            DB::commit();

            return Redirect::to('quiz/' . $test->id)
                ->withSuccess("Test added successfully!");
        }
        catch (\Exception $e) {
            DB::rollback();

            return Redirect::to('quiz')
                ->withErrors("Test failure when adding!");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [
            'assets' => [],
            'page' => 'view'
        ];
        $this->setData($data);

        $tests_info = DB::table('test')
            ->where('id', '=', $id)
            ->first();
        $questions_info = DB::table('question')
            ->where('test_id', '=', $id)
            ->orderBy('order', 'ASC')
            ->get();
        if(count($questions_info) > 0){
            foreach($questions_info as $v){
                $v->question_choices = json_decode($v->question_choices);
            }
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
    public function edit($id)
    {
        $data = [
            'assets' => ['input-mask', 'waiting'],
            'page' => 'edit'
        ];
        $this->setData($data);

        $tests_info = DB::table('test')
            ->where('id', '=', $id)
            ->first();
        $questions_info = DB::table('question')
            ->where('test_id', '=', $id)
            ->orderBy('order', 'ASC')
            ->get();
        $data['tests_info'] = $tests_info;
        $data['questions_info'] = $questions_info;

        return View::make('quiz.default', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'start_message' => 'required',
            'completion_message' => 'required',
            'question_type_id' => 'required',
            'question' => 'required',
            'length' => 'required'
        ]);
        if ($validation->fails()) {
            return Redirect::to('quiz')
                ->withInput()
                ->withErrors($validation->messages());
        }

        DB::beginTransaction();

        try {
            $test = Test::find($id);
            $test->author_id = Auth::user()->user_id;
            $test->title = Input::get('title');
            $test->description = Input::get('description');
            $test->start_message = Input::get('start_message');
            $test->completion_message = Input::get('completion_message');
            $test->save();

            if (Input::file('test_photo')) {
                $photo_dir = public_path() . '/assets/img/test/';
                if(!is_dir($photo_dir)){
                    mkdir($photo_dir, 0777, TRUE);
                }

                $extension = Input::file('test_photo')->getClientOriginalExtension();
                $fileName = $test->id . "." . $extension;
                Input::file('test_photo')->move($photo_dir, $fileName);

                DB::table('test')
                    ->where('id', '=', $test->id)
                    ->update(['test_photo' => $fileName]);
            }

            $t = Input::get('question_type_id');
            $q = Input::get('question');
            $c = Input::has('question_choices') ? Input::get('question_choices') : array();
            $a = Input::has('question_answer') ? Input::get('question_answer') : array();
            $l = Input::get('length');
            $p = Input::get('points');

            //region Delete Question that has been remove
            $qIds = array_filter(array_keys($t), function ($id) {
                $isEdit = substr($id, 0, 2) === 'e_';
                return $isEdit ? $id : false;
            });
            $qIds = array_map(function ($id) {
                return preg_replace('/(e_)/', "", $id);
            }, $qIds);
            if (count($qIds) > 0) {
                DB::table('question')
                    ->where('test_id', '=', $id)
                    ->whereNotIn('id', $qIds)
                    ->delete();
            }
            //endregion

            if (count($t) > 0) {
                $order = 1;
                foreach ($t as $k => $i) {
                    $isEdit = substr($k, 0, 2) === 'e_';
                    $qId = preg_replace('/(e_)/', "", $k);
                    //if ID has e_ tag it mean that it is an EDIT
                    $question = $isEdit ? Question::find($qId) : new Question();
                    $question->test_id = $id;
                    $question->question_type_id = $i;
                    $question->question = array_key_exists($k, $q) ? $q[$k] : '';
                    $question->question_choices = array_key_exists($k, $c) ? json_encode($c[$k]) : '[]';
                    $question->question_answer = array_key_exists($k, $a) ? $a[$k] : '';
                    $question->length = array_key_exists($k, $l) ? '00:' . $l[$k] : '';
                    $question->points = array_key_exists($k, $p) ? $p[$k] : 1;
                    $question->order = $order;
                    $question->save();

                    $file_key = 'question_photo_' . $k;
                    if (Input::file($file_key)) {
                        $photo_dir = public_path() . '/assets/img/question/';
                        if(!is_dir($photo_dir)){
                            mkdir($photo_dir, 0777, TRUE);
                        }

                        $extension = Input::file($file_key)->getClientOriginalExtension();
                        $fileName = $question->id . "." . $extension;
                        Input::file($file_key)->move($photo_dir, $fileName);

                        DB::table('question')
                            ->where('id', '=', $question->id)
                            ->update(['question_photo' => $fileName]);
                    }

                    $order++;
                }
            }

            DB::commit();

            return Redirect::to('quiz/' . $id)
                ->withSuccess("Test updated successfully!");
        }
        catch (\Exception $e) {
            DB::rollback();

            return Redirect::to('quiz/' . $id)
                ->withErrors("Test failure when adding!");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('test')
            ->where('id', '=', $id)
            ->delete();
        DB::table('question')
            ->where('test_id', '=', $id)
            ->delete();
    }
}
