<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import Notifications from '@kyvg/vue3-notification'
</script>

<template>
  <header>
    <img alt="Vue logo" class="logo" src="@/assets/logo.svg" width="125" height="125" />

    <div class="wrapper">
      <nav>
        <RouterLink to="/">Post Pet</RouterLink>
        <RouterLink to="/put-pet">Put Pet</RouterLink>
        <RouterLink to="/get-pet">Get Pet</RouterLink>
        <RouterLink to="/get-pet-by-status">Get Pet By Status</RouterLink>
        <RouterLink to="/get-pet-by-tags">Get Pet By Tags</RouterLink>
        <RouterLink to="/post-pet-by-parameters">Post Pet By Parameters</RouterLink>
        <RouterLink to="/delete-pet">Delete Pet</RouterLink>
        <RouterLink to="/delete-pet">Delete Pet</RouterLink>
        <RouterLink to="/upload-image-pet">Upload Image Pet</RouterLink>
        <RouterLink to="/get-inventory">Get Inventory</RouterLink>
        <RouterLink to="/post-order">Post Order</RouterLink>
        <RouterLink to="/get-order">Get Order</RouterLink>
        <RouterLink to="/delete-order">Delete Order</RouterLink>
      </nav>
    </div>
  </header>

  <RouterView />
<!--  <notifications position="bottom right"/>-->
</template>

<style scoped>
header {
  line-height: 1.5;
  max-height: 100vh;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: var(--color-text);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  border-left: 1px solid var(--color-border);
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;

    padding: 1rem 0;
    margin-top: 1rem;
  }
}
</style>
