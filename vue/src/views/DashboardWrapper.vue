<template>
  <component :is="selectedComponent"></component>
</template>

<script>
import GuestDashboard from '../components/GuestDashboard.vue';
import AuthenticatedDashboard from '../components/AuthenticatedDashboard.vue';
import { computed } from 'vue';
import store from '../store/store';

export default {
  setup() {
    // This computed property will automatically update when the store state changes
    const selectedComponent = computed(() => {
      return store.getters.isAuthenticated ? AuthenticatedDashboard : GuestDashboard;
    });

    return { selectedComponent };
  }
};
</script>