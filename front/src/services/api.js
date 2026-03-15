import axios from 'axios'

// Configuração base da API
const api = axios.create({
    baseURL: 'http://localhost:8000/api', // URL do Laravel
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
})

// Interceptor para tratamento de erros global (opcional)
api.interceptors.response.use(
    response => response,
    error => {
        // Tratamento de erros customizado
        if (error.response) {
            // Erro com resposta do servidor
            console.error('Erro da API:', error.response.data)
        } else if (error.request) {
            // Erro de rede
            console.error('Erro de rede:', error.request)
        }
        return Promise.reject(error)
    }
)

export default {
    /**
     * Listar produtos com filtros opcionais
     * @param {Object} filters - { name, brand, stock }
     * @param {Number} page - Número da página
     */
    getProducts(filters = {}, page = 1) {
        const params = { page, ...filters }
        // Remove parâmetros vazios
        Object.keys(params).forEach(key => {
            if (!params[key]) delete params[key]
        })

        return api.get('/products', { params })
    },

    /**
     * Buscar produto por ID
     * @param {Number} id - ID do produto
     */
    getProduct(id) {
        return api.get(`/products/${id}`)
    },

    /**
     * Criar novo produto
     * @param {Object} data - { name, description, brand, price, stock }
     */
    createProduct(data) {
        return api.post('/products', data)
    },

    /**
     * Atualizar produto existente
     * @param {Number} id - ID do produto
     * @param {Object} data - { name, description, brand, price, stock }
     */
    updateProduct(id, data) {
        return api.put(`/products/${id}`, data)
    },

    /**
     * Deletar produtos em massa
     * @param {Array} ids - Array de IDs [1, 2, 3]
     */
    deleteProducts(ids) {
        return api.delete('/products', {
            data: { data: ids }
        })
    }
}