# DNS Server - BIND9
이 문서는 DNS Server(BIND9)

# 목차

- [DNS 서버 설정](#dns-서버-설정)
  - [도메인 구입](#도메인-구입)
  - [Zone 파일 생성 및 등록](#zone-파일-생성-및-등록)
  - [Options 파일 설정](#options-파일-설정)
  - [재시작](#재시작)
- [BIND9 로깅 설정](#bind9-로깅-설정)
  - [로그 파일 생성](#로그-파일-생성)
  - [로그 설정 블록 작성](#로그-설정-블록-작성)
  - [재시작](#재시작)

# DNS 서버 설정

## 도메인 구입
가비아, 후이즈, AWS 등에서 도메인을 구매해줍니다.
> 해당 문서는 가비아를 기준으로 작성되었습니다.
도메인 구입 후 DNS 호스트를 추가하고, 네임 서버를 아래와 같이 수정해주었습니다.

My가비가 > 서비스 관리 > 구매한 도메인의 관리 선택 > DNS 호스트 설정 > 호스트명과 IP 주소 작성 후 적용(최소 2개) > 네임서버 설정 > 네임서버 목록 > 1, 2차까지 작성 후 적용 > 사진과 같은 결과가 최종 설정 결과
![스크린샷 2025-05-26 오후 1 45 13](https://github.com/user-attachments/assets/ddf6e28a-52a3-4a4a-a87f-7c450d79f5fb)
![스크린샷 2025-05-26 오후 1 45 21](https://github.com/user-attachments/assets/5a865dd8-706c-410b-b1f9-995bab5fa167)

해당 과정은 가비아에서 발급받은 도메인의 네임 서버의 위치가 등록한 IP에 있다는 것을 명시해주는 것 입니다.

## Zone 파일 생성 및 등록

`/etc/bind` 경로에 zone 파일을 생성해줍니다.

```bash
sudo vi /etc/bind/db.logrrrrrrr.site.zone
```

이후 해당 파일에 아래의 내용을 추가하여 TTL과 SOA, NS, A 레코드를 설정해줍니다.

```bash
$TTL  604800
@   IN  SOA  ns.logrrrrrrr.site. root.logrrrrrrr.site. (
            2   ; Serial
       604800   ; Refresh
        86400   ; Retry
      2419200   ; Expire
       604800 ) ; Negative Cache TTL
;
@    IN  NS  ns.logrrrrrrr.site.
@    IN  NS  ns2.logrrrrrrr.site.
@    IN  A   3.37.240.136
ns   IN  A   3.37.240.136
ns2  IN  A   3.37.240.136
```

만든 zone 파일을 bind9에서 읽어올 수 있도록 등록해주어야 합니다.
`sudo vi /etc/bind/named.conf.default-zones` 파일을 열고 아래의 내용을 추가합니다.

```bash
zone "logrrrrrrr.site" {
        type master;
        file "/etc/bind/db.logrrrrrrr.site.zone";
};

zone "37.3.in-addr.arpa" {
        type master;
        file "/etc/bind/zones/db.3.37";
};
```

## Options 파일 설정

마지막으로 아래와 같이 `/etc/bind/named.conf.options` 파일을 수정해줍니다.

```bash
options {
        directory "/var/cache/bind";

        // If there is a firewall between you and nameservers you want
        // to talk to, you may need to fix the firewall to allow multiple
        // ports to talk.  See http://www.kb.cert.org/vuls/id/800113

        // If your ISP provided one or more IP addresses for stable
        // nameservers, you probably want to use them as forwarders.
        // Uncomment the following block, and insert the addresses replacing
        // the all-0's placeholder.

        forwarders {
                8.8.8.8;
        };

        allow-query { any; };
        allow-query-cache { any; };

        //========================================================================
        // If BIND logs error messages about the root key being expired,
        // you will need to update your keys.  See https://www.isc.org/bind-keys
        //========================================================================
        dnssec-validation auto;

        listen-on-v6 { any; };
};
```

## 재시작

위의 모든 과정을 거쳐 설정이 끝나면 BIND9를 재시작 해줍니다.

```bash
sudo systemctl restart bind9
```

이후 등록한 도메인을 사용할 수 있는 것을 확인할 수 있습니다.
<img width="1622" alt="스크린샷 2025-05-29 오후 3 36 55" src="https://github.com/user-attachments/assets/e62183b4-ae0c-41d7-a544-1089d1409794" />

# BIND9 로깅 설정
DNS 쿼리 로그와 BIND 서버의 일반 동작 로그를 query.log와 named.log에 설정하였습니다.

## 로그 파일 생성

로그를 저장할 디렉토리와 파일을 생성하고, 권한을 부여했습니다.
```bash
sudo mkdir named
sudo touch /var/log/named/query.log
sudo touch /var/log/named/named.log
sudo chown -R bind:bind /var/log/named
```

## 로그 설정 블록 작성

`/etc/bind/named.conf.local` 파일에 아래의 내용을 추가하였습니다.

```bash
logging {
    channel querylog {
        file "/var/log/named/query.log" versions 10 size 10m;
        severity info;
        print-time yes;
    };
    channel default_log {
        file "/var/log/named/named.log" versions 10 size 10m;
        severity info;
        print-time yes;
    };
    category queries { querylog; };
    category default { default_log; };
    category general { default_log; };
};
```

## 재시작

위의 모든 과정을 거쳐 설정이 끝나면 BIND9를 재시작 해줍니다.

# Reference

- https://velog.io/@gweowe/bind9-%EC%9E%90%EC%B2%B4-DNSDomain-Name-System-%EA%B5%AC%EC%B6%95%ED%95%98%EA%B8%B0-Ubuntu

```bash
sudo systemctl restart bind9
```


