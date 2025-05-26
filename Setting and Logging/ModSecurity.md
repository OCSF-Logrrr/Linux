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
