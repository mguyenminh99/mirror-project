# x-shopping-st

## 開発環境構築

### システム要件

- Linux(amdアーキテクチャ)
- docker
- docker compose

コンテナの作成
```shell-sessions
git clone git@gitlab.true-inc.jp:true-inc/x-shopping-st/x-shopping-st.git
```

開発環境コンテナ作成
```shell-sessions
sh create-container.sh
```

magento2インストール
```shell-sessions
sh install-magento.sh
```
