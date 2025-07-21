<template>
     <RecipeFilters 
            v-model:searchTerm="searchTerm"
            v-model:authorEmail="authorEmail"
            v-model:ingredient="ingredient"
            @applySearch="fetchItems"
        ></RecipeFilters>
  <div>
    <div v-if="loading">Loading items...</div>
    <div v-else>
        <DataTable :value="items" tableStyle="min-width: 50rem" dataKey="id" selectionMode="single" @rowSelect="onRowSelect">
            <template #empty> No recipes found. </template>
            <Column field="name" header="Recipe"></Column>
            <Column field="author" header="Author"></Column>
            <Column field="email" header="Email"></Column>
            <Column field="description" header="Description"></Column>
        </DataTable>
    </div>
    <Button @click="prevPage" :disabled="currentPage === 1">Previous</Button>
    <span>Page {{ currentPage }} of {{ totalPages }}</span>
    <Button @click="nextPage" :disabled="currentPage === totalPages">Next</Button>
  </div>
</template>

<script setup>
import { ref, onMounted, watch} from 'vue';
import { useRouter } from 'vue-router';
import { Button } from 'primevue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import RecipeFilters from './RecipeFilters.vue';

const router = useRouter()

const items = ref([]);
const currentPage = ref(1);
const pageSize = ref(15);
const totalPages = ref(1);
const loading = ref(false);

// Filters
const searchTerm = ref('');
const authorEmail = ref('');
const ingredient = ref('');

const fetchItems = async () => {
  loading.value = true;
  try {
    var requestUrl = `/recipe?page=${currentPage.value}&pageSize=${pageSize.value}`;
    
    // Use search term filter if filled
    if(searchTerm.value.length) {
        requestUrl += `&search_term=${searchTerm.value}`;
    }

    // Use ingredient filter if filled
    if(ingredient.value.length) {
        requestUrl += `&ingredient=${ingredient.value}`;
    }

    // Use author email filter
    if(authorEmail.value.length) {
        requestUrl += `&email=${authorEmail.value}`;
    }

    const response = await axios.get(requestUrl);
    items.value = response.data.data;
    totalPages.value = response.data.meta.total;
    pageSize.value = response.data.meta.per_page
  } catch (error) {
    console.error('Error fetching items:', error);
  } finally {
    loading.value = false;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const onRowSelect = (event) => {
  const selectedItem = event.data;
  router.push({ 
    path: `recipe/${event.data.slug}`,
  }); 
};  

onMounted(fetchItems); // Fetch items on component mount

watch(currentPage, fetchItems); // Re-fetch items when currentPage changes
</script>