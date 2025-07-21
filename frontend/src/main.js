import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import PrimeVue from 'primevue/config';
import Nora from '@primeuix/themes/nora';

// Set axios default url 
// todo: set this up as an environment variable
axios.defaults.baseURL = 'http://localhost:8888/api'

const app = createApp(App)
app.use(PrimeVue, {
    // Default theme configuration
    theme: {
        preset: Nora,
        options: {
            darkModeSelector: false
        }
    }
 });
app.use(router)
app.mount('#app')


