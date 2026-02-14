# 🏥 Sistema de Gestão de Clínica

Sistema completo de gestão para clínicas e consultórios, desenvolvido com Laravel 12. Oferece gerenciamento de pacientes, agendamentos, prontuários, faturamento e despesas, com suporte multi-profissional e controle de acesso baseado em funções.

## 📋 Índice

- [Funcionalidades](#-funcionalidades)
- [Tecnologias](#-tecnologias)
- [Requisitos](#-requisitos)
- [Instalação](#-instalação)
- [Desenvolvimento](#-desenvolvimento)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Modelos e Relacionamentos](#-modelos-e-relacionamentos)
- [Comandos Úteis](#-comandos-úteis)
- [Testes](#-testes)
- [Deploy](#-deploy)
- [Licença](#-licença)

## ✨ Funcionalidades

### Gestão de Pacientes
- 📝 Cadastro completo de pacientes com prontuário único
- 🔍 Busca e filtros avançados
- 📊 Histórico de consultas e tratamentos
- 🗂️ Organização por profissional responsável

### Agendamentos
- 📅 Sistema de agendamento de consultas
- ⏰ Controle de horários e disponibilidade
- 📋 Status de sessões (agendada, realizada, cancelada)
- 💬 Anotações e observações por sessão

### Gestão Financeira
- 💰 Emissão e controle de faturas
- 💳 Registro de pagamentos com múltiplos métodos
- 📊 Controle de despesas operacionais
- 📈 Relatórios financeiros

### Controle de Acesso
- 👤 Sistema de autenticação seguro
- 🔐 Controle de permissões (Admin/Profissional)
- 🏢 Multi-tenant: profissionais gerenciam seus próprios pacientes
- 👨‍⚕️ Administradores têm acesso total ao sistema

### Outras Funcionalidades
- 🗑️ Soft delete em todos os registros (exceto usuários)
- 🔄 Sistema de filas para tarefas assíncronas
- 📝 Logs detalhados de atividades
- 🌐 Interface responsiva com Tailwind CSS 4

## 🛠 Tecnologias

- **Backend**: [Laravel 12](https://laravel.com) (PHP 8.2+)
- **Frontend**: Blade Templates + [Tailwind CSS 4](https://tailwindcss.com) + [Vite](https://vite.dev)
- **Banco de Dados**: SQLite (padrão) / MySQL / PostgreSQL
- **Autenticação**: Laravel UI
- **Permissões**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- **Cache & Queue**: Database driver (recomendado Redis para produção)
- **Testes**: PHPUnit com SQLite in-memory
- **Ferramentas**: Laravel Pail (logs), Laravel Tinker (REPL)

## 📦 Requisitos

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x
- NPM >= 9.x
- Extensões PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo

## 🚀 Instalação

### Instalação Rápida

```bash
# Clone o repositório
git clone <repository-url> clinica
cd clinica

# Execute o setup completo (instala dependências, configura .env, gera chave, migra DB)
composer setup
```

O comando `composer setup` executa automaticamente:
- ✅ Instalação de dependências PHP (`composer install`)
- ✅ Criação do arquivo `.env` a partir do `.env.example`
- ✅ Geração da chave da aplicação (`php artisan key:generate`)
- ✅ Execução das migrations (`php artisan migrate`)
- ✅ Instalação de dependências frontend (`npm install`)
- ✅ Build dos assets (`npm run build`)

### Instalação Manual (Passo a Passo)

```bash
# 1. Instalar dependências PHP
composer install

# 2. Copiar arquivo de configuração
cp .env.example .env

# 3. Gerar chave da aplicação
php artisan key:generate

# 4. Criar banco de dados SQLite (se usar SQLite)
touch database/database.sqlite

# 5. Configurar .env (opcional - ajustar DB_CONNECTION, etc)
nano .env

# 6. Executar migrations
php artisan migrate

# 7. (Opcional) Popular banco com dados de teste
php artisan db:seed

# 8. Instalar dependências frontend
npm install

# 9. Build dos assets
npm run build
```

## 💻 Desenvolvimento

### Iniciar Servidor de Desenvolvimento

```bash
# Inicia todos os serviços simultaneamente (servidor, fila, logs, vite)
composer dev
```

O comando `composer dev` inicia 4 serviços em paralelo:
- 🌐 **Server**: `php artisan serve` - localhost:8000
- ⚙️ **Queue**: `php artisan queue:listen --tries=1`
- 📋 **Logs**: `php artisan pail --timeout=0` - visualização em tempo real
- ⚡ **Vite**: `npm run dev` - hot reload de assets

### Serviços Individuais

```bash
# Servidor web apenas
php artisan serve

# Worker de filas
php artisan queue:listen --tries=1

# Visualizador de logs em tempo real
php artisan pail --timeout=0

# Servidor Vite para assets
npm run dev
```

### Banco de Dados

```bash
# Executar migrations
php artisan migrate

# Resetar banco e executar migrations + seeds
php artisan migrate:fresh --seed

# Apenas seeds
php artisan db:seed

# Rollback última migration
php artisan migrate:rollback

# Status das migrations
php artisan migrate:status
```

### Comandos Artisan Úteis

```bash
# REPL interativo (testar código)
php artisan tinker

# Listar todas as rotas
php artisan route:list

# Limpar caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Criar recursos
php artisan make:controller PatientController --resource
php artisan make:model Appointment -m
php artisan make:migration create_appointments_table
php artisan make:seeder PatientSeeder
php artisan make:factory PatientFactory
```

## 📁 Estrutura do Projeto

```
clinica/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/          # Controladores admin (Dashboard, Users)
│   │       ├── Auth/           # Autenticação (Laravel UI)
│   │       └── Controller.php
│   ├── Models/                 # Modelos Eloquent
│   │   ├── User.php
│   │   ├── Patient.php
│   │   ├── Appointment.php
│   │   ├── Invoice.php
│   │   ├── Payment.php
│   │   └── Expense.php
│   └── Providers/
│
├── database/
│   ├── migrations/             # Migrações do banco
│   ├── seeders/               # Seeds de dados
│   └── factories/             # Factories para testes
│
├── resources/
│   ├── views/
│   │   ├── auth/              # Telas de login/registro
│   │   ├── admin/             # Painel administrativo
│   │   ├── patients/          # Gestão de pacientes
│   │   ├── layouts/           # Layouts Blade
│   │   └── profile/           # Perfil de usuário
│   ├── css/                   # Estilos (Tailwind)
│   └── js/                    # JavaScript (Vue.js componentes)
│
├── routes/
│   ├── web.php                # Rotas web (auth + protegidas)
│   └── console.php            # Comandos Artisan
│
├── tests/
│   ├── Feature/               # Testes de funcionalidade
│   └── Unit/                  # Testes unitários
│
├── public/                    # Assets públicos
├── storage/                   # Arquivos gerados
└── vendor/                    # Dependências
```

## 🔗 Modelos e Relacionamentos

### User (Usuário/Profissional)
```php
// Campos personalizados
- crp: string              // Registro profissional
- specialties: json        // Especialidades
- address: string
- phone: string

// Relacionamentos
hasMany(Patient)           // Pacientes do profissional
hasMany(Appointment)       // Agendamentos do profissional

// Métodos
isAdmin(): bool
isProfessional(): bool
```

### Patient (Paciente)
```php
// Campos principais
- code: string (unique)    // Código do prontuário
- name: string
- email: string
- phone: string
- address: string
- user_id: foreign         // Profissional responsável

// Relacionamentos
belongsTo(User)            // Profissional responsável
hasMany(Appointment)       // Agendamentos
hasMany(Invoice)           // Faturas

// Soft Deletes: ✅
```

### Appointment (Agendamento)
```php
// Campos principais
- patient_id: foreign
- user_id: foreign         // Profissional
- appointment_date: datetime
- start_time: time
- end_time: time
- status: enum             // scheduled, completed, cancelled...
- type: string
- notes: text

// Relacionamentos
belongsTo(Patient)
belongsTo(User)            // Profissional

// Soft Deletes: ✅
```

### Invoice (Fatura)
```php
// Campos principais
- patient_id: foreign
- amount: decimal
- status: enum             // pending, paid, cancelled
- due_date: date
- issue_date: date

// Relacionamentos
belongsTo(Patient)
hasMany(Payment)

// Soft Deletes: ✅
```

### Payment (Pagamento)
```php
// Campos principais
- invoice_id: foreign
- amount: decimal
- payment_date: date
- payment_method: string

// Relacionamentos
belongsTo(Invoice)

// Soft Deletes: ✅
```

### Expense (Despesa)
```php
// Campos principais
- user_id: foreign         // Profissional
- description: string
- amount: decimal
- category: string
- expense_date: date

// Relacionamentos
belongsTo(User)

// Soft Deletes: ✅
```

## 🧪 Testes

```bash
# Executar todos os testes
composer test

# Ou diretamente com artisan
php artisan test

# Executar teste específico
php artisan test --filter=PatientTest

# Executar testes com cobertura
php artisan test --coverage

# Formatação de código (Laravel Pint)
vendor/bin/pint

# Verificar formatação sem aplicar
vendor/bin/pint --test
```

### Configuração de Testes

Os testes utilizam:
- ✅ SQLite in-memory para velocidade
- ✅ Array driver para cache/session
- ✅ Sync driver para queues
- ✅ RefreshDatabase trait para limpar banco entre testes

## 🔒 Autenticação e Permissões

### Roles (Funções)
- **Admin**: Acesso total ao sistema
- **Professional**: Acesso apenas aos próprios dados

### Middleware
```php
// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    // ...
});

// Verificação de role nos controllers
if (auth()->user()->isAdmin()) {
    // Admin tem acesso a tudo
}

// Escopo de dados por profissional
$patients = auth()->user()->isProfessional()
    ? Patient::where('user_id', auth()->id())->get()
    : Patient::all();
```

## 📊 Comandos Úteis

### Criação de Recursos

```bash
# Controller completo
php artisan make:controller AppointmentController --resource

# Model com migration e factory
php artisan make:model Invoice -mf

# Migration
php artisan make:migration add_status_to_appointments

# Seeder
php artisan make:seeder AppointmentSeeder

# Factory
php artisan make:factory InvoiceFactory

# Request de validação
php artisan make:request StorePatientRequest
```

### Manutenção

```bash
# Otimizar aplicação
php artisan optimize

# Limpar otimizações
php artisan optimize:clear

# Ver logs em tempo real
php artisan pail

# Acessar console interativo
php artisan tinker

# Listar comandos disponíveis
php artisan list
```

## 🚀 Deploy

### Preparação para Produção

```bash
# 1. Otimizar autoload
composer install --optimize-autoloader --no-dev

# 2. Otimizar configuração
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Build assets de produção
npm run build

# 4. Configurar permissões
chmod -R 775 storage bootstrap/cache
```

### Variáveis de Ambiente (.env)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com

# Banco de dados (MySQL/PostgreSQL recomendado)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinica
DB_USERNAME=root
DB_PASSWORD=senha_segura

# Cache e Queue (Redis recomendado)
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Checklist de Deploy

- [ ] Configurar `.env` de produção
- [ ] Gerar nova `APP_KEY` (`php artisan key:generate`)
- [ ] Configurar banco de dados
- [ ] Executar migrations (`php artisan migrate --force`)
- [ ] Otimizar caches (`config:cache`, `route:cache`, `view:cache`)
- [ ] Configurar cron para `schedule:run`
- [ ] Configurar supervisor para `queue:work`
- [ ] Configurar SSL/HTTPS
- [ ] Configurar backups do banco
- [ ] Testar aplicação

## 📝 Licença

Este projeto é um software proprietário. Todos os direitos reservados.

---

**Desenvolvido com Laravel 12** ❤️

Para mais informações, consulte a [documentação do Laravel](https://laravel.com/docs).
