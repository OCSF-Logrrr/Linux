# 🛡️ Suricata IDS 탐지 및 실습

Ubuntu 환경에서 Suricata를 설치하고, 커스텀 룰셋을 이용해 웹 기반 공격(SQL Injection, XSS 등)을 탐지합니다.

<p align="center">
  <img src="results/SQLi_detected.png" width="800" alt="SQLi 탐지 예시">
</p>

---

## 📌 프로젝트 개요

- **IDS(침입 탐지 시스템)** 이해 및 실습
- Suricata를 통한 **패킷 분석 및 공격 탐지**
- **커스텀 룰셋**을 이용한 탐지 정확도 향상
- `eve.json` 로그 확인 및 시각화 가능 구조 구성

---

## 📂 목차

1. [환경 및 사전 조건](#1-환경-및-사전-조건)
2. [Suricata 설치](#2-suricata-설치)
3. [설정 파일 구성](#3-설정-파일-구성)
4. [커스텀 룰셋 작성](#4-커스텀-룰셋-작성)
5. [공격 시뮬레이션 및 테스트](#5-공격-시뮬레이션-및-테스트)
6. [탐지 결과 보기](#6-탐지-결과-보기)
7. [파일 구성](#7-파일-구성)
8. [참고 자료](#8-참고-자료)

---

## 1. 환경 및 사전 조건
| 항목 | 내용 |
|------|------|
| OS | Ubuntu 22.04 |
| Suricata | 7.0.10 |
| 테스트 도구 | 브라우저 |
| 실습 인터페이스 | enX0 |

## 2. Suricata 설치
```bash
sudo add-apt-repository ppa:oisf/suricata-stable
sudo apt update
sudo apt install -y suricata suricata-update jq
```
#### 설치확인:
```
suricata --build-info
```

## 3. 설정 파일 구성

### 인터페이스 설정 (`/etc/suricata/suricata.yaml`)

```
af-packet:
 - interface: enX0
   cluster-id: 99
   defrag: yes
```

### 룰셋 등록

```
rule-files:
  - SQL_Injection.rules
  - XSS.rules
```

## 4. 커스텀 룰셋 작성
자세한 내용은 `rules/SQL_Injection.rules` 참조

예시 - SQL Injection 탐지 룰:
```
alert http any any -> any any (\
    msg:"SQL 인젝션 시도 탐지 – ' OR 1=1 조건문 항상 참 (URL)";\
    flow:established,to_server;\
    uricontent:"' OR 1=1--";\
    nocase; \
    classtype:web-application-attack;\
    priority:2;\
    sid:1000000;\
    rev:1;\
)
```
## 5. 공격 시뮬레이션 및 테스트

`curl`을 사용해 SQLi 또는 XSS 요청을 보낸 후 로그 확인:
```bash
curl -G http://ns.logrrrrrrr.site/webapi/board/search.php?search=1%27+or+1%3D1--+
```

설정 테스트 및 Suricata 실행:
```bash
sudo suricata -T -c /etc/suricata/suricata.yaml -v
sudo systemctl restart suricata
```

## 6. 탐지 결과 보기
```
cd /var/log/suricata
tail -f eve.json | jq
```
jq 명령어를 통해 json 파일을 깔끔하게 볼 수 있다.
- [🖼️ SQL Injection 탐지 화면](results/sqli_detected.png)
- [🖼️ XSS 탐지 화면](results/xss_detected.png)

## 7. 파일 구성
```bash
Suricata
├── README.md
├── suricata.yaml
├── rules/
│   └── SQL_Injection.rules
│   └── XSS.rules
├── results/
│   ├── sqli_detected.png
│   └── xss_detected.png
```

## 8. 참고 자료
- [Suricata 공식 문서 (docs.suricata.io)](https://docs.suricata.io/)
- [Suricata 룰셋 문법 가이드](https://docs.suricata.io/en/latest/rules/)
- [Suricata Log Format - eve.json 구조](https://docs.suricata.io/en/latest/output/eve/eve-json-output.html)
