<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel CMS</title>
    <style>
        body { min-height: 100vh; margin: 0; display: grid; place-items: center; background: #f4f6f8; color: #18212b; font-family: system-ui, sans-serif; }
        main { width: min(100% - 2rem, 38rem); padding: 2.5rem; box-sizing: border-box; border: 1px solid #dce2e8; border-radius: 1rem; background: #fff; box-shadow: 0 1rem 3rem #18212b0d; }
        h1 { margin: 0 0 .75rem; font-size: clamp(1.75rem, 5vw, 2.5rem); }
        p { margin: 0; color: #52606d; line-height: 1.6; }
        nav { display: flex; flex-wrap: wrap; gap: .75rem; margin-top: 2rem; }
        a { display: inline-block; padding: .8rem 1.1rem; border-radius: .6rem; font-weight: 600; text-decoration: none; }
        .primary { background: #174ea6; color: #fff; }
        .secondary { border: 1px solid #aab8c7; color: #174ea6; }
        a:focus-visible { outline: 3px solid #f5a623; outline-offset: 3px; }
    </style>
</head>
<body>
    <main>
        <h1>Panel CMS</h1>
        <p>Wybierz, czy chcesz zalogować się na istniejące konto, czy przejść do rejestracji.</p>
        <nav aria-label="Dostęp do konta">
            <a class="primary" href="{{ route('admin.login') }}">Zaloguj się</a>
            <a class="secondary" href="{{ route('admin.register') }}">Zarejestruj się</a>
        </nav>
    </main>
</body>
</html>
