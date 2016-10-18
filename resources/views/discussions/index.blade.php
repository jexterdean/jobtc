@extends('layouts.default')
@section('content')
<?php
/*
 * Discussions index page
 */
?>
<div class="container">
    <button class='btn btn-primary create-room'>Create Discussion Room</button>
    <h2>Discussions</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Room</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @forelse($discussions as $discussion)
            <tr>
                <td>{{$discussion->room_name}}</td>
                <td><a target="_blank" href="{{url('/discussions/'.$discussion->id)}}" class='btn btn-success'>Join</a></td>
            </tr>
            @empty
            <tr>
                <td>No Rooms Available</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@stop