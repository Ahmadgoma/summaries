@extends('layouts.master') @section('content')
<h1 class="title">Tasks</h1>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Task</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
        <tr>
            <th scope="row"><a href="/tasks/{{$task->id}}">{{$task->body}}</a></th>
            <td><a href="#">Edit</a></td>
            <td><a href="#">Delete</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
