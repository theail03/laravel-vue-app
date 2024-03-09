<template>
  <a :href="googleAuthUrl">
    <img src="../../assets/btn_google_signin_dark_normal_web.png" alt="Sign in with Google" />
  </a>
</template>

<script setup>
import { ref } from "vue";
const googleAuthUrl = ref(`${import.meta.env.VITE_API_BASE_URL}/google-auth/redirect`);
</script>
