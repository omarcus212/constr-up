<template>
  <div class="products-page">
    <!-- Header -->
    <div class="page-header">
      <h1>Gerenciamento de Produtos</h1>
      <div class="header-actions">
        <button @click="toggleFilters" class="btn-filter">
          <svg
            width="20"
            height="20"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
          >
            <path
              d="M3 4h18M3 12h12M3 20h6"
              stroke-width="2"
              stroke-linecap="round"
            />
          </svg>
          Filtros
        </button>
        <button @click="openCreateModal" class="btn-primary">
          <svg
            width="20"
            height="20"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
          >
            <path
              d="M12 5v14M5 12h14"
              stroke-width="2"
              stroke-linecap="round"
            />
          </svg>
          Novo Produto
        </button>
      </div>
    </div>

    <!-- Filtros -->
    <div v-if="showFilters" class="filters-panel">
      <div class="filters-grid">
        <div class="form-group">
          <label>Nome do Produto</label>
          <input
            v-model="filters.name"
            type="text"
            placeholder="Buscar por nome..."
            class="form-control"
            @keyup.enter="applyFilters"
          />
        </div>

        <div class="form-group">
          <label>Marca</label>
          <input
            v-model="filters.brand"
            type="text"
            placeholder="Buscar por marca..."
            class="form-control"
            @keyup.enter="applyFilters"
          />
        </div>

        <div class="form-group">
          <label>Estoque</label>
          <input
            v-model="filters.stock"
            type="text"
            placeholder="Ex: >10, <=5"
            class="form-control"
            @keyup.enter="applyFilters"
          />
        </div>
      </div>

      <div class="filters-actions">
        <button @click="clearFilters" class="btn-secondary">Limpar</button>
        <button @click="applyFilters" class="btn-primary">
          Aplicar Filtros
        </button>
      </div>
    </div>

    <!-- Delete em Massa -->
    <div v-if="selectedProducts.length > 0" class="bulk-actions">
      <span>{{ selectedProducts.length }} produto(s) selecionado(s)</span>
      <button @click="deleteSelected" class="btn-danger">
        <svg
          width="18"
          height="18"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
        >
          <path
            d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14z"
            stroke-width="2"
          />
        </svg>
        Excluir Selecionados
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="loading-overlay">
      <div class="spinner"></div>
    </div>

    <!-- Tabela -->
    <div class="table-container">
      <table class="products-table">
        <thead>
          <tr>
            <th width="50">
              <input
                type="checkbox"
                @change="toggleSelectAll"
                :checked="isAllSelected"
              />
            </th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Marca</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th width="120">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products" :key="product.id">
            <td>
              <input
                type="checkbox"
                :value="product.id"
                v-model="selectedProducts"
              />
            </td>
            <td class="product-name">{{ product.name }}</td>
            <td class="product-description">
              {{ product.description || "-" }}
            </td>
            <td>{{ product.brand }}</td>
            <td class="product-price">R$ {{ formatPrice(product.price) }}</td>
            <td>
              <span class="stock-badge" :class="getStockClass(product.stock)">
                {{ product.stock }} un.
              </span>
            </td>
            <td class="actions">
              <button
                @click="openEditModal(product)"
                class="btn-icon"
                title="Editar"
              >
                <svg
                  width="18"
                  height="18"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                >
                  <path
                    d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                    stroke-width="2"
                  />
                </svg>
              </button>
              <button
                @click="deleteProduct(product.id)"
                class="btn-icon btn-icon-danger"
                title="Excluir"
              >
                <svg
                  width="18"
                  height="18"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                >
                  <path
                    d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14z"
                    stroke-width="2"
                  />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- mock -->
      <div v-if="!loading && products.length === 0" class="empty-state">
        <svg
          width="80"
          height="80"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
        >
          <rect
            x="3"
            y="3"
            width="18"
            height="18"
            rx="2"
            ry="2"
            stroke-width="1.5"
          />
          <line x1="9" y1="9" x2="15" y2="15" stroke-width="1.5" />
          <line x1="15" y1="9" x2="9" y2="15" stroke-width="1.5" />
        </svg>
        <p>Nenhum produto encontrado</p>
      </div>
    </div>

    <!-- Modal Criar/Editar -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal">
        <div class="modal-header">
          <h2>{{ isEditing ? "Editar Produto" : "Novo Produto" }}</h2>
          <button @click="closeModal" class="btn-close">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
            >
              <path
                d="M18 6L6 18M6 6l12 12"
                stroke-width="2"
                stroke-linecap="round"
              />
            </svg>
          </button>
        </div>

        <div class="modal-body">
          <!-- Mensagem de erro -->
          <div v-if="errorMessage" class="error-alert">
            {{ errorMessage }}
          </div>

          <div class="form-group">
            <label>Nome do Produto *</label>
            <input
              v-model="formData.name"
              type="text"
              class="form-control"
              placeholder="Digite o nome do produto"
            />
          </div>

          <div class="form-group">
            <label>Descrição</label>
            <textarea
              v-model="formData.description"
              class="form-control"
              rows="3"
              placeholder="Descreva o produto..."
            ></textarea>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Marca *</label>
              <input
                v-model="formData.brand"
                type="text"
                class="form-control"
                placeholder="Ex: Samsung"
              />
            </div>

            <div class="form-group">
              <label>Preço (R$) *</label>
              <input
                v-model="formData.price"
                type="number"
                step="0.01"
                class="form-control"
                placeholder="0.00"
              />
            </div>

            <div class="form-group">
              <label>Estoque *</label>
              <input
                v-model="formData.stock"
                type="number"
                class="form-control"
                placeholder="0"
              />
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button @click="closeModal" class="btn-secondary" :disabled="saving">
            Cancelar
          </button>
          <button @click="saveProduct" class="btn-primary" :disabled="saving">
            {{ saving ? "Salvando..." : isEditing ? "Atualizar" : "Cadastrar" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/services/api";
import Swal from "sweetalert2";

const showFilters = ref(false);
const showModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);
const saving = ref(false);
const selectedProducts = ref([]);
const errorMessage = ref("");

// Dados
const products = ref([]);

// Filtros
const filters = ref({
  name: "",
  brand: "",
  stock: "",
});

// Form Data
const formData = ref({
  id: null,
  name: "",
  description: "",
  brand: "",
  price: "",
  stock: "",
});

// Computed
const isAllSelected = computed(() => {
  return (
    products.value.length > 0 &&
    selectedProducts.value.length === products.value.length
  );
});

// GET - Buscar produtos
const fetchProducts = async () => {
  loading.value = true;

  try {
    const response = await api.getProducts(filters.value);

    if (response.data.success) {
      products.value = response.data.data;
    } else {
      products.value = [];
    }
  } catch (error) {
    console.error("Erro ao buscar produtos:", error);

    Swal.fire({
      toast: true,
      position: "top-end",
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: false,
      icon: "error",
      title: "Erro!",
      text: "Erro ao carregar produtos. Verifique se a API está rodando.",
    });

    products.value = [];
  } finally {
    loading.value = false;
  }
};

// POST - Criar produto
const createProduct = async () => {
  saving.value = true;
  errorMessage.value = "";

  try {
    const response = await api.createProduct(formData.value);

    if (response.data.success) {
      Swal.fire({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        icon: "success",
        title: "Sucesso!",
        text: "Produto cadastrado com sucesso!",
      });

      closeModal();
      fetchProducts();
    }
  } catch (error) {
    console.error("Erro ao criar produto:", error);

    // Pega a mensagem de erro da API
    if (error.response?.data?.errors?.message) {
      errorMessage.value = error.response.data.errors.message;
    } else if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message;
    } else {
      errorMessage.value = "Erro ao cadastrar produto. Verifique os dados.";
    }

    Swal.fire({
      toast: true,
      position: "top-end",
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: false,
      icon: "error",
      title: "Erro!",
      text: errorMessage.value,
    });
  } finally {
    saving.value = false;
  }
};

// PUT - Atualizar produto
const updateProduct = async () => {
  saving.value = true;
  errorMessage.value = "";

  try {
    const response = await api.updateProduct(formData.value.id, formData.value);

    if (response.data.success) {
      Swal.fire({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        icon: "success",
        title: "Sucesso!",
        text: "Produto atualizado com sucesso!",
      });
      closeModal();
      fetchProducts();
    }
  } catch (error) {
    console.error("Erro ao atualizar produto:", error);

    if (error.response?.data?.errors?.message) {
      errorMessage.value = error.response.data.errors.message;
    } else if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message;
    } else {
      errorMessage.value = "Erro ao atualizar produto. Verifique os dados.";
    }

    Swal.fire({
      toast: true,
      position: "top-end",
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: false,
      icon: "error",
      title: "Erro!",
      text: errorMessage.value,
    });
  } finally {
    saving.value = false;
  }
};

// DELETE - Deletar produto único
const deleteProduct = async (id) => {
  const result = await Swal.fire({
    title: "Tem certeza?",
    text: "Deseja realmente excluir este produto?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc2626",
    cancelButtonColor: "#64748b",
    confirmButtonText: "Sim, excluir!",
    cancelButtonText: "Cancelar",
  });

  if (!result.isConfirmed) return;

  loading.value = true;

  try {
    const response = await api.deleteProducts([id]);

    if (response.data.success) {
      Swal.fire({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        icon: "success",
        title: "Sucesso!",
        text: "Produto deletado com sucesso!",
      });
      fetchProducts();
    }
  } catch (error) {
    console.error("Erro ao deletar produto:", error);

    const message =
      error.response?.data?.errors?.message || "Erro ao excluir produto";
    Swal.fire({
      toast: true,
      position: "top-end",
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: false,
      icon: "error",
      title: "Erro!",
      text: message,
    });
  } finally {
    loading.value = false;
  }
};

// DELETE - Deletar produtos em massa
const deleteSelected = async () => {
  const result = await Swal.fire({
    title: "Tem certeza?",
    text: "Deseja realmente excluir este produto?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc2626",
    cancelButtonColor: "#64748b",
    confirmButtonText: "Sim, excluir!",
    cancelButtonText: "Cancelar",
  });

  if (!result.isConfirmed) return;

  loading.value = true;

  try {
    const response = await api.deleteProducts(selectedProducts.value);

    if (response.data.success) {
      Swal.fire({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        icon: "success",
        title: "Sucesso!",
        text: `${selectedProducts.value.length} produto(s) excluído(s) com sucesso!`,
      });
      selectedProducts.value = [];
      fetchProducts();
    }
  } catch (error) {
    console.error("Erro ao deletar produtos:", error);

    const message =
      error.response?.data?.errors?.message || "Erro ao excluir produtos";

    Swal.fire({
      toast: true,
      position: "top-end",
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: false,
      icon: "error",
      title: "Erro!",
      text: message,
    });
  } finally {
    loading.value = false;
  }
};

// ========== MÉTODOS DE UI ==========

const toggleFilters = () => {
  showFilters.value = !showFilters.value;
};

const clearFilters = () => {
  filters.value = {
    name: "",
    brand: "",
    stock: "",
  };
  fetchProducts(); // Recarrega sem filtros
};

const applyFilters = () => {
  fetchProducts(); // Busca com filtros
};

const openCreateModal = () => {
  isEditing.value = false;
  errorMessage.value = "";
  formData.value = {
    id: null,
    name: "",
    description: "",
    brand: "",
    price: "",
    stock: "",
  };
  showModal.value = true;
};

const openEditModal = (product) => {
  isEditing.value = true;
  errorMessage.value = "";
  formData.value = { ...product };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  errorMessage.value = "";
};

const saveProduct = () => {
  if (isEditing.value) {
    updateProduct();
  } else {
    createProduct();
  }
};

const toggleSelectAll = (event) => {
  if (event.target.checked) {
    selectedProducts.value = products.value.map((p) => p.id);
  } else {
    selectedProducts.value = [];
  }
};

const formatPrice = (price) => {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(price);
};

const getStockClass = (stock) => {
  if (stock === 0) return "stock-empty";
  if (stock <= 10) return "stock-low";
  return "stock-ok";
};

// Carregar produtos ao montar componente
onMounted(() => {
  fetchProducts();
});
</script>

<style scoped>
.products-page {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem;
  background: #f5f7fa;
  min-height: 100vh;
}

/* Header */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  background: white;
  padding: 1.5rem 2rem;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.page-header h1 {
  font-size: 1.75rem;
  color: #1e3a8a;
  font-weight: 600;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

/* Botões */
.btn-primary {
  background: #2563eb;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.btn-secondary {
  background: white;
  color: #64748b;
  border: 2px solid #c3c4c5;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary:hover:not(:disabled) {
  background: #f8fafc;
  border-color: #cbd5e1;
}

.btn-secondary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-filter {
  background: white;
  color: #475569;
  border: 1px solid #e2e8f0;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
}

.btn-filter:hover {
  background: #f8fafc;
  border-color: #2563eb;
  color: #2563eb;
}

.btn-danger {
  background: #dc2626;
  color: white;
  border: none;
  padding: 0.625rem 1.25rem;
  border-radius: 8px;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
}

.btn-danger:hover {
  background: #b91c1c;
}

/* Filtros */
.filters-panel {
  background: white;
  padding: 1.5rem 2rem;
  border-radius: 12px;
  margin-bottom: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.filters-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

/* Form */
.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  font-size: 0.9rem;
  font-weight: 500;
  color: #334155;
}

.form-control {
  padding: 0.75rem;
  border: 1.5px solid #bdb9b9;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.2s;
}

.form-control:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

textarea.form-control {
  resize: vertical;
  font-family: inherit;
}

.form-row {
  display: grid;
  grid-template-columns: 250px 250px;
  gap: 1rem;
}

/* Error Alert */
.error-alert {
  background: #fee2e2;
  border: 1px solid #fecaca;
  color: #991b1b;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
}

/* Ações em Massa */
.bulk-actions {
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  padding: 1rem 1.5rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.bulk-actions span {
  color: #1e40af;
  font-weight: 500;
}

/* Loading */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #e2e8f0;
  border-top-color: #2563eb;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Tabela */
.table-container {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.products-table {
  width: 100%;
  border-collapse: collapse;
}

.products-table thead {
  background: #f8fafc;
  border-bottom: 2px solid #e2e8f0;
}

.products-table th {
  padding: 1rem;
  text-align: left;
  font-size: 0.85rem;
  font-weight: 600;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.products-table td {
  padding: 1rem;
  border-bottom: 1px solid #f1f5f9;
  color: #334155;
  font-size: 0.95rem;
}

.products-table tbody tr:hover {
  background: #f8fafc;
}

.product-name {
  font-weight: 500;
  color: #1e3a8a;
}

.product-description {
  color: #64748b;
  max-width: 300px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.product-price {
  font-weight: 600;
  color: #059669;
}

/* Stock Badge */
.stock-badge {
  display: inline-block;
  padding: 0.375rem 0.75rem;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 500;
}

.stock-ok {
  background: #d1fae5;
  color: #065f46;
}

.stock-low {
  background: #fed7aa;
  color: #92400e;
}

.stock-empty {
  background: #fee2e2;
  color: #991b1b;
}

/* Ações */
.actions {
  display: flex;
  gap: 0.5rem;
}

.btn-icon {
  background: transparent;
  border: none;
  padding: 0.5rem;
  border-radius: 6px;
  cursor: pointer;
  color: #64748b;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-icon:hover {
  background: #f1f5f9;
  color: #2563eb;
}

.btn-icon-danger:hover {
  background: #fef2f2;
  color: #dc2626;
}

/* Estado Vazio */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  color: #94a3b8;
}

.empty-state svg {
  opacity: 0.3;
  margin-bottom: 1rem;
}

.empty-state p {
  font-size: 1.1rem;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.modal {
  background: white;
  border-radius: 16px;
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  overflow: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #e2e8f0;
}

.modal-header h2 {
  font-size: 1.5rem;
  color: #1e3a8a;
  font-weight: 600;
}

.btn-close {
  background: transparent;
  border: none;
  cursor: pointer;
  color: #64748b;
  padding: 0.5rem;
  border-radius: 6px;
  transition: all 0.2s;
  display: flex;
  align-items: center;
}

.btn-close:hover {
  background: #f1f5f9;
  color: #1e3a8a;
}

.modal-body {
  padding: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  padding: 1.5rem 2rem;
  border-top: 1px solid #8d8d8d;
}

/* Checkbox customizado */
input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
  accent-color: #2563eb;
}

/* Responsividade */
@media (max-width: 768px) {
  .products-page {
    padding: 1rem;
  }

  .page-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .header-actions {
    width: 100%;
    flex-direction: column;
  }

  .filters-grid {
    grid-template-columns: 1fr;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .table-container {
    overflow-x: auto;
  }

  .products-table {
    min-width: 800px;
  }
}
</style>
