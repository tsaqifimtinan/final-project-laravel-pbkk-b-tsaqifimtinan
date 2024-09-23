@extends('layout.base')

@section('title', 'Create User')

@section('content')
<div class="container">
    <h2>Create User</h2>
    <form>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" disabled>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" disabled>
        </div>
    </form>
</div>
@endsection