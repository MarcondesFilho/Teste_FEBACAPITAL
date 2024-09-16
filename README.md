APIs e Funcionalidades

O projeto inclui uma série de APIs RESTful para realizar operações de CRUD (Create, Read, Update, Delete) em clientes e livros, além de uma autenticação JWT e upload de arquivos para o AWS S3. Abaixo estão as instruções de configuração e uso.

Autenticação

A autenticação usa o padrão JWT (JSON Web Token). Para autenticar-se no sistema, envie as credenciais de login (usuário e senha) para o endpoint de login:

POST /auth/login

O retorno será um token JWT que deve ser utilizado no cabeçalho Authorization de todas as requisições subsequentes:

Authorization: Bearer {seu-token-jwt}

Cadastro de Clientes

Endpoints:

POST /clientes: Cadastra um novo cliente com os seguintes campos:
- nome (string)
- cpf (string, único)
- endereco (texto)
- sexo (M ou F)
- imagem (arquivo, opcional)

Exemplo de requisição:

POST /clientes
{
  "nome": "João Silva",
  "cpf": "123.456.789-10",
  "endereco": "Rua Exemplo, 123",
  "sexo": "M",
  "imagem": [file.jpg]
}

Cadastro de Livros

Endpoints:

POST /livros: Cadastra um novo livro com os seguintes campos:
- isbn (string, único)
- titulo (string)
- autor (string)
- preco (float)
- estoque (int)
- imagem (arquivo, opcional)

Exemplo de requisição:

POST /livros
{
  "isbn": "9783161484100",
  "titulo": "Livro Exemplo",
  "autor": "Autor Exemplo",
  "preco": 59.90,
  "estoque": 100,
  "imagem": [file.jpg]
}

Listagem de Clientes e Livros

Endpoints:

GET /clientes: Retorna uma lista de clientes com suporte a paginação, ordenação e filtros.
GET /livros: Retorna uma lista de livros com suporte a paginação, ordenação e filtros.

Parâmetros de Filtro:

- limit: Limite de resultados.
- offset: Deslocamento dos resultados.
- orderBy: Ordenação por nome, cpf, titulo ou preco.
- filterBy: Filtro para busca por nome, cpf, titulo, autor, ou isbn.

Exemplo:

GET /clientes?limit=10&offset=0&orderBy=nome&filterBy=João

GET /livros?limit=10&offset=0&orderBy=titulo&filterBy=Autor Exemplo

Upload de Imagens

As imagens são armazenadas no AWS S3. Para realizar o upload de uma imagem ao cadastrar um cliente ou livro, utilize o campo imagem no POST conforme mostrado nas seções anteriores.
