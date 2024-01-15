<template>
  <PageComponent title="Matrices Dashboard">
    <div v-if="loading" class="flex justify-center">Loading...</div>
    <div
      v-else
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-gray-700"
    >
      <DashboardCard class="order-1 lg:order-2" style="animation-delay: 0.1s">
        <template v-slot:title>Total of Your Matrices</template>
        <div
          class="metric-value"
        >
          {{ data.totalUserMatrices }}
        </div>
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
        <template v-slot:title>Your Latest Matrix</template>
        <div v-if="latestMatrix">
          <div class="metric-value h-48">
            {{ latestMatrix.rows }} x {{ latestMatrix.columns }}
          </div>
          <h3 class="font-bold text-xl mb-3">{{ latestMatrix.title }}</h3>
          <MatrixInfo :matrix="latestMatrix" />
          <div class="flex justify-between">
            <TButton :to="{ name: 'MatrixEdit', params: { id: latestMatrix.id } }" link>
              <PencilIcon class="w-5 h-5 mr-2" />
              Edit Matrix
            </TButton>

            <TButton :to="{ name: 'MatrixView', params: { id: latestMatrix.id } }" link>
              <EyeIcon class="w-5 h-5 mr-2" />
              View Matrix
            </TButton>
          </div>
        </div>
        <div v-else class="text-gray-600 text-center py-16">
          Your don't have matrices yet
        </div>
      </DashboardCard>
      <DashboardCard class="order-4 lg:order-3 row-span-2" style="animation-delay: 0.3s">
        <template v-slot:title>
          <div class="flex justify-between items-center mb-3 px-2">
            <h3 class="text-2xl font-semibold">Your Latest Matrices</h3>

            <a
              href="javascript:void(0)"
              class="text-sm text-blue-500 hover:decoration-blue-500"
            >
              View all
            </a>
          </div>
        </template>

        <div v-if="data.latestUserMatrices.length" class="text-left">
          <a
            href="#"
            v-for="matrix of data.latestUserMatrices"
            :key="matrix.id"
            class="block p-2 hover:bg-gray-100/90"
          >
            <div class="font-semibold">{{ matrix.title }}</div>
            <small>
              Matrix Made at:
              <i class="font-semibold">{{ matrix.created_at }}</i>
            </small>
          </a>
        </div>
        <div v-else class="text-gray-600 text-center py-16">
          Your don't have matrices yet
        </div>
      </DashboardCard>
    </div>
  </PageComponent>
</template>

<script setup>
import {EyeIcon, PencilIcon} from "@heroicons/vue/solid"
import DashboardCard from "../components/core/DashboardCard.vue";
import TButton from "../components/core/TButton.vue";
import PageComponent from "../components/PageComponent.vue";
import { computed } from "vue";
import { useStore } from "vuex";
import MatrixInfo from "../components/MatrixInfo.vue";

const store = useStore();

const loading = computed(() => store.state.matricesDashboard.loading);
const data = computed(() => store.state.matricesDashboard.data);

const latestMatrix = computed(() => {
  return data.value.latestUserMatrices?.at(0) ?? null;
});

store.dispatch("getMatricesDashboardData");

</script>

<style scoped></style>
