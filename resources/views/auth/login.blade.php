<form action="{{ route('login') }}" method="post">
    @csrf
    <input type="email" name="email" autocomplete="new-email">
    <input type="password" name="password" id="password" autocomplete="new-password">
    <button id="login-btn">Login</button>

</form>