<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie administratora</title>
</head>
<body>
    <h1>Logowanie administratora</h1>

    <form method="POST" action="{{ route('admin.login.attempt') }}">
        @csrf

        <label for="email">Adres e-mail</label>
        <input id="email" name="email" type="email"
               value="{{ old('email') }}" required autofocus>
        @error('email')
            <p>{{ $message }}</p>
        @enderror

        <label for="password">Hasło</label>
        <input id="password" name="password" type="password" required>
        @error('password')
            <p>{{ $message }}</p>
        @enderror

        <button type="submit">Zaloguj się</button>
    </form>
</body>
</html>