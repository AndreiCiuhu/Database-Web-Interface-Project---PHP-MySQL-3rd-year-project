# Interfață Web pentru Gestiunea Studenților și Cursurilor

## Descriere
Proiectul reprezintă o aplicație web implementată în PHP folosind XAMPP, destinată gestionării eficiente a informațiilor legate de studenți, cursuri și înscrieri. Această aplicație permite utilizatorilor să efectueze operații CRUD (Creare, Citire, Actualizare, Ștergere) asupra unei baze de date relaționale MySQL.

## Funcționalități principale
- Vizualizare, adăugare, editare și ștergere informații despre studenți, cursuri și înscrieri
- Gestionarea relațiilor complexe (M:N) între studenți și cursuri
- Validarea datelor introduse pentru menținerea integrității bazei de date
- Gestionarea erorilor și redirecționarea către pagini specifice în caz de probleme

## Tehnologii utilizate
- PHP (back-end)
- HTML și CSS cu Bootstrap (front-end și design)
- MySQL (baza de date)
- XAMPP (server Apache și MySQL)

## Structura bazei de date
- **students:** ID, prenume, nume, an curent, serie, număr grupă
- **courses:** ID curs, nume curs, credite, tip curs
- **enrollments:** ID student, ID curs, data înscrierii, notă

## Implementare
Aplicația utilizează PHP împreună cu XAMPP pentru interacțiunea dintre utilizatori și baza de date MySQL. Interfața este realizată cu HTML și CSS (Bootstrap), oferind o navigare intuitivă și un design responsiv și modern.

## Pași principali ai implementării
1. Crearea și structurarea bazei de date în MySQL utilizând scriptul `edu_track.sql`
2. Dezvoltarea aplicației web PHP utilizând XAMPP pentru gestionarea conexiunii la baza de date
3. Implementarea operațiilor CRUD și a interfețelor web folosind HTML și Bootstrap
4. Validarea datelor introduse și gestionarea corespunzătoare a erorilor

---

Web Interface for Student and Course Management

## Description
This project is a web-based application developed to efficiently manage student, course, and enrollment data using PHP, XAMPP, and a relational MySQL database. It provides a user-friendly interface for CRUD operations.

## Main Features
- Viewing, adding, editing, and deleting student, course, and enrollment records
- Handling complex M:N relationships between students and courses
- Input validation for data integrity
- Error handling and user-friendly error messages

## Technologies Used
- PHP (back-end)
- HTML and CSS with Bootstrap (front-end design)
- MySQL Workbench (database management)
- XAMPP (web server and database connection)

## Implementation
The web application uses PHP with XAMPP for backend interactions with the MySQL database. HTML and Bootstrap CSS ensure intuitive and responsive design.

## Main Implementation Steps
1. Database creation and structure definition in MySQL (`edu_track.sql`)
2. Web application development in PHP using XAMPP for database connectivity
3. Web interfaces and CRUD functionalities using HTML and Bootstrap
4. Implementing data validation and error handling for user inputs

