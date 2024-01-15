<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Matrices</h1>
        <TButton color="green" :to="{ name: 'MatrixCreate' }">
          <PlusIcon class="w-5 h-5" />
          Add new Matrix
        </TButton>
      </div>
    </template>
    <div v-if="matrices.loading" class="flex justify-center">Loading...</div>
    <div v-else-if="matrices.data.length">
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 text-gray-700">
        <ListItem
          v-for="(matrix, ind) in matrices.data"
          :key="matrix.id"
          :item="matrix"
          :itemEdit="{ name: 'MatrixEdit', params: { id: matrix.id } }"
          :itemView="{ name: 'MatrixView', params: { id: matrix.id } }"
          class="opacity-0 animate-fade-in-down"
          :style="{ animationDelay: `${ind * 0.1}s` }"
          @delete="deleteMatrix(matrix)"
        >
          <!-- Content for the "top" slot inside ListItem -->
          <template v-slot:top>
            <div class="metric-value h-48">
              {{ matrix.rows }} x {{ matrix.columns }}
            </div>
          </template>

          <!-- Content for the "info" slot inside ListItem -->
          <template v-slot:info>
            <MatrixInfo :matrix="matrix" />
          </template>
        </ListItem>
      </div>
      <div class="flex justify-center mt-5">
        <nav
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
          <a
            v-for="(link, i) of matrices.links"
            :key="i"
            :disabled="!link.url"
            href="#"
            @click="getForPage($event, link)"
            aria-current="page"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
            :class="[
              link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 0 ? 'rounded-l-md bg-gray-100 text-gray-700' : '',
              i === matrices.links.length - 1 ? 'rounded-r-md' : '',
            ]"
            v-html="link.label"
          >
          </a>
        </nav>
      </div>
    </div>
    <div v-else class="text-gray-600 text-center py-16">
      <div v-if="publicMode">
        There are no public matrices available at the moment.
      </div>
      <div v-else>
        You don't have matrices yet.
      </div>
    </div>
  </PageComponent>
</template>

<script setup>
import store from "../store/store";
import { computed, watchEffect } from "vue";
import {PlusIcon} from "@heroicons/vue/solid"
import TButton from '../components/core/TButton.vue'
import PageComponent from "../components/PageComponent.vue";
import ListItem from "../components/ListItem.vue";
import MatrixInfo from "../components/MatrixInfo.vue";

const props = defineProps({
  publicMode: Boolean
});

const matrices = computed(() => store.state.matrices);

// Function to load matrices based on publicMode
function loadMatrices() {
  store.dispatch("getMatrices", { public: props.publicMode });
}

// Watch for changes to publicMode and fetch matrices accordingly
watchEffect(() => {
  loadMatrices();
});

function deleteMatrix(matrix) {
  if (
    confirm(
      `Are you sure you want to delete this matrix? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deleteMatrix", matrix.id).then(() => {
      store.dispatch("getMatrices");
    });
  }
}

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) return;
  
  store.dispatch("getMatrices", {
    url: link.url,
    public: props.publicMode
  });
}
</script>
  