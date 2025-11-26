@extends('layouts.app')

@section('content')
<div>
    @foreach($posts as $post)
    <div>
        <h3>{{ $post->title }}</h3>
        <p>by {{ $post->ownerUser->username }} - {{ $post->created_at }} </p>
    </div>
    @endforeach
</div>
