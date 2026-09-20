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