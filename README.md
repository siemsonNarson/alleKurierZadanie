## Aplikacja rekrutacyjna
Aplikacja jest małym systemem pozwalającym dodawać faktury (Invoice) do kontrahentów (User), tworzyć nowych kontarhentów (User) a także pobierać listę nieaktywnych kontrachentów czy listę faktur o odpowiednich parametrach. System jest w początkowej fazie rozwoju i pozwala na uruchomienie poniższych poleceń z poziomu CLI.
```
# tworzenie nowego kontrachenta (User)
bin/console app:user:create user@example.com

# wyszukiwanie listy nieaktywnych kontrachentów (User) - jako wynik podana jest lista adresów email
bin/console app:user:get:inactive

# tworzenie faktury dla użytkownika user@example.com na kwotę 125,00 zł
bin/console app:invoice:create user@example.com 12500

# pobieranie identyfiaktorów faktur, które mają status "new" i ich kwota jest większa od 100,00 zł - jako wynik podawana jest lista faktur (numer id)
bin/console app:invoice:get-by-status-and-amount new 10000
```
### Istniejące założenia biznesowe

- Kwota faktury musi być większa od 0
- Kwoty zapisywane są w groszach
- Stworzenie nowego kontrahenta jest możliwe jeśli podany e-mail nie jest przypisany do innego kontrahenta (User)
- Nowy kontrahent ma status nieaktywny
- Po utworzeniu kontrahenta wysyłany (\App\Common\Mailer\MailerInterface) jest komunikat do użytkownika: "Zarejestrowano konto w systemie. Aktywacja konta trwa do 24h" 
- Dla nieaktywnych kontrahentów nie ma możliwości stworzenia faktury
- Istniejące statusy faktur (wykorzystywane również do wyszukiwania): new, paid, canceled

## Uruchomienie aplikacji

```
1. sklonowanie projekt z gitHub
git clone https://github.com/siemsonNarson/alleKurierZadanie

2. zainstalowanie Dockera na swoim urządzeniu
https://docs.docker.com/get-docker/

3. skonfigurowanie dostępu Dockera do katalogu root projektu (Settings --> Resources --> File Sharing)

4. skopiowanie pliku .env.local do pliku .env oraz nadanie praw odczytu
cp env.local .env
chmod 664 .env

5. uruchomienie komendy Composer w katalogu root projektu
composer install

6. zbudowanie obrazu dockerowego (po uruchomieniu Docker Desktop)
docker-compose up -d

7. po wejściu do powłoki bash kontenera (patrz konfiguracja Aplikacji poniżej) wykonanie migracji
bin/console doctrine:migrations:migrate

```
Aplikacja posiada konfigurację obrazów dockerowych
```
# zbudowanie obrazu i uruchomienie kontenera aplikacji
docker-compose up -d

# lista uruchomionych kontenerów, na liście jest CONTAINER ID
docker ps

# wejście do bash kontenera 
docker exec -it {CONTAINER ID} bash
```

### Testy
```
bin/phpunit tests/Unit/
```