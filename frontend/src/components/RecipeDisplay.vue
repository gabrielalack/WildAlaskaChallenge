<template>
    <div v-if="loading">Loading...</div>
    <div v-else="loading">
        <div class="recipe-heading">
            <div>
                <div><h2>{{ recipe.name }}</h2></div>
                <div>
                    <div>Author: {{ recipe.author }} ({{ recipe.email }})</div>
                    <div>{{ recipe.description }}</div>
                </div>
            </div>
            <div>
                <h3>Ingredients</h3>
                <ul>
                    <li v-for="item in recipe.ingredients">{{ item }}</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="directions">
            <h3>Directions</h3>
            <ol>
                <li v-for="item in recipe.steps">{{ item }}</li>
            </ol>
        </div>
</template>
<style scoped>
.recipe-heading {
    display: flex;
    justify-content: space-between;
}

.directions {
    display: block;
}
</style>

<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import {useRoute} from "vue-router";

const route = useRoute()

const loading = ref(true);
const recipe = ref({});

const fetchRecipe = async () => {
    const slug = route.params.slug;
    const response = await axios.get(`/recipe/${slug}`);
    recipe.value = response.data;
    loading.value = false;
}

onMounted(fetchRecipe); // Fetch items on component mount

</script>