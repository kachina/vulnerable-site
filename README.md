# vulnerable-site

## Amazon Linux 2 への環境構築手順

### パッケージのインストール、アップデート

```
$ sudo yum update -y
$ sudo yum install -y git httpd php postgresql-server php-pgsql
$ sudo systemctl enable httpd.service
$ sudo systemctl enable postgresql.service
$ sudo reboot
```

### PostgreSQL の初期化

```
$ sudo service postgresql initdb
$ sudo systemctl start postgresql
$ sudo su postgres
$ psql
postgres=# create database foo_db;
postgres=# \c foo_db;
foo_db=# create table books (id serial PRIMARY KEY, name text NOT NULL, created_at timestamp NOT NULL, updated_at timestamp NOT NULL);
foo_db=# insert into books (name, created_at, updated_at) values ('foo', now(), now());
foo_db=# create table users (id serial PRIMARY KEY, name text NOT NULL, password text NOT NULL, created_at timestamp NOT NULL, updated_at timestamp NOT NULL);
foo_db=# insert into users (name, password, created_at, updated_at) values ('hoge@example.com', 'abcdeabecd', now(), now());
foo_db=# insert into users (name, password, created_at, updated_at) values ('fuga@example.com', '1230981230', now(), now());
foo_db=# \q
```

設定ファイルの編集

```
$ sudo vim /var/lib/pgsql/data/pg_hba.conf
```

↓この `peer` の部分を `trust` に書き換えます。

```
# "local" is for Unix domain socket connections only
#local   all             all                                     peer
local   all             all                                     trust
```

### デプロイ

```
$ sudo mv /var/www/html /var/www/html_org
$ sudo git clone https://github.com/kachina/vulnerable-site.git /var/www/html
$ sudo chmod 777 /var/www/html/csrf/bbs.csv
```

httpd, PostgerSQL の再起動

```
$ sudo systemctl restart httpd
$ sudo systemctl restart postgresql
```
