
# Projeto PHP

Este projeto utiliza PHP, Symfony, Doctrine e o gerenciador de dependências Composer, tudo rodando em cima do Docker com Nginx e PostgreSQL.

# O que é preciso para rodar o projeto?
Para esse projeto é necessário ter o Docker e o Docker Compose instalados na máquina. Para iniciar o docker, é s'ó rodar o comando abaixo:

```bash
docker-compose up -d --build
```

Para acessar a aplicação, basta acessar o endereço `http://localhost:8003`.

Para executar algum comando no container, basta rodar o comando abaixo:

```bash
docker-compose exec app /bin/bash
```
E você terar acesso ao terminal do container onde está o projeto PHP.

# Estrutura do Projeto

O projeto segue o padrão de design Domain-Driven Design (DDD) e os princípios SOLID. A estrutura de pastas do projeto é organizada da seguinte forma:

- `src`: Este diretório contém todo o código-fonte do projeto.
  - `Application`: Este diretório contém as classes que são usadas para transferir dados entre as camadas do projeto.
  - `Domain`: Este diretório contém as classes de domínio que encapsulam a lógica de negócios do projeto.
  - `Infrastructure`: Este diretório contém as classes que fornecem serviços de infraestrutura, como a comunicação com o banco de dados.
  - `Presentation`: Este diretório contém as classes que lidam com a apresentação dos dados ao usuário.

## Arquitetura e Padrões de Design
Este projeto segue a arquitetura de Service Layer, que é dividida em duas partes: Application Services e Domain Services. Além disso, o projeto também implementa o padrão de design Facade.

Os Facades, localizados na camada `Application`, atuam como uma interface simplificada para os serviços de domínio. Eles fornecem uma maneira de esconder a complexidade dos serviços de domínio e expor uma interface mais simples para os clientes.

Aqui está um resumo de como as camadas interagem:

1. O `UsuarioController` (na Presentation Layer) recebe uma solicitação HTTP e chama o método apropriado no `UsuarioFacede` (na Application Layer).

2. O `UsuarioFacede` (na Application Layer) delega a chamada para o método apropriado no `UsuarioService` (no Domain Layer).

3. O `UsuarioService` (no Domain Layer) executa a lógica de negócios e interage com o banco de dados através do EntityManager.

Este é um padrão comum em aplicações que seguem o Domain-Driven Design (DDD) e é uma boa maneira de separar as responsabilidades e manter o código organizado e fácil de manter.

Além da Service Layer e do padrão Facade, este projeto também utiliza outros padrões de design, como o Repository Pattern e o DTO Pattern. Para mais detalhes sobre esses padrões e como eles são usados no projeto, consulte as seções relevantes deste README.

## Padrão das Resquisições 
Modelos de requisição com os métodos HTTP correspondentes para cada ação do controlador:

Para o `TransacaoController`:

1. Método de requisição para `transferenciaAction`:

```http
POST /transferencia
Content-Type: application/json

{
    "cpfCnpjRemetente": "string",
    "cpfCnpjDestinatario": "string",
    "valor": "float"
}
```

2. Método de requisição para `depositoAction`:

```http
POST /deposito
Content-Type: application/json

{
    "cpfCnpjRemetente": "string",
    "valor": "float"
}
```

Para o `UsuarioController`:

1. Método de requisição para `criarUsuarioAction`:

```http
POST /criar-usuario
Content-Type: application/json

{
    "nome": "string",
    "cpfCnpj": "string",
    "email": "string",
    "senha": "string",
    "isLogista": "boolean"
}
```

2. Método de requisição para `atualizarUsuarioAction`:

```http
PUT /atualizar-usuario/{id}
Content-Type: application/json

{
    "nome": "string",
    "cpfCnpj": "string",
    "email": "string",
    "senha": "string",
    "isLogista": "boolean"
}
```

Por favor, substitua "string", "float", "boolean" e "{id}" pelos valores reais que você deseja enviar na solicitação.
