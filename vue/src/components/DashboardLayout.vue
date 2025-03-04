<!-- Previous file paths for this file:
- ./vue/src/views/MatricesDashboard.vue (initial location)
- ./vue/src/views/DashboardLayout.vue 
- ./vue/src/components/DashboardLayout.vue (current location) 
-->
<template>
  <PageComponent title="Dashboard">
    <div v-if="loading" class="flex justify-center">Loading...</div>
    <div
      v-else
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-gray-700"
    >
      <DashboardCard class="order-1 lg:order-2" style="animation-delay: 0.1s">
        <template v-slot:title>Total Matrices From You</template>
        <slot name="top-center-content" :totalUserMatrices="data.totalUserMatrices">
        </slot>
      </DashboardCard>
      <DashboardCard class="order-2 lg:order-4" style="animation-delay: 0.2s">
        <template v-slot:title>Total Public Matrices</template>
        <div
          class="metric-value"
        >
          {{ data.totalPublicMatrices }}
        </div>
      </DashboardCard>
      <DashboardCard
        class="order-3 lg:order-1 row-span-2"
        style="animation-delay: 0.2s"
      >
        <template v-slot:title>
          <slot name="left-title">
          </slot>
        </template>
        <div v-if="latestMatrix">
          <div class="metric-value h-48">
            {{ latestMatrix.rows }} x {{ latestMatrix.columns }}
          </div>
          <h3 class="font-bold text-xl mb-3">{{ latestMatrix.title }}</h3>
          <MatrixInfo :matrix="latestMatrix" />
          <slot name="left-buttons" :latestMatrixId="latestMatrix.id">
          </slot>
        </div>
        <div v-else class="text-gray-600 text-center py-16">
          <slot name="left-no-content">
          </slot>
        </div>
      </DashboardCard>
      <DashboardCard class="order-4 lg:order-3 row-span-2" style="animation-delay: 0.3s">
        <template v-slot:title>
          <div class="flex justify-between items-center mb-3 px-2">
            <h3 class="text-2xl font-semibold">
              <slot name="right-title">
              </slot>  
            </h3>

            <router-link
              :to="{ name: viewAllLink }"
              link
              class="text-sm text-blue-500 hover:decoration-blue-500"
            >
              View all
            </router-link>
          </div>
        </template>

        <div v-if="data.latestMatrices?.length" class="text-left">
          <router-link
            :to="{ name: 'MatrixView', params: { id: matrix.id } }"
            v-for="matrix of data.latestMatrices"
            :key="matrix.id"
            class="block p-2 hover:bg-gray-100/90"
          >
            <div class="font-semibold">{{ matrix.title }}</div>
            <small>
              Matrix Made at:
              <i class="font-semibold">{{ matrix.created_at }}</i>
            </small>
          </router-link>
        </div>
        <div v-else class="text-gray-600 text-center py-16">
          <slot name="right-no-content">
          </slot> 
        </div>
      </DashboardCard>
    </div>
  </PageComponent>
</template>

<script setup>
import DashboardCard from "./core/DashboardCard.vue";
import PageComponent from "./PageComponent.vue";
import { computed } from "vue";
import { useStore } from "vuex";
import MatrixInfo from "./MatrixInfo.vue";

const props = defineProps({
  viewAllLink: String
})

const store = useStore();

const loading = computed(() => store.state.matricesDashboard.loading);
const data = computed(() => store.state.matricesDashboard.data);

const latestMatrix = computed(() => {
  return data.value.latestMatrices?.at(0) ?? null;
});

store.dispatch("getMatricesDashboardData");

</script>

<style scoped></style>
