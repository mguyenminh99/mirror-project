# x-shopping-st

## 開発環境構築

### システム要件

- Linux(amdアーキテクチャ)
- docker
- docker compose

ソースコードクローン
```shell-sessions
git clone git@gitlab.true-inc.jp:true-inc/x-shopping-st/x-shopping-st.git
```

dockerディレクトリに移動
```shell-sessions
cd x-shopping-st/dev/docker
```

開発環境コンテナ作成
```shell-sessions
sh create-container.sh
```

magento2インストール
```shell-sessions
sh install-magento.sh
```
