import './assets/main.css'

import { createApp } from 'vue'
import router from './routes'
import App from './App.vue'

const app = createApp(App)

app.use(router) // ← Usar o router
app.mount('#app')