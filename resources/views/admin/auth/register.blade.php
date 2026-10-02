<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rejestracja — Panel CMS</title>
    <style>
        body { min-height: 100vh; margin: 0; display: grid; place-items: center; background: #f4f6f8; color: #18212b; font-family: system-ui, sans-serif; }
        main { width: min(100% - 2rem, 38rem); padding: 2.5rem; box-sizing: border-box; border: 1px solid #dce2e8; border-radius: 1rem; background: #fff; }
        h1 { margin: 0 0 .75rem; }
        p { color: #52606d; line-height: 1.6; }
        a { color: #174ea6; font-weight: 600; }
        a:focus-visible { outline: 3px solid #f5a623; outline-offset: 3px; }
    </style>
</head>
<body>
    <main>
        <h1>Rejestracja</h1>
        <p>Tworzenie kont nie jest jeszcze dostępne.</p>
        <a href="{{ route('admin.dashboard') }}">Wróć do strony panelu</a>
    </main>
</body>
</html>
