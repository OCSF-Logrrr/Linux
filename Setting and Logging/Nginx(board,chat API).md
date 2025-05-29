# API Gateway - Nginx

> Nginx 기반 API Gateway 아키텍처에서 Web/API/DB 설정 가이드입니다.

</br>

## Nginx

### 설치

다음 명령어를 통해 Nginx를 설치해줍니다.

```bash
sudo apt update
sudo apt install nginx
```

</br>

### 설정

> 기본 설정은 변경한 사항이 없기에 라우팅 설정부터 진행합니다.

라우팅 설정을 위해 `/etc/nginx/sites-available/default` 해당 파일을 수정해줍니다.

```bash
sudo vi /etc/nginx/sites-available/default
```

</br></br>

```plaintext
server {
  listen 80 default_server;
  listen [::]:80 default_server;
  ...
}
```

- Nginx의 기본 포트를 80포트로 설정합니다.

</br>

```plaintext
root /var/www/html/nginx;
```

- Document Root를 `/var/www/html/nginx`로 수정합니다. (기존은 `/var/www/html` 입니다.

</br>

#### api 라우팅 설정

```plaintext
location /api/ {
  proxy_pass http://127.0.0.1:3000/api/;
  proxy_http_version 1.1;
  proxy_set_header Upgrade $http_upgrade;
  proxy_set_header Connection 'upgrade';
  proxy_set_header Host $host;
  proxy_cache_bypass $http_upgrade;
}
```

- `/api/`로 들어오는 요청을 Node.js 서버(127.0.0.1:3000)의 `/api/`로 프록시해줍니다.
  - WebSocket 지원
  - HTTP 1.1
  - 필요한 헤더들을 백엔드에 전달

</br>

#### webapi 라우팅 설정

```plaintext
location /webapi/ {
  proxy_pass http://127.0.0.1:8080;
  proxy_set_header Host $host;
  proxy_set_header X-Real-IP $remote_addr;

  rewrite ^/webapi/(.*)$ /$1 break;
}
```

- `/webapi/`로 들어오는 요청을 Apache2(127.0.0.1:8080)로 프록시해줍니다.
    - `/webapi/` 접두어를 제거해서 전달
    - 클라이언트 IP 및 Host 정보도 백엔드에 전달

</br>

#### css 라우팅 설정

```plaintext
location /css/ {
  root /var/www/html/apache2;
}
```

- `/css/`로 들어오는 요청을 `/css/` 부분을 제거한 나머지 경로를 /var/www/html/apache2 경로 뒤에 붙여서 실제 파일을 찾아줍니다.

</br>

#### php 라우팅 설정

```plaintext
location ~ \.php$ {
  root /var/www/html/apache2;
  include snippets/fastcgi-php.conf;
  fastcgi_pass unix:/run/php/php7.4-fpm.sock;
}
```

- `.php`로 끝나는 URL 요청이 들어올 때 적용되는 사항들입니다.
  1. 클라이언트가 .php로 끝나는 URL을 요청한다.
  2. Nginx는 /var/www/html/apache2 디렉토리에서 해당 PHP 파일을 찾는다.
  3. fastcgi-php.conf 설정에 따라 PHP 실행에 필요한 환경 변수를 설정한다.
  4. 요청을 PHP-FPM(php7.4-fpm.sock)으로 전달해 PHP 스크립트를 실행한다.
  5. PHP-FPM이 실행 결과를 Nginx로 반환하고, Nginx가 클라이언트에 응답을 전달한다.

</br></br>


## board API 구성 (Apache + PHP + MySQL)

> Apache2, PHP, MySQL 설정에 대해 작성하였습니다.
>
> [버전]
> - Apache: 2.4.58
> - PHP: 7.4.33
> - MySQL: 8.0.42

기초적인 Apache2, PHP, MySQL 설치에 관련된 내용은 https://github.com/OCSF-Logrrr/Linux/blob/main/web_server_setting.md에 정리되어있습니다.

### Apache2 설정

Apache2의 기본 포트를 변경합니다. Nginx와 포트 충돌을 막기 위함입니다.

```bash
sudo vi /etc/apache2/ports.conf
```

위의 명령어를 입력한 후 파일을 수정해줍니다.

```plaintext
Listen 8080

<IfModule ssl_module>
        Listen 443
</IfModule>

<IfModule mod_gnutls.c>
        Listen 443
</IfModule>
```


### PHP 코드 작성
- PHP 코드로는 저희 팀원이 개발한 게시판 코드를 사용했습니다. (https://github.com/OCSF-Logrrr/Linux/tree/main/Web_Code/Board1)

</br>

DB(MySQL)는 외부 서버에 구축해두었기에 Board1의 MySQL 연동 코드를 수정해주어야합니다.

```php
$conn=mysqli_connect("DBServer IP","user","password","DB 이름");
```


## chat API 구성 (Node.js + MongoDB)

### Node.js 설치

먼저 시스템을 업데이트해줍니다.

```bash
sudo apt update
```

</br>

이후, Node.js 및 npm을 설치해줍니다.

```bash
sudo apt install nodejs npm
```

</br>

설치를 확인해봅니다.

```bash
node -v
npm -v
```

</br>

### 코드 작성
> https://github.com/snoopysecurity/dvws-node 오픈소스를 활용하였습니다.


### mongodb 설정

```bahs
# 1) 패키지 목록 업데이트 및 필수 패키지 설치
sudo apt update
sudo apt install -y gnupg

# 2) MongoDB 공개키 가져오기
curl -fsSL https://pgp.mongodb.com/server-6.0.asc | sudo gpg -o /usr/share/keyrings/mongodb-server-6.0.gpg --dearmor

# 3) MongoDB 저장소 리스트 추가
echo "deb [ signed-by=/usr/share/keyrings/mongodb-server-6.0.gpg ] https://repo.mongodb.org/apt/ubuntu jammy/mongodb-org/6.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-6.0.list

# 4) 패키지 리스트 업데이트
sudo apt update

# 5) MongoDB 설치
sudo apt install -y mongodb-org
```

