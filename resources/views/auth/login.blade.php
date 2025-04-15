@extends('layouts.app')
@section('content')
@guest
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
          <img class="w-8 h-8 mr-2" src="{{ asset('images/logo.png') }}" alt="logo">
          My UdD Portal 
        </a>
        <form action="{{ route('login.perform') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="USER_ID" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="USER_ID" id="USER_ID" placeholder="Username"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div>
                <label for="PASSWORD" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="PASSWORD" id="PASSWORD" placeholder="Password"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-300">
                Sign in
            </button>
            @error('USER_ID')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>
@endguest
@endsection
