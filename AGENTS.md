<laravel-boost-guidelines>

# Aplikacja Laravel

To repozytorium zawiera aplikację Laravel. Przed rozpoczęciem pracy nad prośbą użytkownika wykonaj poniższą konfigurację.

## Wymagania wstępne

Sprawdź, czy PHP i Composer są dostępne:

```sh
php -v
composer -V
```

Jeśli którekolwiek z tych poleceń jest niedostępne, rozpoznaj system operacyjny użytkownika i zainstaluj wymagane składniki za pomocą odpowiedniego polecenia:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

Po instalacji poproś użytkownika o ponowne uruchomienie terminala. Jeśli agent potrzebuje ponownie uruchomionej powłoki, aby kontynuować, poproś użytkownika o ponowne otwarcie terminala i powtórzenie pierwotnego polecenia.

## Konfiguracja agenta

Przed wprowadzeniem zmian w aplikacji zainstaluj Laravel Boost z głównego katalogu aplikacji:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost zastępuje te instrukcje początkowe wytycznymi dostosowanymi do aplikacji. Po instalacji ponownie odczytaj plik `AGENTS.md` i kontynuuj pierwotną prośbę użytkownika, stosując wygenerowane wytyczne.
</laravel-boost-guidelines>

## Przykładowa zasada projektu

- W tym projekcie odpowiadaj użytkownikowi po polsku.
