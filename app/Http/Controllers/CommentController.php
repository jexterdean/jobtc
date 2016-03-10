<?php
Class CommentController extends BaseController{

	public function index(){
	}

	public function show(){
	}

	public function create(){
	}

	public function edit(){
	}

	public function store(){

		$validation = Validator::make(Input::all(),['comment'=>'required']);

		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		}

		$comment = new Comment;
	    $data = Input::all();
	    $data['username'] = Auth::user()->username;
	    $comment->fill($data);
	    $comment->save();
	    
	    return Redirect::back()->withSuccess('Successfully saved!!');
	}

	public function update(){
	}

	public function destroy($comment_id){
		$comment = Comment::find($comment_id);

		if(!$comment || ($comment->username != Auth::user()->username && !Entrust::hasRole('Admin')))
			return Redirect::back()->withErrors('This is not a valid link!!');

		$comment->delete($comment_id);
		return Redirect::back()->withSuccess('Deleted successfully!!');
	}
}
?>