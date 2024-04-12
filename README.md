
# Projeto PHP

Este projeto utiliza PHP e o gerenciador de dependências Composer.

# Estrutura do Projeto

O projeto segue o padrão de design Domain-Driven Design (DDD) e os princípios SOLID. A estrutura de pastas do projeto é organizada da seguinte forma:

- `src`: Este diretório contém todo o código-fonte do projeto.
  - `Application`: Este diretório contém as classes que são usadas para transferir dados entre as camadas do projeto.
  - `Domain`: Este diretório contém as classes de domínio que encapsulam a lógica de negócios do projeto.
  - `Infrastructure`: Este diretório contém as classes que fornecem serviços de infraestrutura, como a comunicação com o banco de dados.
  - `Presentation`: Este diretório contém as classes que lidam com a apresentação dos dados ao usuário.

## Padrões de Projeto

O projeto utiliza vários padrões de projeto para organizar o código e facilitar a manutenção e a extensibilidade. Alguns dos padrões de projeto utilizados incluem:

- **Chain of Responsibility**: Este padrão é usado para processar as solicitações HTTP. Cada Handler na cadeia é responsável por processar uma solicitação específica. Se um Handler não pode processar a solicitação, ele passa para o próximo Handler na cadeia.
- **Repository Pattern**: Este padrão é usado para abstrair a lógica de acesso aos dados. Cada Repository é responsável por lidar com as operações de banco de dados para uma entidade específica.
- **Service Pattern**: Este padrão é usado para encapsular a lógica de negócios do aplicativo. Cada Service é responsável por executar uma tarefa de negócios específica.

### HandlerChainBuilder

A classe `HandlerChainBuilder` é responsável por construir a cadeia de manipuladores. Ela recebe um conjunto de manipuladores e os conecta em uma cadeia.

### AbstractHandler

A classe `AbstractHandler` é a classe base para todos os manipuladores. Ela define o método `handle` que é usado para processar uma solicitação. Se um manipulador não pode processar a solicitação, ele passa para o próximo manipulador na cadeia.

## Controladores

Os controladores são responsáveis por lidar com as solicitações HTTP e retornar uma resposta. Eles usam a cadeia de manipuladores para processar a solicitação.

### TransacaoController

O `TransacaoController` tem dois métodos:

- `transferenciaAction`: Este método é responsável por lidar com solicitações de transferência. Ele cria um `TransferenciaTransacaoRequest` e passa para a cadeia de manipuladores para processamento.
- `depositoAction`: Este método é responsável por lidar com solicitações de depósito. Ele cria um `DepositoTransacaoRequest` e passa para a cadeia de manipuladores para processamento.

### UsuarioController

O `UsuarioController` tem dois métodos:

- `criarUsuarioAction`: Este método é responsável por lidar com solicitações para criar um novo usuário. Ele cria um `CriarUsuarioRequest` e passa para a cadeia de manipuladores para processamento.
- `atualizarUsuarioAction`: Este método é responsável por lidar com solicitações para atualizar um usuário existente. Ele cria um `AtualizarUsuarioRequest` e passa para a cadeia de manipuladores para processamento.

## Fluxo dos Handlers

Os Handlers no projeto seguem o padrão de design Chain of Responsibility. Cada Handler é responsável por processar uma solicitação específica. Se um Handler não pode processar a solicitação, ele passa para o próximo Handler na cadeia.

### HandlerChainBuilder

A classe `HandlerChainBuilder` é responsável por construir a cadeia de Handlers. Ela recebe um conjunto de Handlers e os conecta em uma cadeia. O método `buildChain` é usado para construir a cadeia. Ele percorre a lista de Handlers e configura o próximo Handler para cada Handler na lista.

### AbstractHandler

A classe `AbstractHandler` é a classe base para todos os Handlers. Ela define o método `handle` que é usado para processar uma solicitação. Se um Handler não pode processar a solicitação, ele passa para o próximo Handler na cadeia. Isso é feito chamando o método `handle` do próximo Handler.

### Fluxo de uma Solicitação

Quando uma solicitação é recebida, ela é passada para o primeiro Handler na cadeia. O Handler verifica se pode processar a solicitação. Se puder, ele processa a solicitação e retorna o resultado. Se não puder, ele passa a solicitação para o próximo Handler na cadeia.

Este processo continua até que a solicitação seja processada ou todos os Handlers na cadeia tenham tentado processar a solicitação. Se nenhum Handler puder processar a solicitação, a cadeia retornará null.

### Exemplo de Uso

No `TransacaoController` e no `UsuarioController`, a cadeia de Handlers é usada para processar as solicitações de transferência, depósito, criação de usuário e atualização de usuário. A solicitação é criada e passada para o método `handle` do primeiro Handler na cadeia. O resultado do processamento é então retornado como resposta HTTP.

## Services

Os Services são responsáveis por executar a lógica de negócios do aplicativo. Eles são chamados pelos Controllers e podem interagir com o banco de dados, outros serviços, APIs externas, etc.

### UsuarioService

O `UsuarioService` é responsável pela lógica de negócios relacionada aos usuários. Ele tem dois métodos principais:

- `criarUsuario`: Este método é responsável por criar um novo usuário. Ele recebe um `UsuarioDTO`, valida os dados do usuário, cria um novo usuário no banco de dados e retorna o usuário criado.
- `atualizarUsuario`: Este método é responsável por atualizar um usuário existente. Ele recebe um `UsuarioDTO`, valida os dados do usuário, atualiza o usuário no banco de dados e retorna o usuário atualizado.

### TransacaoService

O `TransacaoService` é responsável pela lógica de negócios relacionada às transações. Ele tem dois métodos principais:

- `transferencia`: Este método é responsável por realizar uma transferência entre dois usuários. Ele recebe um `TransacaoDTO`, valida os dados da transação, realiza a transferência no banco de dados e retorna a transação realizada.
- `deposito`: Este método é responsável por realizar um depósito para um usuário. Ele recebe um `TransacaoDTO`, valida os dados da transação, realiza o depósito no banco de dados e retorna a transação realizada.

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

