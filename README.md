# Laboration 2 - Introduktion till PHP

## Publicerad webbplats
https://wordpress-labb2.onrender.com/

## Om projektet
Detta projekt är en webbapplikation som skapats i kursen Webbutveckling med PHP. Syftet med laborationen är att lära sig grunderna i PHP, objektorienterad programmering och databashantering med MySQL.

Webbplatsen fungerar som en personlig bucketlist där användaren kan lägga till och ta bort saker som den vill göra i livet.

## Webbplatsens sidor

Webbplatsen består av tre sidor:

- **Startsida:** Information om laborationen och mina reflektioner kring PHP.
- **Bucketlist:** Här kan användaren lägga till, visa och ta bort aktiviteter.
- **AI:** Information om hur AI har använts under arbetet och mina reflektioner kring detta.

## Funktioner

- Lägga till aktiviteter i bucketlistan.
- Ange namn, beskrivning och prioritet.
- Visa alla sparade aktiviteter.
- Ta bort aktiviteter.
- Spara information i en MySQL-databas.
- Validering av formulär.
- Responsiv design för olika skärmstorlekar.

## Tekniker

Projektet är utvecklat med:

- PHP
- MySQL
- PDO
- HTML
- CSS
- Objektorienterad programmering (OOP)
- Git och GitHub
- XAMPP
- Render
- Enzonic Databases

## Databas

Databasen innehåller tabellen `bucketlist_items` med följande kolumner:

| Kolumn | Beskrivning |
|---|---|
| id | Unikt ID för varje aktivitet |
| name | Aktivitetens namn |
| description | Beskrivning av aktiviteten |
| priority | Prioritet mellan 1 och 5 |
| created_at | Datum och tid då aktiviteten skapades |

Databasen hanteras med MySQL och PHP använder PDO för att kommunicera med databasen.

## Installation lokalt

För att köra projektet lokalt behövs XAMPP eller en liknande lokal PHP- och MySQL-miljö.

1. Klona repositoryt från GitHub.
2. Placera projektmappen i XAMPP:s `htdocs`-katalog.
3. Starta Apache och MySQL i XAMPP.
4. Öppna phpMyAdmin.
5. Skapa en databas med namnet `bucketlist`.
6. Importera SQL-filen `database/bucketlist.sql`.
7. Kontrollera databasinställningarna i `classes/Database.php`.
8. Öppna webbplatsen i webbläsaren:

   `http://localhost/Wordpress-labb2/`

## Projektstruktur

- `index.php` – Startsida
- `bucketlist.php` – Bucketlist med formulär och aktiviteter
- `ai.php` – Information och reflektioner om AI
- `classes/` – PHP-klasser för databas och bucketlist
- `config/` – Konfigurationsfil
- `css/` – Webbplatsens CSS
- `database/` – SQL-filer för databasen
- `diagrams/` – ER-diagram och UML-diagram

## Reflektion

Under arbetet med laborationen har jag lärt mig mer om PHP och hur PHP kan användas tillsammans med en databas. Jag har fått förståelse för objektorienterad programmering, klasser, metoder och hur PDO används för att kommunicera med MySQL.

Jag har även lärt mig att validera formulär, hantera data från användare och skapa funktioner för att lägga till och ta bort information från en databas.

Arbetet har gett mig en bättre förståelse för hur en dynamisk webbplats byggs upp och hur olika delar samverkar.
