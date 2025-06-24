# 🌐 Web Suricata 룰셋

이 디렉터리는 웹 서버를 대상으로 한 공격을 탐지하기 위한 Suricata 룰셋과 설정 파일을 포함합니다.

주요 탐지 대상은 SQL Injection, Cross-site Scripting(XSS), 웹쉘 업로드 및 실행입니다.

---

## 🔍 탐지 항목 설명

| 항목 | 설명 |
|------|------|
| **SQL Injection (SQLi)** | URL 또는 POST 파라미터에서 SQL 조건 우회, DB 구조 유출 시도 탐지 |
| **XSS** | `<script>`, 이벤트 핸들러 등 반사형/저장형 XSS 코드 삽입 탐지 |
| **WebShell 실행** | 업로드된 `.php`, `.jsp` 등의 웹쉘 파일 실행 요청 탐지 (`cmd=` 포함) |

---

## 🧪 테스트 시나리오 예시

> 아래는 Suricata 룰셋이 정상적으로 작동하는지 확인하기 위한 테스트 요청 예시입니다.  
> 실습용 웹 서버 또는 로컬 환경에서 사용해 주세요.

---

### 🔹 SQL Injection (SQLi)

1. 웹사이트 접속: http://your-webserver.com/
2. **게시글 검색**에 다음 입력: ' or 1=1--
3. 결과: 모든 게시글이 조회됨 -> SQL 조건 우회 성공

### 🔹 Cross-site Scripting (XSS)

1. 웹사이트 접속: http://your-webserver.com/board/write.php
2. **내용 입력란**에 다음 내용 입력 후 게시:
```
<script>alert(document.domain)</script>
```
3. 다시 해당 게시글을 클릭 -> 알림창(`alert`)이 뜨면 탐지 성공

### 🔹 WebShell 실행

1. 웹쉘 파일(test.php) 업로드
2. 업로드된 경로에 접속: http://your-webserver.com/files/test.php?cmd=id
3. 브라우저에 `uid=33(www-data)` 등의 결과가 보이면 실행 성공

## 📄 참고

- 룰셋은 `rules/` 디렉터리 내에서 유형별로 구분되어 관리됩니다.
- `suricata.yaml` 파일은 웹 서버 환경에서 Suricata가 해당 룰셋을 불러오고 동작하도록 설정된 구성 파일입니다.
- 탐지 결과는 기본적으로 `eve.json` 파일로 출력되며, `outputs:` 설정을 통해 로그 포맷과 저장 경로를 변경할 수 있습니다.
- 실습용 웹 서버 환경에서 테스트되었으며, 운영 환경 적용 시 룰셋 및 설정을 반드시 검토 후 적용해야 합니다.
