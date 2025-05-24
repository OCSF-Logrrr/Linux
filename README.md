# Ubuntu Linux 환경 구축 (수정중..)
이 문서는 Ubuntu Linux 환경에서 웹 서비스 인프라를 구축하는 방법을 안내합니다. (아키텍처 참조)
추가적으로 로깅 설정 방법과 로그 파일의 위치를 안내합니다.

## 웹 서비스 인프라 아키텍처
<img width="50%" alt="스크린샷 2025-05-23 오후 5 03 47" src="https://github.com/user-attachments/assets/219f34e5-5591-4b69-8887-6c7ab075b1aa" />
- DNS Server : BIND9
- 방화벽 : UFW
- 웹 방화벽 : ModSecurity
- API Gateway : Nginx
- Board API : Apache2 + PHP + MySQL
- Chat API : Node.js + MongoDB

## Ubuntu
Ubuntu 24.04 사용
<img width="1336" alt="Ubuntu_version" src="https://github.com/user-attachments/assets/b9f94131-e8f3-46e7-9b38-48b4007dda1b" />
> 추가적으로 `sudo timedatectl set-timezone Asia/Seoul`를 통해 한국 시간으로 바꾸어 사용해주었습니다.
### System Log
시스템 로그는 Ubuntu 시스템에 대한 로그를 다룹니다. 이러한 로그에는 권한, 시스템 데몬 및 시스템 메시지에 대한 정보가 포함될 수 있습니다.
- `/var/log/auth.log` : sudo 명령, 비밀번호 입력, 원격 로그인 등 인증 시스템 로그
- `/var/log/daemon.log` : 백그라운드에서 실행되는 프로그램 데몬의 로그
- `/var/log/debug` : Ubuntu 시스템 및 애플리케이션의 디버깅 정보 로그
- `/var/log/kern.log` : 리눅스 커널에서 발생하는 로그
- `/var/log/syslog` : 시스템 전반에 관한 로그

## 방화벽 - UFW
ufw의 경우 기본적으로 설치되어 있지만, 만약 설치되지 않은 경우 `sudo apt install ufw`를 통해 설치해주세요.
아래의 명령어를 통해 

## DNS Server - BIND9
BIND9는 아래의 명령어로 설치 가능합니다.
```bash
sudo apt-get install bind9
```
