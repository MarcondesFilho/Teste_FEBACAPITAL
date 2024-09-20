<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>

Yii 2 Basic Project Template é um projeto esqueleto [Yii 2](https://www.yiiframework.com/) ideal para a criação rápida de pequenos projetos.

O template contém os recursos básicos, incluindo login/logout de usuários e uma página de contato.
Ele inclui todas as configurações comumente usadas que permitem que você se concentre em adicionar novos
recursos à sua aplicação.

DIRECTORY STRUCTURE
-------------------
    assets/             contém definições de assets
    commands/           contém comandos de console (controllers)
    config/             contém as configurações da aplicação
    controllers/        contém classes de controllers Web
    mail/               contém arquivos de visualização para e-mails
    models/             contém classes de modelos
    runtime/            contém arquivos gerados durante o runtime
    services/           contém os serviços desacoplados dos controllers
    tests/              contém vários testes para a aplicação
    vendor/             contém pacotes de dependências de terceiros
    views/              contém arquivos de visualização para a aplicação Web
    web/                contém o script de entrada e recursos Web

REQUISITOS
------------
- PHP 7.4 ou superior
- Composer
- MySQL
- AWS S3 (para upload de imagens)
- Dependências PHP (extensões necessárias): ext-json, ext-pdo_mysql

INSTALAÇÃO
------------

### Instalação via Composer

Instalar dependências:

Execute o comando abaixo para instalar as dependências do projeto:
~~~
composer install
~~~

Agora você poderá acessar a aplicação pela seguinte URL, assumindo que `basic` seja o diretório
diretamente sob a raiz do servidor Web.

~~~
http://localhost/basic/web/
~~~

### Instalação com Docker

Atualize seus pacotes de dependências:

    docker-compose run --rm php composer update --prefer-dist

Execute os triggers de instalação (criando o código de validação de cookie):

    docker-compose run --rm php composer install    

Inicie o container:

    docker-compose up -d

Agora você poderá acessar a aplicação pela seguinte URL:

    http://127.0.0.1:8000

CONFIGURAÇÃO
-------------

### Banco de Dados

Edite o arquivo `config/db.php` com os dados reais, por exemplo:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=biblioteca',
    'username': 'root',
    'password': '',
    'charset': 'utf8',
];

**NOTAS:**

O Yii não criará o banco de dados para você. Isso deve ser feito manualmente antes de acessar a aplicação.
~~~
php yii database/create
~~~
Verifique e edite os outros arquivos no diretório config/ para customizar sua aplicação conforme necessário.


### Migrações
Para criar as tabelas no banco de dados, execute as migrações com o comando:
~~~
php yii migrate
~~~

### Criação de Usuario via Console
Para criar os usuários no banco de dados, execute o comando:
~~~
php yii usuario/create [login] [senha] [nome] 

Exemplo:
php yii usuario/create adm adm123 "Admin User"   
~~~

DOCUMENTAÇÃO DAS APIS
-------------

### Autenticação

    POST /api/login
    Exemplo de request:
    {
        "login": "adm",
        "senha": "adm123"
    }

    Resposta em caso de sucesso:
    {
        "token": "Bearer <token>"
    }

### Criação de Usuário

    POST /api/register
    Exemplo de request:
    {
        "login": "novo_user",
        "senha": "senha123",
        "nome": "Novo Usuário"
    }

    Resposta em caso de sucesso:
    {
        "message": "Usuário criado com sucesso"
    }

### Cadastro de Clientes

    POST /api/clientes
    Exemplo de request:
    {
        "nome": "João Silva",
        "cpf": "12345678909",
        "endereco": "Rua A, 123, Bairro, Cidade, Estado",
        "sexo": "M"
    }

    Resposta em caso de sucesso:
    {
        "message": "Cliente cadastrado com sucesso"
    }

### Listar Clientes

    GET /api/clientes

    Exemplo de request:
    GET /api/clientes?limit=10&offset=0

    Resposta em caso de sucesso:
    {
        "data": [
            {
                "id": 1,
                "nome": "João Silva",
                "cpf": "12345678909",
                "endereco": "Rua A, 123, Bairro, Cidade, Estado",
                "sexo": "M"
            },
            {
                "id": 2,
                "nome": "Maria Souza",
                "cpf": "98765432100",
                "endereco": "Rua B, 456, Bairro, Cidade, Estado",
                "sexo": "F"
            }
        ]
    }

### Visualizar Cliente

    GET /api/clientes/<id>

    Exemplo de request:
    GET /api/clientes/1

    Resposta em caso de sucesso:
    {
        "id": 1,
        "nome": "João Silva",
        "cpf": "12345678909",
        "endereco": "Rua A, 123, Bairro, Cidade, Estado",
        "sexo": "M"
    }

### Cadastro de Livros

    POST /api/livros
    Exemplo de request:
    {
        "isbn": "978-3-16-148410-0",
        "titulo": "O Senhor dos Anéis",
        "autor": "J.R.R. Tolkien",
        "preco": 99.90,
        "estoque": 10
    }

    Resposta em caso de sucesso:
    {
        "message": "Livro cadastrado com sucesso"
    }

### Listar Livros

    GET /api/livros

    Exemplo de request:
    GET /api/livros?limit=10&offset=0

    Resposta em caso de sucesso:
    {
        "data": [
            {
                "id": 1,
                "isbn": "978-3-16-148410-0",
                "titulo": "O Senhor dos Anéis",
                "autor": "J.R.R. Tolkien",
                "preco": 99.90,
                "estoque": 10
            },
            {
                "id": 2,
                "isbn": "978-3-16-148410-1",
                "titulo": "Harry Potter",
                "autor": "J.K. Rowling",
                "preco": 79.90,
                "estoque": 5
            }
        ]
    }

### Visualizar Livro

    GET /api/livros/<id>

    Exemplo de request:
    GET /api/livros/1

    Resposta em caso de sucesso:
    {
        "id": 1,
        "isbn": "978-3-16-148410-0",
        "titulo": "O Senhor dos Anéis",
        "autor": "J.R.R. Tolkien",
        "preco": 99.90,
        "estoque": 10
    }


