<template>
  <div>
    <h1 v-if="error === '403'">Access Denied</h1>
    <h1 v-else-if="error === '404'">Page Not Found</h1>
    <p>Sorry, the page you are looking for is {{ error === '403' ? 'restricted' : 'not available' }}.</p>
  </div>
</template>

<script>
export default {
  props: {
    error: String, 
  },
};
</script>

<style>
</style>
