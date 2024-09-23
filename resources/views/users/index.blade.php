@extends('layout.base')

@section('title', 'Users')

@section('content')
<div class="container">
    <h1>Users</h1>
    <form method="GET" action="{{ route('users.index') }}">
        <div class="form-group">
            <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }} <!-- Pagination links -->
</div>
@endsection