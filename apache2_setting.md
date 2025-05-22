# Apache2 세팅
> 기본적인 Apache2, MySQL, PHP를 설치하는 방법을 적은 글입니다.

</br>

## Apach2 설치

Apache2를 설치하기 전에 시스템을 먼저 업그레이드 해줍니다.

```bash
sudo apt update
sudo apt upgrade
```

</br>

시스템을 업그레이드 해준 후 Apache2를 설치해줍니다.

```bash
sudo apt install apache2
```

</br>


현재 사용중인 Ubuntu 버전인 22.04 LTS에는 [UFW]((https://help.ubuntu.com/community/UFW))라는 방화벽이 활성화되어 있습니다. </br>
방화벽을 통해 HTTP 트래픽을 허용하도록 UFW를 구성해야합니다.


```bash
sudo ufw allow http
sudo ufw allow https
```

</br>

해당 설정까지 끝내면 기본적인 Apache2 설치는 끝입니다.

</br>

### TIP

- Apache2에 기본 구성 파일은 `/etc/apache2/apache2.conf' 입니다.
- Apachd2를 시작, 종료, 상태를 확인하려면 아래와 같은 명령어를 사용할 수 있습니다.
  - `sudo systemctl start apache2`: Apache2를 시작합니다.
  - `sudo systemctl stop apache2`: Apachd2를 종료합니다.
  - `sudo systemctl status apache2`: 현재 Apachd2의 상태를 확인합니다.
  - `sudo systemctl restart apachd2`: Apachd2를 재시작합니다.

 </br>

---

</br>

## MySQL 설치

MySQL을 설치하기 위해 시스템을 업데이트 해줍니다.

```bash
sudo apt update
```

</br>

시스템을 업데이트 한 후 MySQL을 설치해줍니다.

```bash
sudo apt install mysql-server
```

</br>

MySQL도 마찬가지로 UFW 설정을 해줍니다.

```bash
sudo ufw allow mysql
```

</br>

이후, MySQL을 실행해줍니다.

```bash
sudo systemctl start mysql
```

</br>

### MySQL 초기 비밀번호 설정
> 해당 설정은 8.0 이상 버전의 MySQL을 기준으로 작성되었습니다.

</br>

MySQL 내부로 들어갑니다.

```bash
sudo mysql -u root
```

</br>

이후 비밀번호를 설정해줍니다.

```mysql
alter user 'root'@'localhost' identified with mysql_native_password by '원하는 비밀번호';
```

</br>

비밀번호를 설정한 다음 변경 사항을 적용해줍니다.

```mysql
flush privileges;
```

</br>

비밀번호 적용 후 MySQL에 접속해봅니다.

```bash
mysql -u root -p
```

</br>

### TIP

- MySQL도 Apachd2와 마찬기지로 시작, 종료, 상태를 확인하려면 아래와 같은 명령어를 사용할 수 있습니다.
  - `sudo systemctl start mysql`: mysql을 시작합니다.
  - `sudo systemctl stop mysql`: mysql을 종료합니다.
  - `sudo systemctl status mysql`: 현재 mysql의 상태를 확인합니다.
  - `sudo systemctl restart mysql`: mysql을 재시작합니다.

</br>

---

</br>

## PHP

PHP를 설치하기 위해 시스템을 업데이트 해줍니다.

</br>

```bash
sudo apt update
```

</br>

이후 PHP 7.4를 설치하기 위해 필요한 것들을 설치해줍니다.

```bash
sudo apt install -y software-properties-common lsb-release ca-certificates apt-transport-https
sudo add-apt-repository ppa:ondrej/php
sudo apt update
```

- `software-properties-common`: add-apt-repository 명령어를 사용하기 위해 필요
- `lsb-release`: Ubuntu 배포판 정보를 확인하는 도구
- `ca-certificates`: HTTPS 통신을 위한 인증서
- `apt-transport-https`: apt가 HTTPS 저장소에서 패키지를 다운로드할 수 있게 함
- `sudo add-apt-repository ppa:ondrej/php`: PHP 관련 최신 버전과 확장 모듈을 제공하는 공식 PPA 저장소를 추가

</br>

PHP 및 필요한 PHP 패키지들을 설치해줍니다.

```bash
sudo apt install -y php7.4 php7.4-cli php7.4-common php7.4-mysql php7.4-xml php7.4-curl php7.4-gd php7.4-mbstring php7.4-zip php7.4-bcmath php7.4-readline
```


- `php7.4`: PHP 7.4 메타 패키지
- `php7.4-cli`: 터미널에서 PHP 스크립트 실행할 수 있도록 함
- `php7.4-common`: 공통 설정 파일과 구성 요소
- `php7.4-mysql`: PHP에서 MySQL과 연동
- `php7.4-xml`: XML 처리
- `php7.4-curl`: HTTP 요청 보내기
- `php7.4-gd`: 이미지 처리
- `php7.4-mbstring`: 다국어 문자처리
- `php7.4-zip`: ZIP 파일 압축/해제 지원
- `php7.4-bcmath`: 정밀 수학 연산
- `php7.4-readline`: CLI에서 입력 처리

  </br>

이후 `php -v`를 입력해 정상적으로 PHP가 설치되었는지 확인합니다.
