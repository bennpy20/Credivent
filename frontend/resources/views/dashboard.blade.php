@if (session()->has('user'))
    <p>Halo, {{ session('user.name') }}</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
@else
    <a href="{{ route('login') }}">Login</a>
@endif