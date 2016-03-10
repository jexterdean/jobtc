<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;


class TicketController extends BaseController
{

    public function index()
    {

        if (Entrust::hasRole('Admin'))
            $ticket = Ticket::all();
        elseif (Entrust::hasRole('Client'))
            $ticket = Ticket::where('username', '=', Auth::user()->username)->get();
        elseif (Entrust::hasRole('Staff')) {
            $ticket = DB::table('fp_ticket')
                ->join('fp_assigned_user', 'fp_assigned_user.unique_id', '=', 'fp_ticket.ticket_id')
                ->where('belongs_to', '=', 'ticket')
                ->where('fp_assigned_user.username', '=', Auth::user()->username)
                ->get();
        }

        $user_options = User::orderBy('name', 'asc')->lists('name', 'username');

        $assets = ['table', 'datepicker'];

        return View::make('ticket.index', [
            'tickets' => $ticket,
            'users' => $user_options,
            'assets' => $assets
        ]);
    }

    public function show($ticket_id)
    {

        if (Entrust::hasRole('Admin'))
            $ticket = Ticket::find($ticket_id);
        elseif (Entrust::hasRole('Client')) {
            $ticket = Ticket::where('username', '=', Auth::user()->username)
                ->where('ticket_id', '=', $ticket_id)
                ->first();
        } elseif (Entrust::hasRole('Staff')) {
            $ticket = DB::table('fp_ticket')
                ->join('fp_assigned_user', 'fp_assigned_user.unique_id', '=', 'fp_ticket.ticket_id')
                ->where('belongs_to', '=', 'ticket')
                ->where('fp_assigned_user.username', '=', Auth::user()->username)
                ->where('ticket_id', '=', $ticket_id)
                ->first();
        }

        if (!$ticket)
            return Redirect::to('ticket')->withErrors('This is not a valid ticket!!');

        $assignedUser = Assigned_User::where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)
            ->get();

        $assign_username = Assigned_User::where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)
            ->lists('username', 'username');

        $note = Note::where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)
            ->where('username', '=', Auth::user()->username)
            ->first();

        $user_options = User::orderBy('name', 'asc')
            ->lists('name', 'username');

        $comment = DB::table('fp_comment')
            ->where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)
            ->join('fp_user', 'fp_comment.username', '=', 'fp_user.username')
            ->orderBy('fp_comment.created_at', 'desc')
            ->get();

        $attachment = DB::table('fp_attachment')
            ->where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)
            ->join('fp_user', 'fp_attachment.username', '=', 'fp_user.username')
            ->orderBy('fp_attachment.created_at', 'desc')
            ->get();

        if (!Entrust::hasRole('Staff')) {
            $task = Task::where('belongs_to', '=', 'ticket')
                ->where('unique_id', '=', $ticket_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $task = Task::where('belongs_to', '=', 'ticket')
                ->where('unique_id', '=', $ticket_id)
                ->where('assign_username', '=', Auth::user()->username)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return View::make('ticket.show', [
            'ticket' => $ticket,
            'note' => $note,
            'comments' => $comment,
            'attachments' => $attachment,
            'tasks' => $task,
            'users' => $user_options,
            'assign_username' => $assign_username,
            'assignedUsers' => $assignedUser
        ]);
    }

    public function create()
    {
    }

    public function edit()
    {
    }

    public function store()
    {

        $setting = Setting::find(1);

        $filevalidation = 'required|mimes:' . $setting->allowed_upload_file . '
			|max:' . $setting->allowed_upload_max_size;
        $filename = uniqid();

        $validation = Validator::make(Input::all(), [
            'ticket_subject' => 'required',
            'ticket_description' => 'required',
            'ticket_priority' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $ticket = new Ticket;
        $data = Input::all();

        if (Input::hasFile('file')) {
            $extension = Input::file('file')->getClientOriginalExtension();
            $file = Input::file('file')->move('assets/tickets/', $filename . "." . $extension);
            $data['file'] = $filename . "." . $extension;
        }

        $data['username'] = Auth::user()->username;
        $data['ticket_status'] = 'open';
        $ticket->fill($data);
        $ticket->save();

        return Redirect::back()->withSuccess('Successfully saved!!');
    }

    public function updateTicketStatus()
    {

        $ticket = Ticket::find(Input::get('ticket_id'));
        $validation = Validator::make(Input::all(), [
            'ticket_id' => 'required',
            'ticket_status' => 'required|in:open,close'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withErrors($validation->messages());
        } elseif (!$ticket) {
            return Redirect::back()->withErrors('Wrong URL!!');
        }

        $ticket->ticket_status = Input::get('ticket_status');
        $ticket->save();

        return Redirect::back()->withSuccess('Saved!!');
    }

    public function update()
    {
    }

    public function destroy()
    {
    }

    public function delete($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);

        if (!$ticket || ($ticket->username != Auth::user()->username && !Entrust::hasRole('Admin')))
            return Redirect::to('ticket')->withErrors('This is not a valid link!!');

        DB::table('fp_assigned_user')
            ->where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)->delete();

        $attachments = DB::table('fp_attachment')
            ->where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)->get();

        foreach ($attachments as $attachment)
            File::delete('assets/attachment_files/' . $attachment->file);

        DB::table('fp_attachment')
            ->where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)->delete();

        DB::table('fp_comment')
            ->where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)->delete();

        DB::table('fp_notes')
            ->where('belongs_to', '=', 'ticket')
            ->where('unique_id', '=', $ticket_id)->delete();

        $ticket->delete();

        return Redirect::to('ticket')->withSuccess('Delete Successfully!!!');
    }
}

?>