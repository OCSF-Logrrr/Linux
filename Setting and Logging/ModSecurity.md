# WAF - ModSecurity + OWASP CRS

> Apache 서버에 ModSecurity WAF 와 OWASP Core Rule Set(CRS)를 연동하여 실전 공격 탐지 및 차단 기능 활성화합니다.

# 1. Apache ModSecurity 설치
```bash
 sudo apt install libapache2-mod-security2
```
![image](https://github.com/user-attachments/assets/d89079b8-0eb3-4d87-a8ce-31a45e201f81)

## ModSecurity 설정 파일 복사 및 수정
```bash
sudo cp /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf
sudo vim /etc/modsecurity/modsecurity.conf
```
![image](https://github.com/user-attachments/assets/6093555e-02e4-447e-a712-e1606fe89bc5)

SecRuleEngine DetectionOnly → On을 수정한 후 :wq! 입력 후 빠져나옵니다.

### security2 모듈 활성화
```bash
sudo a2enmod security2
```
![image](https://github.com/user-attachments/assets/521c12d7-608e-442b-a9ed-372f1facfeae)

SecRuleEngine DetectionOnly → On을 수정한 후 :wq! 입력 후 빠져나옵니다.

### Apache 설정에서 Include 설정
```bash
sudo vim /etc/apache2/sites-enabled/000-default.conf
```
![image](https://github.com/user-attachments/assets/b2e4a70a-dc21-428e-ad88-3d619d35072f)
```bash
    <Directory /var/www/html>
        Require all granted
    </Directory>
```
위의 내용을 추가한 후 :wq! 입력 후 엔터로 빠져나옵니다.

## Apache 재시작
```bash
sudo systemctl restart apache2
```

## ModSecurity 동작 확인
```bash
sudo apachectl -M | grep security2
```
![image](https://github.com/user-attachments/assets/f15cf7cb-196e-4150-b1fa-e8980d35b370)

security2_module (shared) 가 나오면서 성공적으로 활성화 완료 했음을 알 수 있습니다.

# OWASP Core Rule Set 설치
## 기존 APT 설치된 CRS 비활성화
```bash
sudo rm -rf /usr/share/modsecurity-crs
```

## OWASP CRS 다운로드
```bash
cd /etc/modsecurity
sudo git clone https://github.com/coreruleset/coreruleset.git
```
![image](https://github.com/user-attachments/assets/ad7714d7-5c60-469f-bbf1-c94c79b98394)

/etc/modsecurity/coreruleset 디렉토리가 생깁니다.

## CRS 기본 설정 적용
```bash
cd /etc/modsecurity/coreruleset
sudo cp crs-setup.conf.example crs-setup.conf
```
![image](https://github.com/user-attachments/assets/a7bb493b-0552-42d3-b2fd-9534909698af)

## Apache에 CRS 룰 포함시키기
Apache에서 modsecurity.conf 안에서 룰 디렉터리와 세트 파일들을 포함해야 합니다.
```bash
sudo vim /etc/modsecurity/modsecurity.conf
```
```bash
IncludeOptional /etc/modsecurity/coreruleset/crs-setup.conf
IncludeOptional /etc/modsecurity/coreruleset/rules/*.conf
```
![image](https://github.com/user-attachments/assets/58d773e3-8746-4e0b-9fb3-404b49d6d135)

맨 아래에 위의 코드 두 줄을 추가하고 :wq! 입력 후 엔터로 빠져나옵니다.
> Include 위치는 SecRuleEngine On 아래쪽, 마지막에 추가해도 무방합니다.

## 권한 확인
```bash
sudo chown -R www-data:www-data /etc/modsecurity/coreruleset
```
## Apache 재시작
```bash
sudo apachectl configtest
sudo systemctl restart apache2
```
![image](https://github.com/user-attachments/assets/55ece8cf-c8c0-44d9-b53d-e1f681e4c4df)

Syntax OK가 정상적으로 뜬 것을 확인할 수 있으며 설치가 성공됐음을 알 수 있습니다.

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
