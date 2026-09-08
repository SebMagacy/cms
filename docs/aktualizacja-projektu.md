# Aktualizacja projektu Laravel

Ten dokument opisuje bezpieczną aktualizację całego projektu CMS: kodu z GitHub, zależności PHP, zależności JavaScript, bazy danych i zasobów frontendu.

Procedura jest dopasowana do aktualnej konfiguracji projektu:

- PHP `^8.5.4`;
- Laravel `^13.29`;
- Composer `^2.9.3`;
- Node.js i npm;
- Vite 8;
- testy PHPUnit uruchamiane przez `composer test`;
- Laravel Pint;
- PHPStan/Larastan.

> Nie aktualizuj jednocześnie wersji głównej Laravel, PHP lub innych kluczowych zależności bez wcześniejszego sprawdzenia instrukcji migracji. Zwykłe polecenia z tego dokumentu aktualizują pakiety tylko w granicach określonych w `composer.json` i `package.json`.

## 1. Przejdź do katalogu projektu

```bash
cd /home/sebastian/Development/Laravel/cms
```

Sprawdź, czy jesteś we właściwym katalogu:

```bash
pwd
git remote -v
```

Repozytorium powinno wskazywać na:

```text
https://github.com/SebMagacy/cms.git
```

## 2. Sprawdź stan Git

```bash
git status
git branch --show-current
```

Przed aktualizacją zapisz albo odłóż wszystkie własne zmiany. Nie wykonuj `git pull`, gdy w katalogu roboczym znajdują się niezapisane zmiany, których pochodzenia nie znasz.

Jeżeli zmiany są gotowe, wykonaj commit na swojej gałęzi roboczej. Jeżeli nie są gotowe, przerwij aktualizację albo świadomie odłóż je za pomocą `git stash`.

## 3. Pobierz informacje z GitHuba

```bash
git fetch origin
```

Sprawdź, czy lokalna gałąź nie odbiega od zdalnej:

```bash
git status -sb
```

Jeżeli pracujesz na gałęzi `main` i katalog roboczy jest czysty:

```bash
git pull --ff-only origin main
```

Opcja `--ff-only` zapobiega przypadkowemu utworzeniu commita scalającego.

## 4. Sprawdź środowisko

```bash
php -v
composer --version
node --version
npm --version
php artisan --version
```

PHP musi spełniać wymaganie zapisane w `composer.json`. Przed zmianą wersji PHP albo Node.js sprawdź zgodność wszystkich zależności.

## 5. Włącz tryb konserwacji

Ten krok jest potrzebny na serwerze produkcyjnym. W lokalnym środowisku deweloperskim zazwyczaj można go pominąć.

```bash
php artisan down
```

Po zakończeniu prac aplikację uruchamia polecenie:

```bash
php artisan up
```

## 6. Wykonaj kopię bazy danych

Przed migracjami wykonaj kopię bazy odpowiednią dla używanego silnika. Nie zapisuj hasła do bazy w skrypcie ani dokumentacji.

Przykład dla MySQL/MariaDB:

```bash
mysqldump -u NAZWA_UZYTKOWNIKA -p NAZWA_BAZY > backup-przed-aktualizacja.sql
```

Pliku kopii bazy nie dodawaj do repozytorium Git.

## 7. Sprawdź pakiety Composer

Najpierw sprawdź stan zależności i znane problemy bezpieczeństwa:

```bash
composer validate
composer outdated --direct
composer audit
```

Wyjaśnij każdą większą aktualizację przed jej zastosowaniem. Szczególną ostrożność zachowaj przy Laravel, PHPUnit, PHPStan/Larastan i pakietach wpływających na uruchamianie aplikacji.

## 8. Zaktualizuj zależności PHP

Jeżeli `composer.lock` pochodzi już z aktualnego repozytorium i chcesz tylko odtworzyć zapisane wersje:

```bash
composer install
```

Jeżeli świadomie aktualizujesz pakiety w granicach wersji dozwolonych przez `composer.json`:

```bash
composer update
```

Po aktualizacji sprawdź różnice:

```bash
git diff -- composer.json composer.lock
composer audit
```

Plik `composer.lock` powinien pozostać w repozytorium aplikacji Laravel. Zapewnia instalację tych samych wersji pakietów na innych komputerach i serwerach.

## 9. Sprawdź i zaktualizuj pakiety npm

Sprawdź dostępne aktualizacje:

```bash
npm outdated
npm audit
```

Instalacja wersji zapisanych w `package-lock.json`:

```bash
npm ci
```

Aktualizacja pakietów w granicach określonych w `package.json`:

```bash
npm update
```

Po aktualizacji sprawdź:

```bash
git diff -- package.json package-lock.json
npm audit
```

Nie używaj automatycznie `npm audit fix --force`. Polecenie może wprowadzić niezgodne wersje zależności.

## 10. Wyczyść pamięć podręczną Laravel

```bash
php artisan optimize:clear
```

## 11. Sprawdź migracje

Najpierw wyświetl ich stan:

```bash
php artisan migrate:status
```

Wykonaj oczekujące migracje:

```bash
php artisan migrate
```

Na produkcji, po wykonaniu kopii bazy:

```bash
php artisan migrate --force
```

Nie używaj `migrate:fresh`, `db:wipe` ani podobnych poleceń na bazie zawierającej dane.

## 12. Zbuduj frontend

```bash
npm run build
```

Proces musi zakończyć się bez błędów Vite, JavaScript i CSS.

## 13. Uruchom testy i kontrolę jakości

Testy Laravel:

```bash
composer test
```

Kontrola formatowania PHP bez modyfikowania plików:

```bash
./vendor/bin/pint --test
```

Analiza statyczna:

```bash
./vendor/bin/phpstan analyse
```

Dodatkowe kontrole aplikacji:

```bash
php artisan route:list
php artisan about
```

Jeżeli któreś polecenie zakończy się błędem, nie wysyłaj aktualizacji na GitHuba ani na serwer przed wyjaśnieniem problemu.

## 14. Sprawdź zmiany

```bash
git status
git diff --stat
git diff
```

W typowej aktualizacji zależności mogą zmienić się:

- `composer.lock`;
- `package-lock.json`;
- czasami `composer.json` lub `package.json`, jeżeli wersje zmieniono świadomie;
- dokumentacja wymagana przez daną aktualizację.

Nie dodawaj do Git pliku `.env`, kopii bazy, katalogów `vendor`, `node_modules` ani lokalnych plików tymczasowych.

## 15. Uruchom aplikację po aktualizacji

W środowisku lokalnym:

```bash
composer dev
```

Na produkcji odbuduj pamięć podręczną po zakończeniu wszystkich testów:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

Następnie sprawdź w przeglądarce co najmniej:

- stronę publiczną;
- logowanie;
- panel administracyjny `/admin`;
- formularze zapisujące dane;
- kolejki i zadania cykliczne, jeżeli są używane.

## 16. Zapisz aktualizację w Git

Dopiero po pomyślnym wykonaniu testów przygotuj commit:

```bash
git add composer.lock package-lock.json
git status
git commit -m "Update project dependencies"
```

Dodaj inne pliki tylko wtedy, gdy rzeczywiście należą do aktualizacji. Przed `git push` jeszcze raz sprawdź `git status` i wyniki testów.

## Skrócona procedura rutynowej aktualizacji lokalnej

Poniższej sekwencji używaj tylko wtedy, gdy katalog roboczy jest czysty, a aktualizacja nie obejmuje zmiany głównych wersji PHP, Laravel ani Node.js:

```bash
cd /home/sebastian/Development/Laravel/cms
git status
git fetch origin
git pull --ff-only origin main
composer validate
composer outdated --direct
composer audit
composer update
npm outdated
npm audit
npm update
php artisan optimize:clear
php artisan migrate:status
php artisan migrate
npm run build
composer test
./vendor/bin/pint --test
./vendor/bin/phpstan analyse
git status
git diff --stat
```

## Aktualizacja głównej wersji Laravel

Przejście na nową główną wersję Laravel jest osobnym zadaniem. Przed rozpoczęciem:

1. utwórz osobną gałąź roboczą;
2. wykonaj kopię bazy danych;
3. przeczytaj oficjalny przewodnik aktualizacji Laravel;
4. sprawdź zgodność PHP i wszystkich pakietów Composer;
5. aktualizuj zależności etapami;
6. usuń wszystkie ostrzeżenia i błędy;
7. wykonaj pełne testy oraz ręczną kontrolę aplikacji;
8. dopiero wtedy przygotuj scalenie zmian.

Nie zmieniaj ręcznie ograniczeń wersji w `composer.json`, dopóki zgodność pakietów nie zostanie potwierdzona.

## Wycofanie nieudanej aktualizacji

Jeżeli zmiany nie zostały jeszcze zatwierdzone, najpierw sprawdź dokładnie:

```bash
git status
git diff
```

Nie używaj automatycznie `git reset --hard`. Może usunąć również własne, niezapisane zmiany. Bezpieczny sposób wycofania zależy od tego, które pliki zostały zmienione i czy migracje dotknęły danych.

Jeżeli migracje zostały wykonane, oceń ich metodę `down()` oraz wpływ na dane przed użyciem:

```bash
php artisan migrate:rollback --step=1
```

W razie ryzyka utraty danych przywróć bazę z wykonanej wcześniej kopii i poproś o przegląd zmian przed ponowną próbą aktualizacji.
