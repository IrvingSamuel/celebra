<h1 align="center">
  <br>
  <span style="color:#FF477E">Celebra ✦</span>
  <br>
</h1>

<h4 align="center">Plataforma completa de planejamento de eventos — casamentos, formaturas, aniversários e muito mais.</h4>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/Livewire-4-4E56A6?style=flat-square&logo=livewire&logoColor=white" alt="Livewire 4">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-38BDF8?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind 4">
  <img src="https://img.shields.io/badge/Gemini-2.0_Flash-4285F4?style=flat-square&logo=google&logoColor=white" alt="Gemini AI">
  <img src="https://img.shields.io/badge/status-em_desenvolvimento-yellow?style=flat-square" alt="Status">
</p>

<p align="center">
  <a href="#-sobre">Sobre</a> •
  <a href="#-funcionalidades">Funcionalidades</a> •
  <a href="#-tecnologias">Tecnologias</a> •
  <a href="#-arquitetura">Arquitetura</a> •
  <a href="#-como-rodar">Como Rodar</a> •
  <a href="#-variáveis-de-ambiente">Variáveis de Ambiente</a> •
  <a href="#-banco-de-dados">Banco de Dados</a> •
  <a href="#-estrutura-do-projeto">Estrutura</a>
</p>

---

## Sobre

**Celebra** é uma plataforma SaaS de planejamento de eventos focada em casamentos. Conecta noivos, fornecedores e espaços em um único lugar, com ferramentas poderosas para tornar cada celebração inesquecível.

A plataforma oferece desde descoberta de espaços e serviços até uma **assistente de IA integrada (Celi)**, editor visual de página do evento, lista de presentes com scraping automático e confirmação de presença — tudo em tempo real com uma experiência reativa e moderna.

> **Demo ao vivo:** [eventos.eflow.space](https://eventos.eflow.space)

---

## Funcionalidades

### Para Noivos / Clientes

| Funcionalidade | Descrição |
|---|---|
| **Home** | Landing page com apresentação dos espaços e serviços disponíveis |
| **Espaços** | Listagem e detalhes de espaços para eventos com galeria, capacidade, localização e preço |
| **Serviços** | Catálogo de fornecedores por categoria (foto, buffet, flores, DJ, etc.) |
| **Meus Eventos** | Criação e gerenciamento de eventos com tipo, data, orçamento e número de convidados |
| **Celi — IA Planner** | Assistente inteligente (Google Gemini 2.0 Flash) para planejar eventos via chat com sugestões contextuais e ações rápidas |
| **Lista de Presentes** | Gerenciamento de lista com scraping automático de URL (Amazon, Americanas, etc.) para preencher item, imagem e preço |
| **Page Builder** | Editor visual de página pública do evento com temas, cores e blocos customizáveis |
| **Página do Evento** | Landing page pública e personalizável do evento para compartilhar com convidados |
| **RSVP** | Confirmação de presença dos convidados diretamente na página do evento |

### Para Fornecedores

| Funcionalidade | Descrição |
|---|---|
| **Perfil do Fornecedor** | Edição de perfil com foto, bio, localização e dados de contato |
| **Meus Serviços** | Cadastro e gestão de serviços com múltiplas imagens, descrição e preço |
| **Solicitações** | Visualização e gestão de solicitações de contato recebidas de clientes |

### Administração

| Funcionalidade | Descrição |
|---|---|
| **Fornecedores** | Listagem, aprovação e gerenciamento de fornecedores cadastrados |
| **Espaços** | Cadastro e gestão de espaços para eventos |
| **Gigs dos Fornecedores** | Gerenciamento dos serviços vinculados a cada fornecedor |

---

## Tecnologias

### Back-end
- **[PHP 8.3](https://www.php.net/)** — linguagem principal
- **[Laravel 13](https://laravel.com/)** — framework full-stack
- **[Livewire 4](https://livewire.laravel.com/)** — componentes reativos server-side sem JavaScript extra
- **[MySQL](https://www.mysql.com/)** — banco de dados relacional

### Front-end
- **[Tailwind CSS 4](https://tailwindcss.com/)** — estilização utilitária
- **[Vite 8](https://vitejs.dev/)** — bundler e hot-reload
- **[SweetAlert2](https://sweetalert2.github.io/)** — modais e alertas elegantes
- **[Alpine.js](https://alpinejs.dev/)** — interatividade leve no front (embutido via Livewire)

### Infraestrutura & Integrações
- **[Google Gemini 2.0 Flash](https://ai.google.dev/)** — IA para o planejador Celi
- **[AWS S3 / MinIO](https://min.io/)** — armazenamento de imagens e arquivos
- **[Laravel Queue](https://laravel.com/docs/queues)** — processamento assíncrono
- **[Laravel Mail](https://laravel.com/docs/mail)** — notificações por e-mail (verificação, presentes)

---

## Arquitetura

A Celebra segue a arquitetura **TALL Stack** (Tailwind + Alpine + Livewire + Laravel), com componentes Livewire cobrindo toda a interatividade. Cada página é um componente Livewire isolado, comunicando-se via eventos e propriedades reativas.

```
┌─────────────────────────────────────────────────┐
│                    Browser                      │
│         (Livewire Wire Requests / Vite)         │
└─────────────────┬───────────────────────────────┘
                  │
┌─────────────────▼───────────────────────────────┐
│                  Laravel 13                     │
│  ┌──────────┐  ┌──────────┐  ┌──────────────┐   │
│  │  Routes  │  │Middleware│  │  Providers   │   │
│  └────┬─────┘  └──────────┘  └──────────────┘   │
│       │                                         │
│  ┌────▼─────────────────────────────────────┐   │
│  │         Livewire Components              │   │
│  │  HomePage │ VenueListing │ EventPlanner  │   │
│  │  GiftRegistryManager │ PageBuilder │ ... │   │
│  └────┬────────────────────┬────────────────┘   │
│       │                    │                    │
│  ┌────▼──────────┐  ┌──────▼───────────────┐    │
│  │    Models     │  │      Services        │    │
│  │ Event, Venue  │  │  GeminiService (AI)  │    │
│  │ GiftRegistry  │  │  HTTP Scraper        │    │
│  │ SupplierProf. │  └──────────────────────┘    │
│  └────┬──────────┘                              │
└───────┼─────────────────────────────────────────┘
        │
┌───────▼─────────────────────────────────────────┐
│              MySQL  +  MinIO (S3)               │
└─────────────────────────────────────────────────┘
```

### Fluxo da IA (Celi)

```
Usuário → Livewire Component → GeminiService
            ↓                       ↓
     Contexto do evento    Google Gemini 2.0 Flash API
     (tipo, orçamento,           ↓
      convidados, data)    Resposta + Sugestões
            ↓                       ↓
     Conversation Model   Exibido em tempo real via Livewire
     (histórico salvo)
```

---

## Como Rodar

### Pré-requisitos

- PHP `^8.3`
- Composer `^2`
- Node.js `^20`
- MySQL `^8`
- MinIO (ou AWS S3) configurado
- Conta na [Google AI Studio](https://aistudio.google.com/) para a chave Gemini

### Instalação rápida

```bash
# 1. Clone o repositório
git clone https://github.com/IrvingSamuel/celebra.git
cd celebra

# 2. Configure o .env
cp .env.example .env
# Edite o .env com suas credenciais

# 3. Instale as dependências PHP e JS, gere a key, rode migrations e build
composer run setup
```

O comando `composer run setup` executa na sequência:

1. `composer install`
2. Copia `.env.example` → `.env` (se não existir)
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

### Subir o ambiente de desenvolvimento

```bash
composer run dev
```

Isso inicia em paralelo:

| Processo | Descrição |
|---|---|
| `php artisan serve` | Servidor HTTP local |
| `php artisan queue:listen` | Worker da fila |
| `php artisan pail` | Log em tempo real |
| `npm run dev` | Vite HMR |

Acesse em: [http://localhost:8000](http://localhost:8000)

---

## Variáveis de Ambiente

Copie `.env.example` e preencha as seguintes variáveis:

```env
APP_NAME=Celebra
APP_URL=https://seu-dominio.com

# Banco de Dados
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=celebra
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# Google Gemini (IA)
GEMINI_API_KEY=sua_chave_gemini
GEMINI_MODEL=gemini-2.0-flash

# Storage (MinIO / AWS S3)
AWS_ACCESS_KEY_ID=seu_access_key
AWS_SECRET_ACCESS_KEY=sua_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=celebra
AWS_ENDPOINT=http://localhost:9000   # MinIO local
AWS_USE_PATH_STYLE_ENDPOINT=true     # obrigatório para MinIO

# E-mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.seu-provedor.com
MAIL_PORT=587
MAIL_USERNAME=seu@email.com
MAIL_PASSWORD=sua_senha
MAIL_FROM_ADDRESS=noreply@seu-dominio.com
MAIL_FROM_NAME="Celebra"

# Sessão / Cache / Queue
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

---

## Banco de Dados

### Diagrama de entidades principais

```
users
  ├── events (1:N)
  │     ├── event_venues (N:M) ──► venues
  │     ├── event_services (N:M) ──► services
  │     ├── gift_registries (1:1)
  │     │     └── gift_items (1:N)
  │     │           └── gift_pledges (1:N)
  │     ├── event_pages (1:1)
  │     └── event_rsvps (1:N)
  ├── conversations (1:N)
  └── supplier_profiles (1:1)
        └── services (1:N)
              └── service_categories (N:1)

venues
  └── event_types (N:M)
```

### Migrations

| Migration | Descrição |
|---|---|
| `create_users_table` | Usuários com roles (cliente/fornecedor/admin) e slug |
| `create_venues_table` | Espaços para eventos com galeria, capacidade e tipo |
| `create_service_categories_table` | Categorias de serviços (foto, buffet, etc.) |
| `create_services_table` | Serviços dos fornecedores com múltiplas imagens |
| `create_events_table` | Eventos dos usuários com orçamento e configurações |
| `create_supplier_profiles_table` | Perfis de fornecedores |
| `create_gift_registries_table` | Listas de presentes vinculadas ao evento |
| `create_gift_items_table` | Itens da lista com controle de quantidade |
| `create_gift_pledges_table` | Promessas de presente com cancelamento via token |
| `create_event_pages_table` | Página pública do evento (Page Builder) |
| `create_event_rsvps_table` | RSVPs dos convidados |
| `create_conversations_table` | Histórico de conversas com a Celi |
| `create_event_venues_table` | Relação N:M eventos ↔ espaços |

---

## Estrutura do Projeto

```
celebra/
├── app/
│   ├── Http/
│   │   ├── Controllers/            # Controllers mínimos (auth helpers)
│   │   └── Middleware/             # Admin middleware
│   ├── Livewire/                   # Todos os componentes Livewire
│   │   ├── Admin/                  # Área administrativa
│   │   │   ├── Suppliers.php
│   │   │   ├── SupplierGigs.php
│   │   │   └── Venues.php
│   │   ├── Auth/                   # Login, Registro, Verificação
│   │   ├── Supplier/               # Área do fornecedor
│   │   │   ├── ProfileEdit.php
│   │   │   ├── SupplierServices.php
│   │   │   └── Requests.php
│   │   ├── CeliChat.php            # Chat standalone com a IA
│   │   ├── EventPlanner.php        # Planejador com IA + ações contextuais
│   │   ├── GiftRegistryManager.php # Gestão da lista de presentes
│   │   ├── GiftRegistryPage.php    # Página pública da lista
│   │   ├── PageBuilder.php         # Editor visual da página do evento
│   │   ├── EventLandingPage.php    # Landing page pública do evento
│   │   ├── Dashboard.php           # Painel do usuário
│   │   ├── EventEdit.php           # Criar/editar eventos
│   │   ├── VenueListing.php        # Listagem de espaços
│   │   ├── VenueDetail.php         # Detalhe do espaço
│   │   ├── ServiceListing.php      # Listagem de serviços
│   │   └── ServiceDetail.php       # Detalhe do serviço
│   ├── Models/                     # Eloquent Models
│   │   ├── Event.php
│   │   ├── Venue.php
│   │   ├── Service.php
│   │   ├── GiftRegistry.php
│   │   ├── GiftItem.php
│   │   ├── GiftPledge.php
│   │   ├── EventPage.php
│   │   ├── EventRsvp.php
│   │   ├── SupplierProfile.php
│   │   ├── Conversation.php
│   │   └── User.php
│   ├── Notifications/              # Email notifications
│   │   ├── GiftPledgeNotification.php
│   │   └── VerifyEmailNotification.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Services/
│       └── GeminiService.php       # Integração Google Gemini AI
├── database/
│   ├── migrations/                 # 25+ migrations
│   └── seeders/                    # Seeders de dados
├── resources/
│   ├── css/                        # Tailwind CSS entry
│   ├── js/                         # Vite JS entry
│   └── views/
│       ├── livewire/               # Blade views dos componentes
│       ├── components/             # Layouts e componentes parciais
│       └── prototype/              # Protótipo IHC (moodboard, style guide)
├── routes/
│   └── web.php                     # Todas as rotas da aplicação
├── public/
│   └── build/                      # Assets compilados pelo Vite
├── composer.json                   # Scripts: setup, dev, test
└── vite.config.js
```

---

## Roles de Usuário

| Role | Acesso |
|---|---|
| `user` | Dashboard, eventos, lista de presentes, page builder, planejador IA |
| `supplier` | Área do fornecedor (perfil, serviços, solicitações) |
| `admin` | Painel admin (fornecedores, espaços, gigs) |

---

## A Celi — Assistente de IA

A **Celi** é a assistente virtual da Celebra, alimentada pelo **Google Gemini 2.0 Flash**. Ela possui contexto completo da plataforma: conhece os espaços disponíveis, os serviços cadastrados e os tipos de eventos suportados.

**Capacidades da Celi:**
- Planejamento conversacional de eventos
- Sugestões de espaços e fornecedores baseadas em orçamento e preferências
- Botões de ação contextual após cada resposta (ex: "Ver espaços disponíveis", "Criar evento")
- Histórico de conversas persistido por usuário
- Múltiplas conversas com títulos gerados automaticamente

---

## Page Builder

O editor visual de página do evento permite personalizar:

| Bloco | Descrição |
|---|---|
| **Hero** | Imagem de capa com título e data do evento |
| **Mensagem** | Texto personalizado com imagem opcional |
| **Galeria** | Grade de fotos do casal |
| **Programação** | Linha do tempo da cerimônia/festa |
| **Localização** | Endereço com mapa |

- **Temas:** Romântico, Elegante, Moderno, Rústico, Floral
- **Cor primária** customizável
- **Upload de imagens** direto para o MinIO/S3
- **Publicação** com URL pública: `/{user-slug}/{event-slug}`

---

## Lista de Presentes

Sistema completo de lista de presentes com:

- **Scraping automático:** cole o link do produto e a plataforma busca nome, imagem e preço
- **Plataformas suportadas:** Amazon, Americanas, Magazine Luiza e outras
- **Controle de quantidade** desejada vs. recebida
- **Promessas de presente** pelos convidados com e-mail de confirmação
- **Cancelamento** via link tokenizado enviado por e-mail (sem necessidade de login)

---

## Testes

```bash
# Rodar todos os testes
composer test

# Ou diretamente via artisan
php artisan test
```

---

## Deploy

```bash
# 1. Compilar assets para produção
npm run build

# 2. Instalar dependências sem dev
composer install --optimize-autoloader --no-dev

# 3. Cache de configurações
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Rodar migrations
php artisan migrate --force
```

---

## Licença

Este projeto é privado e de uso exclusivo da **eFlow**. Todos os direitos reservados.

---
