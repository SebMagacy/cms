## Status wdrożenia

**Status:** W trakcie implementacji

## Cel

Celem funkcjonalności jest zapewnienie bezpiecznego dostępu do panelu administracyjnego CMS pod adresem `/admin`.

Dostęp do zaplecza mogą uzyskać wyłącznie uwierzytelnieni użytkownicy posiadający uprawnienia administratora. Zwykły użytkownik nie może uzyskać dostępu do chronionej części panelu administracyjnego.

## Założenia

- administrator jest użytkownikiem aplikacji zapisanym w tabeli `users`;
    
- uprawnienia administratora określa pole `is_admin`;
    
- wartość `is_admin = true` oznacza użytkownika posiadającego dostęp administracyjny;
    
- wartość `is_admin = false` oznacza zwykłego użytkownika bez dostępu do zaplecza;
    
- panel administracyjny działa pod prefiksem `/admin`;
    
- mechanizm będzie korzystał z uwierzytelniania Laravel;
    
- logowanie i wylogowanie administratora będą obsługiwane przez dedykowane trasy panelu;
    
- chronione trasy panelu będą wymagały uwierzytelnienia oraz uprawnień administratora.
    

## Implementacja

Funkcjonalność jest wdrażana etapami.

### 1. Utworzenie migracji dodającej pole `is_admin`

Pierwszym etapem jest rozszerzenie tabeli `users` o pole `is_admin`.

Zmiana struktury bazy danych zostanie wykonana za pomocą nowej migracji Laravel, bez modyfikowania istniejącej migracji tworzącej tabelę `users`.

W katalogu głównym projektu należy wykonać:
```shell
php artisan make:migration add_is_admin_to_users_table --table=users
```
Po wykonaniu polecenia Laravel utworzy nowy plik migracji w katalogu `database/migrations`.

Utworzenie migracji przygotowuje zmianę struktury tabeli `users`, ale **nie modyfikuje jeszcze bazy danych**. W kolejnym kroku w pliku migracji zostanie zdefiniowana kolumna `is_admin`, a następnie migracja zostanie uruchomiona, aby zastosować zmianę w bazie danych.

### 2. Definicja migracji

W metodzie `up()`, w miejscu komentarza `//`, należy dodać:

```php
$table->boolean('is_admin')->default(false);
```

Kolumna `is_admin` przechowuje informację, czy użytkownik posiada uprawnienia administratora. Wartość domyślna `false` powoduje, że użytkownik nie otrzymuje uprawnień administratora automatycznie. Po wykonaniu migracji istniejący użytkownicy również będą mieli domyślnie wartość `false`.

W metodzie `down()`, w miejscu komentarza `//`, należy dodać:

```php
$table->dropColumn('is_admin');
```

Metoda `down()` określa sposób wycofania migracji. W tym przypadku wycofanie zmiany spowoduje usunięcie kolumny `is_admin` z tabeli `users`.

Na tym etapie zmiana jest zdefiniowana w pliku migracji, ale nie została jeszcze zastosowana w bazie danych.

### 3. Uruchomienie i sprawdzenie migracji

Przed uruchomieniem migracji sprawdź, czy aplikacja może połączyć się z bazą danych wskazaną w pliku `.env`. Migracja zmieni schemat tej bazy, dlatego w przypadku danych, które chcesz zachować, wykonaj wcześniej kopię zapasową.

W katalogu głównym projektu sprawdź stan migracji:

```shell
php artisan migrate:status
```

Jeżeli migracja `2026_09_08_144144_add_is_admin_to_users_table` nie została jeszcze wykonana, uruchom oczekujące migracje:

```shell
php artisan migrate
```

Następnie ponownie wyświetl ich stan:

```shell
php artisan migrate:status
```

Migracja powinna być oznaczona jako wykonana. Jeżeli polecenie zgłasza błąd połączenia z bazą, najpierw sprawdź ustawienia bazy w `.env` oraz działanie serwera bazy danych. Nie oznaczaj tego etapu jako ukończonego, dopóki migracja nie zakończy się pomyślnie.

Uruchomienie migracji dodaje kolumnę `is_admin` z domyślną wartością `false`. Samo dodanie kolumny nie włącza jeszcze logowania ani nie ogranicza dostępu do `/admin`.

### 4. Przygotowanie modelu `User`

Kolumna `is_admin` jest już widoczna w tabeli `users` z wartością domyślną `0`. W modelu `app/Models/User.php` dodaj do istniejącej tablicy zwracanej przez metodę `casts()` rzutowanie, aby Laravel odczytywał ją jako wartość logiczną:

```php
'is_admin' => 'boolean',
```

Dzięki temu `$user->is_admin` będzie miało wartość `true` albo `false`. Nie dodawaj `is_admin` do listy `#[Fillable(...)]`: zwykły formularz tworzenia lub edycji konta nie powinien móc nadać użytkownikowi uprawnień administratora przez masowe przypisanie danych.

Na tym etapie model potrafi odczytać uprawnienie, ale sam dostęp do panelu nadal nie jest chroniony. W kolejnym kroku trzeba dodać uwierzytelnianie i sprawdzanie uprawnienia dla tras `/admin`.

### 5. Plan tras logowania i ochrony panelu

Przed dodaniem ochrony do panelu przygotuj adresy potrzebne administratorowi do zalogowania się i wylogowania. Trasy w pliku `routes/admin.php` otrzymują już prefiks adresu `/admin` i prefiks nazwy `admin.` z konfiguracji w `bootstrap/app.php`.

| Metoda i adres | Nazwa trasy | Zadanie | Dostęp |
| --- | --- | --- | --- |
| `GET /admin/login` | `admin.login` | Wyświetlenie formularza logowania | Gość |
| `POST /admin/login` | `admin.login.attempt` | Sprawdzenie danych i rozpoczęcie sesji | Gość |
| `POST /admin/logout` | `admin.logout` | Zakończenie sesji | Zalogowany administrator |
| `GET /admin` | `admin.dashboard` | Wyświetlenie panelu | Zalogowany administrator |

Najpierw trzeba uruchomić stronę i obsługę logowania. Dopiero potem należy wymagać uwierzytelnienia przy wejściu na `/admin`; w przeciwnym razie niezalogowany użytkownik nie będzie miał dokąd zostać przekierowany.

Poprawne hasło nie wystarcza do uzyskania dostępu do panelu. Po zalogowaniu aplikacja musi również sprawdzić `is_admin`. Użytkownik z `is_admin = false` nie może wejść na `/admin`, nawet jeśli ma aktywną sesję. Po udanym logowaniu należy odnowić identyfikator sesji, a podczas wylogowania unieważnić sesję. Wylogowanie powinno używać żądania `POST` chronionego tokenem CSRF.

To jest plan kolejnych zmian; samo dopisanie tych adresów do dokumentacji nie tworzy jeszcze tras ani nie zabezpiecza panelu.
