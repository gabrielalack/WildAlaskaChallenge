<script setup>
import { RouterView } from 'vue-router'
</script>
<template>
    <div class="app-layout">
    <header>
        <!-- Header content here -->
        <h1>Recipe Finder</h1>
        <nav>
        <router-link to="/">Home</router-link>
        </nav>
    </header>

    <main>
        <!-- This is where page-specific content will be rendered -->
        <RouterView />
    </main>

    <footer>
        <!-- Footer content here -->
        <p>&copy; 2025 Recipe Finder</p>
    </footer>
    </div>
</template>

<script>
export default {
    name: 'DefaultLayout',
};
</script>

<style scoped>
/* Basic styling for layout elements */
.app-layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

header, footer {
    background-color: #f0f0f0;
    padding: 10px;
    text-align: center;
}

main {
    flex-grow: 1; /* Allows main content to take available space */
    padding: 20px;
}
</style>