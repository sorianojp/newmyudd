<form action="{{ route('login.perform') }}" method="POST">
    @csrf
    <label>User ID</label>
    <input type="text" name="USER_ID" required>

    <label>Password</label>
    <input type="password" name="PASSWORD" required>

    <button type="submit">Login</button>

    @error('USER_ID')
        <div>{{ $message }}</div>
    @enderror
</form>
