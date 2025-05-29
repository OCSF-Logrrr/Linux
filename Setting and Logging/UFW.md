# Firewall - UFW

# 목차

- [로그 활성화](#로그-활성화)
- [Reference](#reference)

# 로그 활성화
`/etc/rsyslog.d/20-ufw.con` 파일에서 맨 아랫줄에 `& stop` 부분의 주석을 제거해주면 로깅이 활성화 됩니다.
![스크린샷 2025-05-26 오후 11 48 46](https://github.com/user-attachments/assets/941252d1-91a2-4e0b-a4e1-5381fd6ceaf9)

해당 로그는 `/var/log/ufw.log`에 기록됩니다.

# Reference

- https://mytory.net/archives/13142
