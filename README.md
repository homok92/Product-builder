# Product Builder for livingtmw.com

이 저장소에는 `livingtmw.com`에서 직접 관리하는 사용자 코드만 저장합니다.
WordPress 코어, 업로드, 서버 설정, 비밀번호 및 호스팅 제공 플러그인은 포함하지 않습니다.

`main` 브랜치의 `site/` 아래 파일이 변경되면 GitHub Actions가 다음 서버 경로로 배포합니다.

```text
site/wp-content/mu-plugins -> /wp-data/wp-content/mu-plugins
```

배포는 기존 서버 파일을 삭제하지 않으며, 저장소에 있는 사용자 파일만 업로드합니다.

필요한 GitHub `production` Environment secrets:

- `SFTP_HOST`
- `SFTP_PORT`
- `SFTP_USERNAME`
- `SFTP_PASSWORD`

전체 서버 파일 백업은 로컬 `backups/`에 보관되며 Git에 커밋되지 않습니다.

