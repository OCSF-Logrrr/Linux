# WAF - ModSecurity + OWASP CRS

> Nginx 서버에 ModSecurity WAF 와 OWASP Core Rule Set(CRS)를 연동하여 실전 공격 탐지 및 차단 기능 활성화합니다.

## 1. ModSecurity 설정
### 설정 파일 복사 및 활성화
```bash
sudo cp modsecurity.conf-recommended modsecurity.conf
```
![image](https://github.com/user-attachments/assets/8836a4d1-50ce-4446-893b-e0081e153527)
modsecurity.conf-recommemded가 원본 설정파일입니다.
modsecurity.conf-recommemded 원본 파일을 modsecurity.conf 로 복사합니다.
```bash
sudo vim modsecurity.conf
```
![image](https://github.com/user-attachments/assets/cc2ec1ee-fb8f-45be-a8e8-9ec603c5b596)
SecRuleEngine DetectionOnly → SecRuleEngine On 으로 바꿔준 후 :wq!로 저장해줍니다.

## 2. ModSecurity-Nginx 모듈 빌드
### 모듈 클론
```bash
cd ~
git clone --depth 1 https://github.com/SpiderLabs/ModSecurity-nginx.git
```
![image](https://github.com/user-attachments/assets/4b4f5744-6551-4234-a5aa-64f948937eb5)
### Nginx 소스 다운로드 및 압축 해제
```bash
cd ~
wget http://nginx.org/download/nginx-1.24.0.tar.gz
tar zxvf nginx-1.24.0.tar.gz
cd nginx-1.24.0
```
![image](https://github.com/user-attachments/assets/e3317886-6cbd-45cd-851e-a84ad29e3089)
### 모듈 컴파일
```bash
./configure --with-compat --add-dynamic-module=../ModSecurity-nginx
make modules
```
> ```bash
ls objs/ngx_http_modsecurity_module.so
```
