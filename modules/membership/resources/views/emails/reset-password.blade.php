@extends('membership::emails.layout')

@section('title', 'Reset Password')

@section('content')
<h1>Your new password</h1>
<p style="font-size: 28px;">{{ $user->new_password ?? '-' }}</p>
<br />
@endsection