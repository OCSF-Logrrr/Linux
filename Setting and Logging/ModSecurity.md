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
```bash
ls objs/ngx_http_modsecurity_module.so
```
![image](https://github.com/user-attachments/assets/5aad8d30-933c-42d8-bae8-f71ebbf57a7c)
성공 시 확인할 수 있는 명령어로, 성공한 것을 확인할 수 있습니다.
### 모듈 설치
```bash
sudo mkdir -p /usr/share/nginx/modules
sudo cp objs/ngx_http_modsecurity_module.so /usr/share/nginx/modules/
```
![image](https://github.com/user-attachments/assets/7ff7cd38-c42a-428b-9973-cde41aa5ece9)

## 3. Nginx에 모듈 연결
### nginx.conf 상단에 추가
```bash
sudo vim /etc/nginx/nginx.conf
```
```bash
load_module /usr/lib/nginx/modules/ngx_http_modsecurity_module.so;
```
![image](https://github.com/user-attachments/assets/a7c6b5ab-c164-4669-80bf-6495452cd20c)
위의 코드를 추가하고 :wq!로 저장해줍니다.
### 사이트 설정 파일 (/etc/nginx/sites-enabled/default)
```bash
modsecurity on;
modsecurity_rules_file /home/ubuntu/ModSecurity/modsecurity.conf;
```
![image](https://github.com/user-attachments/assets/ed5ef22b-2162-46c4-b118-0f32b569471c)
> 위의 설정은 server{ } 블록 내부에 작성해줍니다.

## 4. OWASP CRS 연동
### CRS 다운로드 및 설정 파일 구성
```bash
cd /etc/nginx/
sudo git clone https://github.com/coreruleset/coreruleset.git
cd coreruleset
sudo cp crs-setup.conf.example crs-setup.conf
```
![image](https://github.com/user-attachments/assets/7f67dc33-ed5b-4aff-a139-166e8c9a2191)
### 통합 설정 파일 생성
```bash
sudo vim /etc/nginx/modsecurity-crs.conf
```
```bash
Include /home/ubuntu/ModSecurity/modsecurity.conf
Include /etc/nginx/coreruleset/crs-setup.conf
Include /etc/nginx/coreruleset/rules/*.conf
```
![image](https://github.com/user-attachments/assets/290e1c89-51ea-401e-a598-f34cae01e0e7)
:wq!로 저장해줍니다.
### 사이트 설정 수정
```bash
sudo vim /etc/nginx/sites-enabled/default
```
![image](https://github.com/user-attachments/assets/e145d60d-d4ff-4c5b-bc69-5b5831ff4533)
기존 설정 modsecurity_rules_file /home/ubuntu/ModSecurity/modsecurity.conf; 코드를 modsecurity_rules_file /etc/nginx/modsecurity-crs.conf; 로 한 줄만 변경해줍니다.
> 이제는 단순 modsecurity.conf 대신, CRS 룰셋까지 포함한 conf를 로드할 수 있습니다.

## 5. 설정 적용
```bash
sudo nginx -t
sudo systemctl restart nginx
```

## 6. 로그 확인 및 탐지 결과
### 로그 확인 명령
```bash
sudo tail -f /var/log/modsec_audit.log
```
![image](https://github.com/user-attachments/assets/1fd8dc2e-b106-4819-a12a-331d9671e0d2)
### 탐지 예시 (XSS 시도)
- <script>alert(1)</script> 요청 탐지
- 감지된 룰:
  - 941100 (XSS via libinjection)
  - 941110 (<script> 태그 탐지)
  - 941160 (NoScript-style HTML Injection)
  - 941390 (alert() 함수 탐지)
### 차단 결정 로그
```bash
ModSecurity: Access denied with code 403 (phase 2).
Matched ... against variable `TX:BLOCKING_INBOUND_ANOMALY_SCORE' (Value: `20')
[msg "Inbound Anomaly Score Exceeded (Total Score: 20)"]
```
- 차단 임계치 5 초과 (현재 점수: 20)
- 결과 : 403 Forbidden 반환

## curl 테스트 결과
```bash
curl http://<your-ip>/?q=<script>alert(1)</script>
```
![image](https://github.com/user-attachments/assets/6d2eaa50-729a-48ff-af75-64270b8481ea)
```bash
HTTP/1.1 403 Forbidden
<h1>403 Forbidden</h1>
```
즉, 브라우저 또는 curl로 공격을 시도 했을 때 웹 애플리케이션에 전달되지 않으며 Nginx 앞단에서 ModSecurity가 즉시 차단합니다.

> 이 서버는 이제 실전 환경에서 웹 공격(예: XSS, SQLi, LFI 등)을 탐지하고 차단할 수 있는 WAF 방어선이 완성된 상태입니다.
