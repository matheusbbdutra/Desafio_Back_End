
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

Este projeto é estruturado seguindo a arquitetura de camadas, inspirada nos princípios do Domain-Driven Design (DDD) e nos padrões SOLID, para promover um design de software limpo e modular. Abaixo está uma descrição de como a arquitetura e os padrões de design são aplicados:

- **Camada de Aplicação (`Application`)**: Esta camada atua como um ponto de entrada para a lógica de negócios, orquestrando o fluxo de dados entre a interface do usuário e a camada de domínio. Ela contém serviços de aplicação que coordenam a execução de operações específicas do domínio, transferindo dados entre as camadas através de Data Transfer Objects (DTOs).

- **Camada de Domínio (`Domain`)**: O coração do projeto, esta camada encapsula a lógica e as regras de negócios essenciais. Ela é composta por entidades, objetos de valor, interfaces de repositório e serviços de domínio. Esta camada é independente de qualquer tecnologia específica de infraestrutura, garantindo que a lógica de negócios possa ser testada e evoluída de forma isolada.

- **Camada de Infraestrutura (`Infrastructure`)**: Fornece implementações concretas para as interfaces definidas na camada de domínio, como repositórios e serviços de domínio. Esta camada lida com detalhes técnicos como comunicação com o banco de dados, integração com APIs externas e configuração de frameworks.

- **Camada de Apresentação (`Presentation`)**: Responsável pela interação com o usuário, esta camada contém controladores, views e outros componentes de interface do usuário. Ela traduz as requisições do usuário em ações nos serviços de aplicação e transforma os resultados em respostas compreensíveis pelo usuário.

## Padrões de Design Implementados

- **Service Layer**: O projeto utiliza uma camada de serviço, dividida entre Serviços de Aplicação e Serviços de Domínio, para separar a lógica de negócios da lógica de apresentação e infraestrutura. Isso ajuda a manter o código organizado, facilita a reutilização e a manutenção.

- **Facade**: Implementamos o padrão Facade para fornecer uma interface simplificada para complexas interações entre objetos na camada de aplicação. Isso ajuda a reduzir a complexidade do código e melhora a legibilidade.

- **Repository**: Para abstrair a lógica de acesso a dados, utilizamos o padrão Repository, permitindo que a camada de domínio permaneça agnóstica em relação à fonte de dados.

- **DTO (Data Transfer Object)**: Utilizamos DTOs para transferir dados entre as camadas de aplicação e apresentação, encapsulando os dados em objetos simples, sem lógica de negócios, para otimizar a comunicação.

Esta estrutura e estes padrões de design ajudam a garantir que o projeto seja escalável, testável e fácil de manter, alinhando-se com as melhores práticas de desenvolvimento de software.

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
