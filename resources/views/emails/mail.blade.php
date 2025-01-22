<!-- resources/views/email_password.blade.php -->
@extends('layouts.admin')

@section('content')
    <h4 class="card-title">Email Addresses and Password Hashes</h4>

    <table>
        <tr>
            <th>Email</th>
            <th>Password Hash</th>
        </tr>
        @foreach ($userData as $user)
            <tr>
                <td>{{ $user['email'] }}</td>
                <td>{{ $user['hashed_password'] }}</td>
            </tr>
        @endforeach
    </table>
@endsection
