# PHP 게시판
<img width="1389" alt="스크린샷 2025-05-24 오후 4 53 32" src="https://github.com/user-attachments/assets/b60a679f-2086-4ba3-8556-342f8c2de340" />

### MySQL 설정

아래의 명령을 통해 mysql에 접속합니다.
```bash
sudo mysql
```
이후 게시판에서 사용할 계정을 생성합니다. (php 코드에 아래의 계정 정보를 설정해두었기 때문에 계정의 정보를 수정하려면 php 코드의 내용도 수정해야 합니다.)
```sql
CREATE USER 'test'@'localhost' IDENTIFIED BY '1234';
```
```sql
GRANT ALL PRIVILEGES ON *.* TO 'test'@'localhost' WITH GRANT OPTION;
```
```sql
FLUSH PRIVILEGES;
```

아래의 명령을 통해 데이터베이스를 생성합니다.
```sql
CREATE DATABASE DB;
```
생성 후 아래 명령어를 통해 작업을 진행할 데이터베이스로 지정합니다.
```sql
USE DB;
```

이후 회원정보를 저장할 member 테이블과 게시판 글을 저장할 board 테이블을 만들어줍니다.
```sql
CREATE TABLE member(
    idx INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userid VARCHAR(100) NOT NULL,
    userpw VARCHAR(100) NOT NULL,
    userphone VARCHAR(300),
    useremail VARCHAR(300),
    username VARCHAR(300)
);
```
```sql
CREATE TABLE board(
    idx INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userid VARCHAR(100),
    title VARCHAR(100),
    content TEXT,
    date DATE,
    hit INT(11),
    file_name VARCHAR(300)
);
```

### apache 적용

아래의 명령어를 통해 코드 git clone을 진행합니다.

```bash
sudo git clone https://github.com/OCSF-Logrrr/Linux.git
```

이후 Board1 디렉토리 안에 존재하는 파일들을 `var/www/html/apache2` 경로에 위치하도록 합니다.

<img width="500" alt="스크린샷 2025-05-24 오후 4 37 50" src="https://github.com/user-attachments/assets/4b88c575-b2cb-4936-a7b5-f5cc40a4cb8e" />

이후 웹 사이트에 접속하면 게시판 이용이 가능합니다.

> git clone 후 files라는 디렉토리를 생성해주어야 파일 업로드 진행이 가능합니다.
