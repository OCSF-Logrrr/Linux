# Ubuntu Linux 환경 구축
이 문서는 Ubuntu Linux 환경에서 웹 서비스 인프라를 구축하는 방법을 안내합니다. (아키텍처 참조)

## 웹 서비스 인프라 아키텍처
<img width="50%" alt="스크린샷 2025-05-23 오후 5 03 47" src="https://github.com/user-attachments/assets/219f34e5-5591-4b69-8887-6c7ab075b1aa" />

- DNS 서버 : BIND9
- 방화벽 : UFW
- 웹 방화벽 : ModSecurity
- API Gateway : Nginx
- Board API : Apache2 + PHP + MySQL
- Chat API : Node.js + MongoDB

## Ubuntu Version
Ubuntu 22.04.05 version 사용
<img width="1600" alt="스크린샷 2025-05-23 오후 5 43 11" src="https://github.com/user-attachments/assets/8bfe8716-ae63-444c-859c-f5c01e9d8c25" />
