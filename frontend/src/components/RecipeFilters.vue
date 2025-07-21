<template>
    <div class="flex justify-end">
        <InputText type="text" v-model="authorEmail" placeholder="Author Email" />
        <InputText type="text" v-model="ingredient" placeholder="Ingredient" />
        <InputText type="text" v-model="searchTerm" placeholder="Keyword Search" />


        <Button label="Filter" @click="$emit('applySearch')"/>
        <Button label="Clear" @click="clearFilters"/>
    </div>
</template>

<style>
</style>

<script setup>
import { InputText, Button } from 'primevue';
const emit = defineEmits(['applySearch']);
const authorEmail = defineModel('authorEmail');
const ingredient = defineModel('ingredient')
const searchTerm = defineModel('searchTerm');

const clearFilters = () => {
    authorEmail.value = '';
    ingredient.value = '';
    searchTerm.value = '';
    emit('applySearch');
}

</script>