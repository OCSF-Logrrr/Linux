# 🛡️ Suricata

이 프로젝트는 **공격 시나리오에 존재하는 다양한 공격에 대한 로그를 수집 및 탐지**하기 위해  
침입 탐지 시스템인 **Suricata**를 활용한 룰셋과 설정 파일을 정리한 저장소입니다.

웹 서버와 데이터베이스(DB) 서버 환경에 맞춰 Suricata 룰셋을 구성하고,  
각 환경별로 탐지 목적에 따라 분리하여 관리합니다.

---

## 디렉터리 구조
```
Suricata/
├── web_suricata/    # 웹 서버용 Suricata 설정 및 룰셋     
│   ├── rules/            
│   │   └── SQL_Injection.rules
│   │   └── XSS.rules
│   │   └── webshell_access.rules
│   ├── suricata.yaml    
│   └── README.md       
│
├── db_suricata/     # DB 서버용 Suricata 설정 및 룰셋   
│   ├── rules/           
│   │   └── mysql_access.rules 
│   ├── suricata.yaml    
│   └── README.md         
│
└── README.md             
```

---

## 📄 하위 룰셋 설명

### 🔹 [web_suricata](./web_suricata/README.md)
웹 서버 환경에서 발생하는 공격을 탐지하기 위한 Suricata 룰셋을 포함합니다.

- ✅ SQL Injection (SQLi)
- ✅ Cross-site Scripting (XSS)
- ✅ WebShell 업로드 및 실행

---

### 🔹 [db_suricata](./db_suricata/README.md)
데이터베이스 서버에 대한 접근 시도를 탐지하기 위한 Suricata 룰셋을 포함합니다.

- ✅ 외부에서 MySQL 서버(포트 3306)로의 비인가 접속 시도

> 각 디렉터리 내 `README.md` 파일에서 탐지 방식, 테스트 명령어, 로그 예시 등을 확인할 수 있습니다.
