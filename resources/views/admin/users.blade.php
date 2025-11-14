@extends('layouts.admin')

@section('content')
<div class="users-section">
    <h1>User List</h1>

    <table class="users-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Telp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div class="user-info">
                        <div class="user-avatar"></div>
                        {{ $user->username }}
                    </div>
                </td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
.users-section {
    padding: 20px;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    margin-top: 20px;
    overflow: hidden;
}

.users-table th,
.users-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.users-table th {
    background-color: #F5F1EB;
    font-weight: 600;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #E6DFD5;
}
</style>
@endsection