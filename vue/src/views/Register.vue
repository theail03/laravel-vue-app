<template>
  <div>
    <div>
      <img
        class="mx-auto h-12 w-auto"
        src="../assets/logo.png"
        alt="Workflow"
      />
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Register for free
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Or
        {{ " " }}
        <router-link
          :to="{ name: 'Login' }"
          class="account-action-text"
        >
          login to your account
        </router-link>
      </p>
    </div>
    <form class="mt-8 space-y-6" @submit="register">
      <Alert
        v-if="Object.keys(errors).length"
        class="flex-col items-stretch text-sm"
      >
        <div v-for="(field, i) of Object.keys(errors)" :key="i">
          <div v-for="(error, ind) of errors[field] || []" :key="ind">
            * {{ error }}
          </div>
        </div>
      </Alert>

      <input type="hidden" name="remember" value="true" />
      <div class="rounded-md shadow-sm -space-y-px">
        <TInput
          name="name"
          v-model="user.name"
          :errors="errors"
          placeholder="Full Name"
          inputClass="rounded-t-md"
        />
        <TInput
          type="email"
          name="email"
          v-model="user.email"
          :errors="errors"
          placeholder="Email Address"
        />
        <TInput
          type="password"
          name="password"
          v-model="user.password"
          :errors="errors"
          placeholder="Password"
        />
        <TInput
          type="password"
          name="password_confirmation"
          v-model="user.password_confirmation"
          :errors="errors"
          placeholder="Confirm Password"
          inputClass="rounded-b-md"
        />
      </div>
      <div>
        <TButtonLoading
          :loading="loading"
          class="w-full relative justify-center"
        >
          Sign up
        </TButtonLoading>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import TButtonLoading from "../components/core/TButtonLoading.vue";
import TInput from "../components/core/TInput.vue";
import Alert from "../components/Alert.vue";

const user = {
  name: "",
  email: "",
  password: "",
};
const loading = ref(false);
const errors = ref({});

function register(ev) {
  ev.preventDefault();
  errors.value.googleLogin = ["Please use 'Login with Google' to register."];
}
</script>
