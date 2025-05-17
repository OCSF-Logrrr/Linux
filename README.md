# apache

# apache 로그 위치

apache 로그는 기본적으로 error.log와 access.log 2가지가 존재한다.

- error.log : 웹 서버에서 발생하는 오류에 대한 정보가 기록되는 파일
- access.log : 웹 서버에 접속한 클라이언트의 IP 주소, 접속 시간, 요청한 페이지 등 모든 HTTP 요청에 대한 정보가 기록되는 파일

파일 위치는 우분투 기준으로 `/var/log/apache2`에 두 파일 모두 존재

# 환경 구축

우분투 24.04에서 환경 구축을 진행하였으며, 웹 사이트는 개인 실습용으로 제작했던 게시판을 사용하였다. 

<aside>
📌

**실습을 하다보니 개인용 게시판보다 DVWA 등의 대응 방안이 완전히 적용된 코드와 아닌 코드를 모두 편리하게 실행 가능한 환경에서 해보는게 좋다고 느꼈다.. 때문에 XSS 이후 실습에서는 DVWA를 사용해볼 생각이다.**

</aside>

## apache 설치

apache 설치

```bash
sudo apt install apache2
```

![스크린샷 2025-05-16 오후 11.37.57.png](attachment:248466ce-f973-4362-8712-facd762b6c90:스크린샷_2025-05-16_오후_11.37.57.png)

apache 서비스 시작 후

```bash
sudo systemctl start apache2
```

데몬(서비스) 상태 확인

```bash
sudo systemctl status apache2
```

![스크린샷 2025-05-16 오후 11.42.16.png](attachment:cc1ada0a-8800-40f3-8f8e-d2dcacfc7f41:스크린샷_2025-05-16_오후_11.42.16.png)

localhost에 접속 시 apache 화면이 나오는 것 확인 가능

![스크린샷 2025-05-16 오후 11.45.43.png](attachment:c3ba9c9e-515a-4767-a477-3a378b31a978:스크린샷_2025-05-16_오후_11.45.43.png)

## 로그 위치 및 내용 확인

아래와 같이 /var/log/apache2 경로에 error.log와 access.log가 존재하는 것을 확인

![스크린샷 2025-05-17 오전 12.01.22.png](attachment:f83e9b10-ea0a-459d-9ca8-d82473233c2b:스크린샷_2025-05-17_오전_12.01.22.png)

### access.log

![스크린샷 2025-05-17 오전 12.02.35.png](attachment:fe2e2f52-7db9-4bef-9e48-a038b185301b:스크린샷_2025-05-17_오전_12.02.35.png)

현재 로그를 살펴보면

```bash
127.0.0.1 - - [16/May/2025:23:45:38 +0900] "GET / HTTP/1.1" 200 3460 "-" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0"
127.0.0.1 - - [16/May/2025:23:45:38 +0900] "GET /icons/ubuntu-logo.png HTTP/1.1" 200 3607 "http://localhost/" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0"
127.0.0.1 - - [16/May/2025:23:45:38 +0900] "GET /favicon.ico HTTP/1.1" 404 487 "http://localhost/" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0"
```

get 요청으로 기본 웹 루트인 /var/www/html/index.html에 접속한 기록과 해당 웹 페이지에서 사용되는 /icons/ubuntu-logo.png 이미지도 정상적으로 제공되었다는 로그가 찍혀있다.

마지막 로그는 브라우저 탭에 표시되는 로고가 없어서 404로 뜨는 것이다.

### error.log

![스크린샷 2025-05-17 오전 12.10.29.png](attachment:fa6318fe-c7fd-4a91-8d30-49d0d26455b2:스크린샷_2025-05-17_오전_12.10.29.png)

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

![스크린샷 2025-05-17 오후 3.16.28.png](attachment:2a019b1f-6049-4f71-a6be-feb7ceab1494:스크린샷_2025-05-17_오후_3.16.28.png)

<aside>
💡

mysql 계정 생성

```sql
CREATE USER 'keshu'@'localhost' IDENTIFIED BY '1234';
```

```sql
GRANT ALL PRIVILEGES ON *.* TO 'keshu'@'localhost' WITH GRANT OPTION;
```

```sql
FLUSH PRIVILEGES;
```

</aside>

php 설치

```bash
sudo apt install php
```

![스크린샷 2025-05-17 오후 3.15.29.png](attachment:14481027-a10b-4bff-af8a-dff7a77933da:스크린샷_2025-05-17_오후_3.15.29.png)

php에서 mysql과 apache를 사용할 수 있도록 모듈 설치

```bash
sudo apt install php-mysql
```

![스크린샷 2025-05-17 오후 3.18.09.png](attachment:85589174-a5ce-42f3-acd5-7eba0249597d:스크린샷_2025-05-17_오후_3.18.09.png)

```bash
sudo apt install libapache2-mod-php
```

![스크린샷 2025-05-17 오후 3.18.39.png](attachment:4362b387-6e62-44f5-bec7-d460ea3195cd:스크린샷_2025-05-17_오후_3.18.39.png)

### PHP 및 MySQL 로깅 설정

아래의 경로에서 PHP 로깅 설정 (버전은 8.3으로 되어있기에 어떤 버전을 설치하는지에 따라 다른 명령어 사용)

```bash
sudo vi /etc/php/8.3/cli/php.ini
```

![스크린샷 2025-05-17 오후 3.32.11.png](attachment:5c5db4f9-9c1e-4e46-9b34-3e4654c7e0c1:30107fd4-4154-4f02-adab-c181615d00c5.png)

위 부분에 명시된 경로 및 파일에 PHP 에러 로그가 기록되는데,, 여기서 문제가 있다면, 사용자가 어떤 경로를 명시하는 지에 따라서 로그 수집기로 수집할 때 각 환경에 맞는 경로로 사용해야하는 문제가 발생한다고 한다..

⇒ 위 사진 부분의 `error_log =` 다음에 경로나 파일을 따로 명시하지 않으면 apache의 error.log 로 들어간다고 하긴 하는데… ⇒ 이럼 통합은 되겠지만 apache에러랑 php 에러가 섞여서 나누기 힘들지 않을까..? 싶음….

일단 `/var/log/php/php_error.log` 로 설정 해둠..!! (php 디렉토리 만들고 권한 줘야함!!)

![스크린샷 2025-05-17 오후 3.37.46.png](attachment:f61037b6-b7d9-4ee2-8cb7-ff0595558329:71b6da20-1443-4fd2-bb05-dc486bdf0dab.png)

아래와 같이 에러를 로그파일에 기록하는 설정도 On으로 해주었다.

![스크린샷 2025-05-17 오후 3.37.19.png](attachment:9f2eb57e-eb2b-4b60-ba7b-1537bc448fa0:6088d143-7909-431c-a282-66e45426de97.png)

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

![스크린샷 2025-05-17 오후 4.13.06.png](attachment:de3017b4-9d72-4ce5-97bc-e78261dbf1c2:스크린샷_2025-05-17_오후_4.13.06.png)

내용은 아래와 같이 나온다

![스크린샷 2025-05-17 오후 4.45.15.png](attachment:f482c4a6-9b5f-4d79-a144-8408fb41e7b8:107c94b8-2179-4800-8847-3bcdc80469c0.png)

general log도 php의 에러 로그와 같이 로깅 설정을 해주고, 경로까지 지정해주어야 한다. 때문에 같은 문제가 있을 수 있지만,, 일단 `/var/log/mysql/general.log` 경로에 저장해주기로 했다.

```sql
SHOW VARIABLES LIKE 'general%';
```

위의 명령어로 조회해보면

![스크린샷 2025-05-17 오후 4.37.16.png](attachment:644602c3-ef10-4ff3-8afc-6090ab689019:스크린샷_2025-05-17_오후_4.37.16.png)

위와 같이 기본 설정이 되어있는 것을 알 수 있다.

아래 명령어로 로깅을 활성화 해주고,

```sql
SET GLOBAL general_log = ON;
```

아래 명령어로 정해둔 경로 및 파일명 설정을 해주었다.

```sql
SET GLOBAL general_log_file = '/var/log/mysql/general.log';
```

![스크린샷 2025-05-17 오후 4.42.46.png](attachment:d344a0c5-9c38-46b4-96b8-1143aaf9f3ad:스크린샷_2025-05-17_오후_4.42.46.png)

해당 로그 파일의 출력은 아래와 같이 나온다.

![스크린샷 2025-05-17 오후 4.46.18.png](attachment:50180e4e-8751-4ffa-b556-e53f398f4e31:스크린샷_2025-05-17_오후_4.46.18.png)

### 웹 사이트 실행 및 데이터베이스 구성

개인 게시판의 코드를 /var/www/html 경로에 가져오고, mysql 데이터베이스 테이블을 만드는 과정이다.

![스크린샷 2025-05-17 오후 4.58.20.png](attachment:bfe8159a-eab9-4955-97e8-b6101191bc4f:스크린샷_2025-05-17_오후_4.58.20.png)

코드는 그냥 로컬에 있는 페이지를 가져왔고, 데이터베이스 설정은 아래와 같이 해주었다.

우선 DB라는 이름의 데이터베이스를 생성해주었다.

```sql
CREATE DATABASE DB;
```

![스크린샷 2025-05-17 오후 5.02.14.png](attachment:98727686-13ce-4438-afd1-a85fb38fd322:스크린샷_2025-05-17_오후_5.02.14.png)

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

![스크린샷 2025-05-17 오후 5.14.30.png](attachment:9e15bd6e-40a9-4f4c-96bc-ee405d47d3cb:스크린샷_2025-05-17_오후_5.14.30.png)

### 웹 사이트 접속 후 동작 확인

이제 웹 사이트에 접속해보면

![스크린샷 2025-05-17 오후 5.21.36.png](attachment:ec1c30db-3449-4a0e-a28a-4d41e56641e8:스크린샷_2025-05-17_오후_5.21.36.png)

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

<aside>
📌

**결론**

sqlmap, nikto 등의 스캐닝 도구가 포함된 user-agent가 `/var/log/apache2/access.log` 에서 발견되는 경우를 탐지

</aside>

## 2. XSS

### Stord XSS (mysql/general.log)

게시글 등에 XSS 코드가 삽입되어 해당 게시글을 조회할 경우 사용자의 쿠키 탈취 등의 공격이 실행

![스크린샷 2025-05-17 오후 5.25.52.png](attachment:7819ea97-8810-424e-ae3b-45936c9c8b50:스크린샷_2025-05-17_오후_5.25.52.png)

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

![스크린샷 2025-05-17 오후 6.11.18.png](attachment:a85d0e73-9742-446d-84ef-3d7da677933f:스크린샷_2025-05-17_오후_6.11.18.png)

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

<aside>
📌

**결론**

XSS 공격 탐지는 htmlspecialchars로 필터링이 되는 경우에는 `/var/log/mysql/general.log` 로그에 INSERT와 UPDATE문에서 `&lt;script&gt;` 등의 문자열이 포함된 경우를 탐지해야 한다.

+ 필터링이 안되는 경우는(없겟지만..?) `<script>` 등을 탐지하면 될 듯

(탐지해야하는 문자열들은 다른 공격들도 알아본 후 추가적으로 진행)

</aside>

### Reflected XSS (apache/access.log)

URL 파라미터 등에 쿼리가 입력될 시

![필터링이 안된 경우](attachment:4d4eaa21-78ed-4d84-b234-e07e298ed484:스크린샷_2025-05-17_오후_6.31.16.png)

필터링이 안된 경우

![필터링이 안된 경우](attachment:8d115659-fc5d-405b-8752-cfa3b48ad72d:스크린샷_2025-05-17_오후_6.31.27.png)

필터링이 안된 경우

필터링은 아래와 같이 진행되었다.

```php
$search_con=htmlspecialchars($_GET['search']);
```

![필터링이 된 경우](attachment:a58273cf-0db0-4e39-865d-3e05dc10bc4a:스크린샷_2025-05-17_오후_6.36.59.png)

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

<aside>
📌

**결론**

XSS 공격 탐지는 htmlspecialchars로 필터링이 되는 경우와 필터링이 안된 경우에는 `/var/log/apache2/access.log` 로그에서 `%3Cscript%3E` 등의 문자열이 포함된 경우를 탐지해야 한다.

(탐지해야하는 문자열들은 다른 공격들도 알아본 후 추가적으로 진행)

</aside>

# 로그 수집기

Filebeat 사용 예정

# Reference

- https://data04190.tistory.com/16
- https://who1sth1s.tistory.com/entry/mysql-log%EB%B3%B4%EB%8A%94%EB%B2%95
- https://real-dongsoo7.tistory.com/179L
