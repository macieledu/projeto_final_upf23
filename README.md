# CineConnect - README

Bem-vindo ao projeto CineConnect! Aqui estão os passos para configurar e executar o projeto localmente.

## Pré-requisitos
- Servidor Apache
- MySQL

Certifique-se de que você tenha o Apache e o MySQL instalados em sua máquina antes de prosseguir. Xampp sugerido.

## Passos de Configuração

### 1. Atualização do Banco de Dados
Execute as seguintes linhas no banco de dados para garantir que esteja atualizado, caso tenha uma versão antiga do banco criada:
```sql
-- ALTER TABLE reviews
DROP FOREIGN KEY reviews_ibfk_2;

ALTER TABLE reviews
ADD FOREIGN KEY (movies_id) REFERENCES movies(id) ON DELETE CASCADE;
```

### 2. Criação do Banco de Dados
Utilize o arquivo `dbase.sql` para criar o banco.

### 3. Ajuste das Credenciais do Banco
Abra o arquivo `db.php` e insira suas credenciais do banco de dados:
```php
<?php

$db_name = "cineconnect";
$db_host = "";
$db_user = "";
$db_pass = "";
