# Ubuntu Linux 환경 구축
이 문서는 Ubuntu Linux 환경에서 웹 서비스 인프라를 구축하는 설치 방법을 안내합니다. (아키텍처 참조)
> 세부적인 설정에 대한 내용은 Setting and Logging 디렉토리의 파일들을 통해 확인 가능합니다.

# 목차
- [웹 서비스 인프라 아키텍처](#웹-서비스-인프라-아키텍처)
- [Ubuntu](#ubuntu)
- [DNS Server - BIND9](#dns-server---bind9)
- [Firewall - UFW](#firewall---ufw)
- [API Gateway - Nginx](#api-Gateway---nginx)
- [WAF - ModSecurity](#waf---modsecurity)
- [Board API : Apache2 + PHP + MySQL](#board-api--apache2--php--mysql)
   - [Apache2](#apache2)
   - [PHP](#php)
   - [MySQL](#mysql)
   - [연동 모듈](#연동-모듈)
- [Chat API : Node.js + MongoDB](#chat-api--node.js--mongodb)
   - [Node.js](#node.js)
   - [MongoDB](#mongodb)

## 웹 서비스 인프라 아키텍처
<img width="50%" alt="스크린샷 2025-05-23 오후 5 03 47" src="https://github.com/user-attachments/assets/219f34e5-5591-4b69-8887-6c7ab075b1aa" />

- DNS Server : BIND9
- Firewall : UFW
- WAF : ModSecurity
- API Gateway : Nginx
- Board API : Apache2 + PHP + MySQL
- Chat API : Node.js + MongoDB

## Ubuntu
Ubuntu 24.04 사용
<img width="1336" alt="Ubuntu_version" src="https://github.com/user-attachments/assets/b9f94131-e8f3-46e7-9b38-48b4007dda1b" />
> 추가적으로 `sudo timedatectl set-timezone Asia/Seoul`를 통해 한국 시간으로 바꾸어 사용해주었습니다.

## DNS Server - BIND9
BIND9는 아래의 명령어로 설치 가능합니다.
```bash
sudo apt-get install bind9
```

## Firewall - UFW
ufw의 경우 기본적으로 설치되어 있지만, 만약 설치되지 않은 경우 `sudo apt install ufw`를 통해 설치해주세요.
아래의 명령어를 통해 활성화 및 비활성화가 가능합니다.
```bash
sudo ufw enable #활성화
```
```bash
sudo ufw disable #비활성화
```
또한 아래와 같이 상태 확인이 가능합니다.
```bash
sudo ufw status verbose
```
<img width="1336" alt="스크린샷 2025-05-24 오후 2 24 19" src="https://github.com/user-attachments/assets/d354b3ca-8664-483f-8fcb-2211d1c09995" />

자세한 설정은 () 페이지를 참고해주세요.

## API Gateway - Nginx
Nginx는 아래의 명령어로 설치 가능합니다.
```bash
sudo apt install nginx
```
아래 명령어로 상태 확인이 가능합니다.
```bash
sudo systemctl status nginx
```
<img width="1336" alt="스크린샷 2025-05-24 오후 2 26 35" src="https://github.com/user-attachments/assets/0fe1eb3e-d259-4e40-b624-1409f618d9d7" />

## WAF - ModSecurity
우선 아래의 명령어를 통해 필요한 패키지를 설치합니다.
```bash
sudo apt-get install git g++ apt-utils autoconf automake build-essential libcurl4-openssl-dev libgeoip-dev liblmdb-dev libpcre2-dev libtool libxml2-dev libyajl-dev pkgconf zlib1g-dev
```
ModSecurity 소스를 가져와 아래의 빌드 과정을 진행합니다.
```bash
git clone https://github.com/owasp-modsecurity/ModSecurity
cd ModSecurity/
git submodule init
git submodule update
```
<img width="1336" alt="스크린샷 2025-05-24 오후 2 34 39" src="https://github.com/user-attachments/assets/db5110fa-4b64-49f6-9b2b-b9452384a899" />

```bash
sh build.sh
./configure --with-pcre2
```

<img width="1336" alt="스크린샷 2025-05-24 오후 2 35 14" src="https://github.com/user-attachments/assets/d2d4382d-dd7e-42a5-a2ef-35b65e8d1fb9" />

```bash
make
sudo make install
```
위와 같이 빌드가 모두 진행되면, ModeSecurity가 `/usr/local/modsecurity` 경로에 설치됩니다.

```bash
#예시는 /home/ocsf-logrrr/whs 디렉토리에서 아래의 명령어 실행
git clone https://github.com/nginx/nginx.git
git clone https://github.com/owasp-modsecurity/ModSecurity-nginx.git
cd nginx
```
```bash
git checkout release-1.22.1
./auto/configure --add-dynamic-module=/home/ocsf-logrrr/whs/ModSecurity-nginx --with-compat
make
```
위의 과정을 진행하면 생성되는 /objs/ngx_http_modsecurity_module.so 를 nginx 모듈 디렉토리로 복사합니다.
```bash
sudo mkdir -p /lib/nginx/modules
sudo cp ./objs/ngx_http_modsecurity_module.so /usr/share/nginx/modules
```

## Board API : Apache2 + PHP + MySQL
### Apache2
아래의 명령어로 설치를 진행합니다.
```bash
sudo apt install apache2
```
이후 바로 apache를 활성화 해주면 위에서 설치한 nginx와 80 포트로 충돌이 발생하기 때문에 아래 과정을 통해 apache의 포트를 수정하여 실행합니다.
총 2개의 파일을 수정해야 하는데, 우선 아래와 같이 /etc/apache2/ports.conf 파일의 Listen을 8080 등의 포트로 수정합니다.
```bash
sudo vi /etc/apache2/ports.conf
```
<img width="1336" alt="스크린샷 2025-05-24 오후 3 17 37" src="https://github.com/user-attachments/assets/45409afa-1f96-4037-8ba9-ecbc46398414" />
또한 /etc/apache2/sites-available/000-default.conf 파일의 VirtualHost도 8080 등으로 수정합니다.

```bash
sudo vi /etc/apache2/sites-available/000-default.conf
```

<img width="1336" alt="스크린샷 2025-05-24 오후 3 18 01" src="https://github.com/user-attachments/assets/26c55276-ab07-4a9e-ac7b-f47697a3c1ed" />

두 파일의 설정이 모두 끝나면 아래의 명령어로 apache 서비스 활성화가 가능합니다.
```bash
sudo systemctl start apache2
```
이후 아래의 명령어로 서비스 상태를 확인할 수 있습니다.
```bash
sudo systemctl status apache2
```
<img width="1336" alt="스크린샷 2025-05-24 오후 3 18 31" src="https://github.com/user-attachments/assets/d59c6579-243d-4710-a6a1-e07a3618c623" />

### PHP
아래의 명령어로 설치를 진행합니다.
```bash
sudo apt install php
```

### MySQL
아래의 명령어로 설치를 진행합니다.
```bash
sudo apt install mysql-server
```

### 연동 모듈
php와 mysql, php와 apache를 연동해주기 위한 모듈 설치를 진행합니다.
```bash
sudo apt install php-mysql
```
```bash
sudo apt install libapache2-mod-php
```

## Chat API : Node.js + MongoDB
### Node.js
아래의 명령어로 Node.js와 패키지 관리자인 NPM 설치를 진행합니다.
```bash
sudo apt install nodejs npm
```
### MongoDB
패키지 관리 시스템에서 사용하는 공개키를 진행하기 위해 우선 gnupg와 curl 설치를 진행합니다.
```bash
sudo apt-get install gnupg curl
```
이후 MongoDB 공개 GPG 키를 가져오기 위한 명령을 실행합니다.
```bash
curl -fsSL https://www.mongodb.org/static/pgp/server-7.0.asc | \
   sudo gpg -o /usr/share/keyrings/mongodb-server-7.0.gpg \
   --dearmor
```
사용 중인 Ubuntu 버전에 대한 목록 파일인 /etc/apt/sources.list.d/mongodb-org-7.0.list 를 생성합니다.
```bash
echo "deb [ arch=amd64,arm64 signed-by=/usr/share/keyrings/mongodb-server-7.0.gpg ] https://repo.mongodb.org/apt/ubuntu jammy/mongodb-org/7.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-7.0.list
```
로컬 패키지 데이터베이스를 다시 불러옵니다.
```bash
sudo apt-get update
```
이제 MongoDB 패키지 설치를 진행합니다.
```bash
sudo apt-get install mongodb-org
```
> 선택 사항으로 아래의 명령어들을 통해 의도하지 않은 업그레이드를 방지하려면 현재 설치된 패키지를 고정할 수 있습니다.
> ```bash
> echo "mongodb-org hold" | sudo dpkg --set-selections
> echo "mongodb-org-database hold" | sudo dpkg --set-selections
> echo "mongodb-org-server hold" | sudo dpkg --set-selections
> echo "mongodb-mongosh hold" | sudo dpkg --set-selections
> echo "mongodb-org-mongos hold" | sudo dpkg --set-selections
> echo "mongodb-org-tools hold" | sudo dpkg --set-selections
> ```

모든 설치가 완료되면 아래 명령어를 통해 프로세스를 시작할 수 있습니다.
```bash
sudo systemctl start mongod
```
또한 아래 명령어를 통해 시스템의 서비스 및 유닛 파일이 변경된 경우, 이 변경사항을 systemd가 인식하도록 다시 로드하는 작업 수행이 가능합니다.
```bash
sudo systemctl daemon-reload
```
아래 명령어를 통해 상태 확인이 가능합니다.
```bash
sudo systemctl status mongod
```
<img width="1336" alt="스크린샷 2025-05-24 오후 3 41 59" src="https://github.com/user-attachments/assets/9a61190e-c86e-46cc-853c-982e02482f4b" />





