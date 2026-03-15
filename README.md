# 📦 Sistema de Gerenciamento de Produtos

Sistema completo de gestão de produtos desenvolvido com **Laravel 12** (backend) e **Vue.js 3** (frontend). Ideal para empresas que precisam de controle organizado de estoque com interface moderna e responsiva.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)

---

## ✨ Funcionalidades

### 📊 Gestão de Produtos

- ✅ **Cadastro completo** com nome, descrição, marca, preço e estoque
- ✅ **Edição** de produtos existentes
- ✅ **Exclusão única** ou **em massa** (checkbox)
- ✅ **Filtros avançados** por nome, marca e estoque (com operadores `>10`, `<=5`)
- ✅ **Validações** frontend e backend
- ✅ **Listagem paginada** com ordenação

### 🎨 Interface Moderna

- ✅ Design **profissional** com cores azul e branco
- ✅ **Modais** elegantes para criar/editar
- ✅ **Notificações** com SweetAlert2
- ✅ **Loading states** durante requisições
- ✅ **Badges coloridos** de estoque (vermelho, amarelo, verde)

### 🔧 Tecnologias Utilizadas

#### Backend (API Laravel)

- Laravel 12
- MySQL 8.0+
- Form Requests (validação)
- Response Macros

#### Frontend (Vue.js)

- Vue.js 3 (Composition API)
- Axios (requisições HTTP)
- SweetAlert2 (alertas)
- CSS puro (sem frameworks)

---

## 📋 Requisitos

- **PHP** 8.3+
- **Composer** 2.x
- **Node.js** 18+ e npm
- **MySQL** 8.0+ ou MariaDB 10.5+
- **Navegador** moderno (Chrome, Firefox, Edge)

---

## 🚀 Como Rodar o Projeto

### 1️⃣ Clone o Repositório

```bash
git clone https://github.com/omarcus212/constr-up
cd constr-up
```

---

## 🔙 Backend (Laravel)

### 1. Acesse a pasta da API

```bash
cd api
```

### 2. Instale as dependências

```bash
composer install
```

### 3. Configure o ambiente

```bash
cp .env.example .env
```

Edite o arquivo `.env` com suas credenciais:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=products_db
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 5. Execute as migrations

```bash
php artisan migrate
```

### 6. (Opcional) Popule o banco com dados de exemplo

```bash
php artisan db:seed
```

### 7. Otimize a aplicação

```bash
php artisan optimize
```

### 8. Inicie o servidor

```bash
php artisan serve
```

**API rodando em:** `http://localhost:8000`

---

## 🎨 Frontend (Vue.js)

### 1. Acesse a pasta do frontend

```bash
cd front
```

### 2. Instale as dependências

```bash
npm install
```

### 3. Configure a URL da API (caso necessário) 

Edite `src/services/api.js` e confirme a URL:

```javascript
baseURL: "http://localhost:8000/api";
```

### 4. Inicie o servidor de desenvolvimento

```bash
npm run dev
```

**Frontend rodando em:** `http://localhost:5173`

---

## 🧪 Testando a Aplicação

### 1. Certifique-se de que ambos estão rodando:

- Backend: `http://localhost:8000`
- Frontend: `http://localhost:5173`

### 2. Acesse o frontend e teste:

- ✅ Criar novo produto
- ✅ Editar produto existente
- ✅ Filtrar por nome, marca, estoque
- ✅ Deletar produto único
- ✅ Selecionar múltiplos e deletar em massa

---

## 🛠️ Comandos Úteis

### Laravel

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Recriar banco (APAGA TUDO!)
php artisan migrate:fresh --seed

# Otimizar aplicação
php artisan optimize

# Ver rotas
php artisan route:list
```

### Vue.js

```bash
# Build para produção
npm run build

# Preview da build
npm run preview

# Limpar node_modules
rm -rf node_modules package-lock.json
npm install
```

---

## 🔐 Segurança

- ✅ **Validação** de dados no backend (FormRequests)
- ✅ **CORS** configurado corretamente
- ✅ **Sanitização** de inputs
- ✅ **Headers** de segurança
- ✅ **Proteção** contra SQL Injection (Eloquent ORM)

---

## 🐛 Problemas Comuns

### ❌ Erro CORS

**Solução:**

- Verifique `config/cors.php`
- Confirme que `http://localhost:5173` está em `allowed_origins`
- Reinicie o servidor Laravel

### ❌ API não conecta

**Solução:**

- Verifique se Laravel está rodando: `php artisan serve`
- Confirme URL em `src/services/api.js`: `http://localhost:8000/api`
- Teste diretamente no navegador: `http://localhost:8000/api/products`

### ❌ Erro 500 ao criar produto

**Solução:**

- Verifique os logs: `storage/logs/laravel.log`
- Confirme que `authorize()` retorna `true` nos FormRequests
- Verifique se o banco de dados está configurado corretamente

---

## 📚 Documentação Adicional

- [Laravel 12 Docs](https://laravel.com/docs)
- [Vue.js 3 Docs](https://vuejs.org/)
- [Axios Docs](https://axios-http.com/)
- [SweetAlert2 Docs](https://sweetalert2.github.io/)

---

## 👨‍💻 Desenvolvedor

Desenvolvido por **Marcus Vinnicius**

- GitHub: https://github.com/omarcus212
- LinkedIn: https://www.linkedin.com/in/marcus-vinnicius-524aa1206
- Email: marcusvinniciusouza@gmail.com

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## Documentação api:

https://documenter.getpostman.com/view/21065723/2sBXigMZP5
