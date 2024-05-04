
# Projeto PHP

Este projeto utiliza PHP, Symfony, Doctrine e o gerenciador de dependências Composer, tudo rodando em cima do Docker com Nginx e PostgreSQL.

# O que é preciso para rodar o projeto?
Para esse projeto é necessário ter o Docker e o Docker Compose instalados na máquina. Para iniciar o docker, é só rodar o comando abaixo:

```bash
docker compose up -d --build
```

Para acessar a aplicação, basta acessar o endereço `http://localhost:8003`.

Para executar algum comando no container, basta rodar o comando abaixo:

```bash
docker compose exec app /bin/bash
```
E você terar acesso ao terminal do container onde está o projeto PHP.

## RabbitMQ

O RabbitMQ é utilizado neste projeto como um broker de mensagens para desacoplar a produção de mensagens (envio de tarefas) da sua consumação (execução de tarefas). Isso permite uma arquitetura mais escalável e resiliente, onde os componentes podem ser escalados independentemente e falhas em um componente não afetam diretamente os outros.

### Configuração

O RabbitMQ é configurado para rodar em um container Docker separado, definido no `docker-compose.yml`. A comunicação com o RabbitMQ é feita através da biblioteca `symfony/amqp-messenger` no projeto PHP, permitindo o envio e recebimento de mensagens de forma eficiente.

### Implementação do Cron

O cron é implementado para garantir que os consumidores de mensagens do RabbitMQ estejam sempre rodando, mesmo após falhas ou reinicializações do sistema. Isso é feito através de um script `run_consumer.sh`, que é executado periodicamente pelo cron no container do projeto.

O cron é configurado no `Dockerfile` do projeto com a seguinte linha:

```Dockerfile
RUN echo "*/10 * * * * root /run_consumer.sh" >> /etc/crontab
```

Isso significa que o script `/run_consumer.sh` é executado a cada 10 minutos pelo usuário `root`. O script deve conter a lógica para iniciar ou reiniciar os consumidores das filas do RabbitMQ, garantindo que eles estejam sempre ativos para processar as mensagens recebidas.

### Validação do envio de e-mail

Para validar o envio do e-mail é utilizado a ferramenta MailHog que está incluida no docker-compose.yml, onde é necessário acessar apenas o http://localhost:8025/ e validar que o e-mail está sendo recebido. Cada e-mail enviado entra na lista da tela inicial assim que acessar, lembrando que o cron roda apenas de tempos e tempos caso queira testar o envio antes do cron é só executar o seguinte comando:

```
docker compose exec app /bin/bashphp bin/console messenger:consume async_priority_high -vv
```

# Testes

Os testes são uma parte crucial do desenvolvimento de software, garantindo que a aplicação funcione conforme esperado e ajudando a prevenir regressões. Este projeto inclui uma suíte de testes automatizados, abrangendo testes unitários e de integração, que validam a lógica de negócios e a integração entre os componentes do sistema.

## Tipos de Testes

- **Testes Unitários**: Focam em testar partes isoladas do código, como funções ou métodos, para garantir que elas funcionem corretamente sob várias condições.

## Como Executar os Testes
Para iniciar o teste é preciso garantir que esteja dentro do bash do container, caso não esteja necessário rodar o seguinte comando:

```bash
docker compose exec app /bin/bash
```

Uma vez dentro do bash do container, para rodar os testes é só executar o seguinte comando:

```bash
    ./vendor/bin/phpunit
```

 Para executar um teste específico, use o caminho do arquivo de teste como argumento:

```bash
    ./vendor/bin/phpunit tests/Path/To/YourTest.php
```

## Validação Automática

Os testes são executados automaticamente como parte do pipeline de CI/CD configurado no projeto, garantindo que cada mudança no código seja validada antes de ser integrada à base de código principal.

Lembre-se de escrever novos testes ou atualizar os existentes ao modificar a aplicação ou adicionar novas funcionalidades, mantendo a suíte de testes relevante e útil.

# Arquitetura e Padrões de Design

Este projeto segue uma abordagem de Domain-Driven Design (DDD) para focar na complexidade do domínio central e promover uma modelagem rica e expressiva do domínio de negócios. A estrutura do código é organizada em torno do domínio do negócio, facilitando a comunicação e a manutenibilidade do sistema. Abaixo, detalhamos como os padrões de design e arquitetura são implementados no projeto:

## Domain-Driven Design (DDD)

- **Entidades e Objetos de Valor**: Modelamos o domínio de negócios usando entidades e objetos de valor, capturando conceitos do domínio e suas relações.
- **Agregados**: Utilizamos agregados para agrupar entidades e objetos de valor que mudam juntos, garantindo a consistência das regras de negócio.

## Data Transfer Objects (DTOs)

- **DTOs** são utilizados para transferir dados entre a camada de apresentação e a camada de serviço, encapsulando os dados de forma eficiente e reduzindo a acoplamento entre as camadas.

## Service Layer

- **Serviços de Domínio**: Separamos a lógica de negócios complexa em serviços de domínio, focados nas regras de negócio, que coordenam a execução de operações de negócio, interagindo com infraestrutura, serviços externos e o domínio.

## Repository Pattern

- **Repositórios**: Abstraímos o acesso a dados usando o padrão Repository, permitindo que a camada de domínio interaja com a base de dados de forma indireta. Isso facilita a substituição da fonte de dados e melhora a testabilidade do código.

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
