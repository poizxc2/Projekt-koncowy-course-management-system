# Course-management-system

## Uruchomienie projektu, migracja i tworzenie danych testowych

    docker-compose up --build

# Lista uczestników w projekcie

1. Uladzislau Chukh
2. Vladyslav Shtepan
3. Kiryl Shakhnovich


# Lista contenerów 

1. <b>app</b>  port: 8000 (swagger doc: /api/doc) 
2. <b>database</b> port: 3306 (user: user, password: password)
3. <b>phpmyadmin</b>  port: 8080  (server: database, user: user, password: password)

## Swagger

http://localhost:8000/api/doc

## Dane testowe

#### Professor (user)
{
    "username": "professor@app.dev",
    "password": "password"
}

#### Students (user)
{
    "username": "student1@app.dev",
    "password": "password"
}

{
    "username": "student2@app.dev",
    "password": "password"
}

{
    "username": "student3@app.dev",
    "password": "password"
}

## Opis funcjalności

### Auth

[POST] <b>/api/users/token/login</b> *(Pozwala użytkownikowi zalogować się i uzyskać access oraz refresh tokeny)*

[POST] <b>/api/users/token/refresh</b> *(Pozwala odświeżyć access oraz refresh tokeny)*

[POST] <b>/api/users/token/invalidate</b> *(Pozwala dezaktywować refresh token)*

---

### Users

[POST] <b>/api/users/register</b> *(Pozwala utworzyć nowego użytkownika z rolą studenta)*

[DELETE] (roles: PROFESSOR) <b>/api/users/delete</b> *(Usuwa wszystkich istniejących studentów z kursu)*

---

### Db-Info

[POST] (roles: PROFESSOR, STUDENT) <b>/api/db-info</b>  *(Pozwala utworzyć nową bazę danych do wyboru przez studentów)*

[GET] (roles: PROFESSOR, STUDENT) <b>/api/db-info</b> *(Zwraca listę wszystkich dostępnych baz danych)*

[GET] (roles: PROFESSOR, STUDENT) <b>/api/db-info/{ID}</b> *(Zwraca pełne informacje na temat bazy danych)*

[DELETE] (roles: PROFESSOR) <b>/api/db-info/{ID}</b> *(Usuwa bazę danych z listy dostępnych do wyboru)*

---

### Course-groups

[POST] (roles: PROFESSOR, STUDENT) <b>/api/course-group</b> *(Tworzy nową grupę)*

[GET] (roles: PROFESSOR, STUDENT) <b>/api/course-group</b> *(Zwraca listę wszystkich grup)*

[PUT] (roles: STUDENT) <b>/api/course-group/add/{groupId}</b> *(Pozwala studentowi dołączyć do grupy (maksymalnie 3 osoby w grupie))*

[PUT] (roles: STUDENT) <b>/api/course-group/remove/{groupId}</b> *(Pozwala studentowi usunąć się z grupy)*


---

### Course-participants (Tworzy się automatycznie przy utworzeniu użytkownika-studenta)

[PUT] (roles: STUDENT) <b>/api/course-participant</b> *(Pozwala wprowadzić dodatkowe informacje o uczestniku kursu)*

[PUT] (roles: PROFESSOR) <b>/api/course-participant/grade</b> *(Pozwala prowadzącemu ocemić stuednta)*

[GET] (roles: PROFESSOR, STUDENT) <b>/api/course-participant</b> *(Zwraca listę wszystkich uczestników kursu)*

[GET] (roles: PROFESSOR, STUDENT) <b>/api/course-participant/id</b> *(Zwraca pełne informacje o uczestniku kursu)*
