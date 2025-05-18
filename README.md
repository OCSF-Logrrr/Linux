# Apache/PHP/MySQL (계속 작성 및 수정 중..)

# apache 로그 위치

apache 로그는 기본적으로 error.log와 access.log 2가지가 존재한다.

- error.log : 웹 서버에서 발생하는 오류에 대한 정보가 기록되는 파일
- access.log : 웹 서버에 접속한 클라이언트의 IP 주소, 접속 시간, 요청한 페이지 등 모든 HTTP 요청에 대한 정보가 기록되는 파일

파일 위치는 우분투 기준으로 `/var/log/apache2`에 두 파일 모두 존재


# 환경 구축

우분투 24.04에서 환경 구축을 진행하였으며, 웹 사이트는 개인 실습용으로 제작했던 게시판을 사용하였다. ⇒ Debian 계열의 경우 /apache2 경로에 로그가 저장되지만, RHEL, CentOS 계열의 경우 /httpd 경로에 로그가 저장된다.

📌***실습을 하다보니 개인용 게시판보다 DVWA 등의 대응 방안이 완전히 적용된 코드와 아닌 코드를 모두 편리하게 실행 가능한 환경에서 해보는게 좋다고 느꼈다.. 때문에 XSS 이후 실습에서는 DVWA를 사용해볼 생각이다.***

## apache 설치

apache 설치

```bash
sudo apt install apache2
```

![apache_install](https://github.com/user-attachments/assets/3c913e3d-1155-4e2d-9d8e-37fa5cdb2f24)


apache 서비스 시작 후

```bash
sudo systemctl start apache2
```

데몬(서비스) 상태 확인

```bash
sudo systemctl status apache2
```

![status_apache](https://github.com/user-attachments/assets/c4608506-574d-4021-a2a1-a34a3a9d647d)


localhost에 접속 시 apache 화면이 나오는 것 확인 가능

![localhost](https://github.com/user-attachments/assets/fbcd7a76-48fd-4b20-9a3c-e87e23434e58)


## 로그 위치 및 내용 확인

아래와 같이 /var/log/apache2 경로에 error.log와 access.log가 존재하는 것을 확인

![apache_log](https://github.com/user-attachments/assets/13dee09f-f1cd-4a66-b291-c759636ffeda)


### access.log

![access log_apache](https://github.com/user-attachments/assets/4f8e1021-0da3-4824-b275-83d81e8401aa)


현재 로그를 살펴보면

```bash
127.0.0.1 - - [16/May/2025:23:45:38 +0900] "GET / HTTP/1.1" 200 3460 "-" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0"
127.0.0.1 - - [16/May/2025:23:45:38 +0900] "GET /icons/ubuntu-logo.png HTTP/1.1" 200 3607 "http://localhost/" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0"
127.0.0.1 - - [16/May/2025:23:45:38 +0900] "GET /favicon.ico HTTP/1.1" 404 487 "http://localhost/" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0"
```

get 요청으로 기본 웹 루트인 /var/www/html/index.html에 접속한 기록과 해당 웹 페이지에서 사용되는 /icons/ubuntu-logo.png 이미지도 정상적으로 제공되었다는 로그가 찍혀있다.

마지막 로그는 브라우저 탭에 표시되는 로고가 없어서 404로 뜨는 것이다.

### error.log

![error log_apache](https://github.com/user-attachments/assets/35448b48-a870-469d-a052-5e7bfe681d29)


해당 로그도 살펴보면

```bash
[Fri May 16 23:37:53.610540 2025] [mpm_event:notice] [pid 474085:tid 259233596645408] AH00489: Apache/2.4.58 (Ubuntu) configured -- resuming normal operations
[Fri May 16 23:37:53.610585 2025] [core:notice] [pid 474085:tid 259233596645408] AH00094: Command line: '/usr/sbin/apache2'
```

apache가 정상 작동을 한다는 것과 apache가 실행되었다는 것을 나타내준다.

## 웹 사이트 구성

PHP와 MySQL을 통해 만든 개인 실습용 게시판을 apache에 옮기기 위해 환경 구성을 해주었다.

mysql 설치

```bash
sudo apt install mysql-server
```

<img width="1578" alt="mysql_install" src="https://github.com/user-attachments/assets/80c2b6c0-60bd-4301-9077-d8d0bda4ebde" />

php 설치

```bash
sudo apt install php
```

<img width="1578" alt="php_install" src="https://github.com/user-attachments/assets/ba4a4ec4-2c1b-4176-bce9-d7023522173f" />


php에서 mysql과 apache를 사용할 수 있도록 모듈 설치

```bash
sudo apt install php-mysql
```

<img width="1578" alt="php-mysql" src="https://github.com/user-attachments/assets/2716a23e-6e37-47e4-8fc3-93943f1b7a1e" />


```bash
sudo apt install libapache2-mod-php
```

<img width="1578" alt="php-apache" src="https://github.com/user-attachments/assets/ecd7c4fb-a952-49c4-bb4c-821372b33052" />


### PHP 및 MySQL 로깅 설정

아래의 경로에서 PHP 로깅 설정 (버전은 8.3으로 되어있기에 어떤 버전을 설치하는지에 따라 다른 명령어 사용)

```bash
sudo vi /etc/php/8.3/apache2/php.ini
```

![php_error log](https://github.com/user-attachments/assets/5e729302-0991-4107-9484-b9879801fb53)


위 부분에 명시된 경로 및 파일에 PHP 에러 로그가 기록되는데,, 여기서 문제가 있다면, 사용자가 어떤 경로를 명시하는 지에 따라서 로그 수집기로 수집할 때 각 환경에 맞는 경로로 사용해야하는 문제가 발생한다고 한다..

⇒ 위 사진 부분의 `error_log =` 다음에 경로나 파일을 따로 명시하지 않으면 apache의 error.log 로 들어간다고 하긴 하는데… ⇒ 이럼 통합은 되겠지만 apache에러랑 php 에러가 섞여서 나누기 힘들지 않을까..? 싶음….

일단 `/var/log/php/php_error.log` 로 설정 해둠..!! (php 디렉토리 만들고 권한 줘야함!!)

![php-php_error log](https://github.com/user-attachments/assets/18c672e9-f32b-46d5-9a97-f5bf9aebf5c2)


아래와 같이 에러를 로그파일에 기록하는 설정도 On으로 해주었다.

![php-log_errors](https://github.com/user-attachments/assets/8da31eff-899b-4c2a-b52a-b7f981a0dfdd)


설정이 끝나고 apache를 재시작 해주면 적용된다. (아직 php를 실행시키지 않아 로그는 아래에서 확인할 예정)

이제 MySQL 로그 파일은 아래와 같다.

- Error log : mysqld를 시작, 구동 또는 종료할 때 발생하는 에러 로그
- General log : established된 클라이언트 접속 및 클라이언트로부터 받는 명령문
- Slow query log : long_query_time 시간보다 오래 실행되는 쿼리 + 인덱스 미사용 쿼리
- Binary log : 데이터를 변경시키는 모든 명령문 (replica 포함)

이 중에서 error log와 general log에 대한 로그만 수집해보려 한다.

error log는 별도의 설정을 해두지 않았다면 `/var/log/mysql/error.log`에 저장이 된다고 한다.

⇒ 만약 경로를 지정하고 싶은 경우 `/etc/mysql/my.cnf`에서 지정이 가능하다.

아무 설정 없다면 실제로 아래와 같이 `/var/log/mysql/error.log` 가 존재한다는 것을 확인했다.

<img width="1578" alt="error log-mysql-ok" src="https://github.com/user-attachments/assets/2a95443c-e339-4d3d-9e2f-d967a4ac7176" />


내용은 아래와 같이 나온다

![cat-error log-mysql](https://github.com/user-attachments/assets/e0a6ba5f-08a4-4ef5-b202-33dce8a9bd7f)


general log도 php의 에러 로그와 같이 로깅 설정을 해주고, 경로까지 지정해주어야 한다. 때문에 php 로그와 같은 문제가 있을 수 있지만,, 일단 `/var/log/mysql/general.log` 경로에 저장해주기로 했다.

```sql
SHOW VARIABLES LIKE 'general%';
```

위의 명령어로 조회해보면

<img width="602" alt="mysql-general log" src="https://github.com/user-attachments/assets/00db3ec2-74af-4b89-91d2-969aa1e01aba" />


위와 같이 기본 설정이 되어있는 것을 알 수 있다.

아래 명령어로 로깅을 활성화 해주고,

```sql
SET GLOBAL general_log = ON;
```

아래 명령어로 정해둔 경로 및 파일명 설정을 해주었다.

```sql
SET GLOBAL general_log_file = '/var/log/mysql/general.log';
```

<img width="506" alt="mysql_change" src="https://github.com/user-attachments/assets/f08539ea-3c0d-40fe-9737-ea6a8eeb50de" />


해당 로그 파일의 출력은 아래와 같이 나온다.

<img width="793" alt="cat_general log" src="https://github.com/user-attachments/assets/3a70f8fe-6188-474b-84ea-d95e34b5466c" />


### 웹 사이트 실행 및 데이터베이스 구성

개인 게시판의 코드를 /var/www/html 경로에 가져오고, mysql 데이터베이스 테이블을 만드는 과정이다.

<img width="469" alt="web_dir" src="https://github.com/user-attachments/assets/1ba51617-9f82-4fc6-b9cd-19183d1da13f" />


코드는 그냥 로컬에 있는 페이지를 가져왔고, 데이터베이스 설정은 아래와 같이 해주었다.

우선 DB라는 이름의 데이터베이스를 생성해주었다.

```sql
CREATE DATABASE DB;
```

<img width="359" alt="DB" src="https://github.com/user-attachments/assets/449557b9-3499-4159-b382-56653c62adcb" />


작업할 데이터베이스를 선택해주고

```sql
USE DB;
```

아래와 같이 회원 정보를 관리할 member 테이블과 게시글을 관리할 board 테이블을 만들어주었다.

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

<img width="262" alt="tables" src="https://github.com/user-attachments/assets/375725f1-71ea-479d-b4d6-e39944aff4a1" />


### 웹 사이트 접속 후 동작 확인

이제 웹 사이트에 접속해보면

<img width="1702" alt="web" src="https://github.com/user-attachments/assets/d7186437-a809-453a-b129-abaa943eab11" />

위와 같이 실습용 게시판이 생성됨

# 공격 및 로그 수집

## 1. 스캐닝 도구 탐지 (apache/access.log)

보통의 요청의 경우 `/var/log/apache2/access.log` 의 user-agent가 브라우저 정보로 나온다.

```php
127.0.0.1 - - [17/May/2025:18:36:47 +0900] "GET /index.php HTTP/1.1" 200 1134 "http://localhost/board/content.php?idx=4" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0"
```

```php
Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0
```

하지만 스캐닝 도구를 사용한 경우 User-agent가 해당 스캐닝 도구의 이름으로 나오게 된다.

```php
127.0.0.1 - - [17/May/2025:19:09:22 +0900] "GET /member/login.php HTTP/1.1" 200 773 "-" "sqlmap/1.8.4#stable (https://sqlmap.org)"
```

```php
sqlmap/1.8.4#stable (https://sqlmap.org)
```

이 경우를 탐지하면 되는데, 브라우저일 경우를 화이트 리스트 필터링 해야할지 아니면 도구일 경우를 블랙 리스트 필터링 해야할지 선택해야 한다..

|  | 장점 | 단점 |
| --- | --- | --- |
| 블랙 리스트 필터링 | 정확한 탐지가 가능하다 | 새로운 도구가 등장하거나, 개인이 제작한 도구의 경우 탐지 불가능 |
| 화이트 리스트 필터링 | 업데이트를 하지 않아도 새로운 도구나 제작 도구를 탐지할 수 있다 | 정상적인 user-agent를 오탐하는 경우도 발생할 수 있다. |

이와 같은 장단점이 존재하는데, 개인적인 생각으로는 블랙 리스트 필터링이 조금 더 적합할 것 같다고 생각한다. 정확한 탐지가 가능하며 올바르게 탐지가 되었는지 확인하는 오탐을 재확인 해야하는 번거로움을 걸러준다고 생각하기 때문이다.

아니면 블랙 리스트로 1차적인 필터링을 진행한 후 화이트 리스트로 한 번 더 필터링을 거치는 등의 방법도 괜찮을 것 같다고 생각한다.

⇒ 예를 들어 sqlmap 등의 도구를 1차적으로 탐지한 후 curl 등의 경우와 같이 일반적으로 사용되는 브라우저가 아닌 경우 별도로 다르게 분류하여 결과를 내는 것도 괜찮을 것 같다.


📌***결론***<br>
_sqlmap, nikto 등의 스캐닝 도구가 포함된 user-agent가 `/var/log/apache2/access.log` 에서 발견되는 경우를 탐지_


## 2. XSS

### Stored XSS (mysql/general.log)

게시글 등에 XSS 코드가 삽입되어 해당 게시글을 조회할 경우 사용자의 쿠키 탈취 등의 공격이 실행

<img width="1702" alt="stored_xss" src="https://github.com/user-attachments/assets/639df687-a949-4691-bd5c-713ccd2af57b" />


해당 공격의 경우 피해가 발생하는 시점은 글을 읽는 상황이지만, 그 시점에서 나오는 로그로는 XSS 피해가 일어났음을 탐지하기 어렵다. 때문에 피해가 일어나는 시점이 아닌, XSS 공격을 시도하려는 게시글이 작성된 경우를 탐지하는 것이 좋을 듯하다.

아래는 `/var/log/apache2/access.log`에 기록된 글 작성 로그이며,

```sql
127.0.0.1 - - [17/May/2025:18:11:14 +0900] "POST /board/write_ok.php HTTP/1.1" 302 230 "http://localhost/board/write.php" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0"
```

아래는 `/var/log/mysql/general.log`에 기록된 게시글의 내용을 데이터베이스에 저장하는 쿼리 로그이다.

```sql
2025-05-17T09:11:14.382385Z	   59 Query	INSERT INTO board(userid,title,content,date,hit,file_name) VALUES('keshu','Stored XSS','&lt;script&gt;alert(document.cookie);&lt;/script&gt;','2025-05-17',0,'')
```

게시글의 내용이 GET 요청으로 파라미터에 포함된다면 access.log로 공격을 잡겠지만, 현재의 경우(대부분의 경우도) 글 작성의 경우 POST 요청으로 진행되기 때문에 access.log에서는 확인이 불가능하다.

때문에 general.log에 전달되는 쿼리 중 XSS 요청이 포함되는 경우를 잡으면 어떨까 싶다.

⇒ 게시글에 <script> 태그가 포함되는 경우를 탐지하는 것

대부분의 웹 사이트에서 해당 공격이 필터링 되어있어 아래와 같은 대응이 된 경우

```php
$content=htmlspecialchars($_POST['content']);
```

<img width="1702" alt="stored_xss2" src="https://github.com/user-attachments/assets/96d5d2bc-9920-4e28-b34a-37f338be9e4b" />


게시글에 작성되는 script 구문이 동작되지는 않지만, sql 쿼리에는 위에 로그에서 확인한 것과 같이

```sql
INSERT INTO board(userid,title,content,date,hit,file_name) VALUES('keshu','Stored XSS','&lt;script&gt;alert(document.cookie);&lt;/script&gt;','2025-05-17',0,'')
```

script 태그가 포함된 쿼리로 실행된 것을 확인할 수 있다.

htmlspecialchars를 통해 html 엔티티로 script 태그가 치환되기 때문에

```sql
&lt;script&gt;alert(document.cookie);&lt;/script&gt;
```

이런식으로 저장이 되고 있다.

만약 로그로 공격을 탐지하는 경우 `&lt;script&gt;` 등이 포함된다면 XSS 공격으로 탐지하면 될 것 같다.

📌***결론***<br>
_XSS 공격 탐지는 htmlspecialchars로 필터링이 되는 경우에는 `/var/log/mysql/general.log` 로그에 INSERT와 UPDATE문에서 `&lt;script&gt;` 등의 문자열이 포함된 경우를 탐지해야 한다._ <br>
_+ 필터링이 안되는 경우는(없겟지만..?) `<script>` 등을 탐지하면 될 듯_ <br>
_(탐지해야하는 문자열들은 다른 공격들도 알아본 후 추가적으로 진행)_

### Reflected XSS (apache/access.log)

URL 파라미터 등에 쿼리가 입력될 시

<img width="1702" alt="reflected_xss" src="https://github.com/user-attachments/assets/968a4825-e153-480b-a845-99b9025385f1" />


필터링이 안된 경우

<img width="1702" alt="reflected_xss2" src="https://github.com/user-attachments/assets/803f6395-0d86-4882-b49c-29d3596b5ff0" />


필터링이 안된 경우

필터링은 아래와 같이 진행되었다.

```php
$search_con=htmlspecialchars($_GET['search']);
```

<img width="1702" alt="reflected_xss3" src="https://github.com/user-attachments/assets/2dedfbf8-2818-48a6-b0fd-61f3437c91fa" />


필터링이 된 경우

위와 같은 경우는 URL에 포함되어 가기 때문에 `/var/log/apache2/access.log` 에서 탐지가 가능하다.

이번에는 필터링이 되었을 때와 안되었을 때의 URL이 모두 같다.

⇒ 이유는 결국 URL에 입력되는 값이 화면에 나타나며 발생하는 것인데, 현재의 경우 URL로 값 자체를 필터링 하는 것이 아닌 URL로 전달받은 값을 화면에 표시할 때 HTML 엔티티로의 치환이 일어났기 때문에 같은 값인 것이다.

```sql
127.0.0.1 - - [17/May/2025:18:36:52 +0900] "GET /board/search.php?search=%3Cscript%3Ealert%28document.cookie%29%3B%3C%2Fscript%3E HTTP/1.1" 200 553 "http://localhost/index.php" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0"
```

이번에는 URL 값이기 때문에 로그에 이런식으로 남는다.

```php
%3Cscript%3Ealert%28document.cookie%29%3B%3C%2Fscript%3E
```

만약 로그로 공격을 탐지하는 경우 `%3Cscript%3E` 등이 포함된다면 XSS 공격으로 탐지하면 될 것 같다.

📌***결론***<br>
_XSS 공격 탐지는 htmlspecialchars로 필터링이 되는 경우와 필터링이 안된 경우에는 `/var/log/apache2/access.log` 로그에서 `%3Cscript%3E` 등의 문자열이 포함된 경우를 탐지해야 한다._ <br>
_(탐지해야하는 문자열들은 다른 공격들도 알아본 후 추가적으로 진행)_

# 로그 수집기

Filebeat 사용 예정

# Reference

- https://data04190.tistory.com/16
- https://who1sth1s.tistory.com/entry/mysql-log%EB%B3%B4%EB%8A%94%EB%B2%95
- https://real-dongsoo7.tistory.com/179L
