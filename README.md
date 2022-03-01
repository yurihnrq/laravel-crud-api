# 📝 Sobre este projeto

Este projeto foi construído como parte do processo seletivo para estagiário em desenvolvimento web/mobile da empresa [NewM](https://newm.com.br/).

O processo consiste em duas partes:

- Construir uma API (back-end) em PHP para o CRUD de usuários;
- Desenvolver uma interface (frond-end) que realize o consumo da API.

Este repositório contém o código do back-end e foi desenvolvido utilizando [Laravel](https://laravel.com/). Além disso, foi utilizado [PostgreSQL](https://www.postgresql.org/) como banco de dados.

## ⚙️ Instalação local

Para instalar e executar este projeto em sua máquina local, execute os seguintes passos:

1) Instale e configure o PHP (preferível a versão 7.4.3) na sua máquina. Você pode verificar o manual oficial para realizar a instalação [clicando aqui](https://www.php.net/manual/pt_BR/install.php);
2) Instale e configure o composer. Para realizar a instalação, acesse o [site oficial](https://getcomposer.org/download/);
3) Faça download deste repositório ou clone-o com Git (caso não o tenha instalado, ele pode ser baixado no [site oficial](https://git-scm.com/)):

```bash
git clone https://github.com/yurihnrq/laravel-crud-api.git;
```

4) Entre na pasta do repositório com o seu terminal e execute o comando:

```bash
composer install;
```

5) Baixe e instale o PostgreSQL. Para realizar o download, [clique aqui](https://www.postgresql.org/download/).

6) Crie o banco de dados e importe a tabela utilizando o arquivo db.sql que está na raiz deste projeto com o seguinte comando:

```bash
psql -U <seu_usuário_postgres> -c 'CREATE DATABASE php_crud';
psql -U <seu_usuário_postgres> -d php_crud < <caminho_para_db.sql>;
```

7) Crie um arquivo .env na raiz do projeto, copie para ele o conteúdo do arquivo .env.example preencha modifique as variáveis que iniciam com DB como a seguir:

```bash
DB_CONNECTION=pgsql
# Você deve colocar o host do seu banco de dados, caso não seja 127.0.0.1
DB_HOST=127.0.0.1 
# Você deve colocar a porta do seu banco de dados, caso não seja 5432
DB_PORT=5432
DB_DATABASE=php_crud
DB_USERNAME=<seu_usuário_postgres>
DB_PASSWORD=<senha_usuário_postgres>
```

8) Agora basta inicializar o servidor com o comando:
```bash
# Tenha certeza que a porta 8000 não está ocupada, pois é a porta utilizada pela aplicação front-end.
php artisan serve;
```
