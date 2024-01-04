<template>
    <PageComponent>
      <template v-slot:header>
        <div class="flex items-center justify-between">
          <h1 class="text-3xl font-bold text-gray-900">
            {{ route.params.id ? model.title : "Create a Matrix" }}
          </h1>
  
          <div class="flex">
            <TButton v-if="route.params.id" color="red" @click="deleteMatrix()">
              <TrashIcon class="w-5 h-5 mr-2" />
              Delete
            </TButton>
          </div>
        </div>
      </template>
      <div v-if="matrixLoading" class="flex justify-center">Loading...</div>
      <form v-else @submit.prevent="saveMatrix" class="animate-fade-in-down">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
          <!-- Matrix Fields -->
          <div class="px-4 py-5 bg-white space-y-6 sm:p-6">  
            
            <!-- Title -->
            <div>
              <label for="title" class="custom-label">Title</label>
              <input type="text" name="title" id="title" v-model="model.title" autocomplete="matrix_title" class="custom-input"/>
            </div>
            <!--/ Title -->

            <!-- Rows and Columns -->
            <div class="matrix-view-form-group">
              <div> 
                <label for="rows" class="custom-label">Rows</label>
                <input type="number" name="rows" id="rows" v-model="model.rows" step="1" min="1" class="custom-input"/>
              </div>
              <div> 
                <label for="columns" class="custom-label">Columns</label>
                <input type="number" name="columns" id="columns" v-model="model.columns" step="1"  min="1" class="custom-input"/>
              </div>
            </div>
            <!-- Rows and Columns -->

            <!-- Matrix and Image -->
            <div class="matrix-view-form-group">
              <div> 
                <label class="custom-label">Matrix</label>
                <div class="grid justify-start gap-1" :style="{ 'grid-template-columns': `repeat(${computedColumns}, min-content)` }">
                  <template v-for="rowIndex in computedRows">
                    <div v-for="colIndex in computedColumns" :key="`${rowIndex}-${colIndex}`" class="w-6 h-6 bg-gray-400">
                      <!-- Content for each cell -->
                    </div>
                  </template>
                </div>
              </div>
              <div v-if="route.params.id"> 
                <label class="custom-label">Image</label>
              </div>
            </div>
            <!-- Matrix and Image -->
          </div>
          
          <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <TButton>
              <SaveIcon class="w-5 h-5 mr-2" />
              Save
            </TButton>
          </div>
        </div>
      </form>
    </PageComponent>
  </template>
  
  <script setup>
  import { computed, ref, watch } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import { SaveIcon, TrashIcon } from '@heroicons/vue/solid'
  import store from "../store/store";
  import PageComponent from "../components/PageComponent.vue";
  import TButton from "../components/core/TButton.vue";
  import "../styles/MatrixView.css";
  
  const router = useRouter();
  
  const route = useRoute();
  
  // Get matrix loading state, which only changes when we fetch matrix from backend
  const matrixLoading = computed(() => store.state.currentMatrix.loading);
  
  // Create empty matrix
  let model = ref({
    title: "",
    slug: "",
    rows: 1,
    columns: 1,
  });

  const maxRows = 20;
  const maxColumns = 20;

  const computedRows = computed(() => Math.min(model.value.rows, maxRows));
  const computedColumns = computed(() => Math.min(model.value.columns, maxColumns));
  
  // Watch to current matrix data change and when this happens we update local model
  watch(
    () => store.state.currentMatrix.data,
    (newVal, oldVal) => {
      model.value = {
        ...JSON.parse(JSON.stringify(newVal)),
      };
    }
  );
  
  // If the current component is rendered on matrix update route we make a request to fetch matrix
  if (route.params.id) {
    store.dispatch("getMatrix", route.params.id);
  }
  
  /**
   * Create or update matrix
   */
  function saveMatrix() {
    let action = "created";
    if (model.value.id) {
      action = "updated";
    }
    store.dispatch("saveMatrix", { ...model.value }).then(({ data }) => {
      store.commit("notify", {
        type: "success",
        message: "The matrix was successfully " + action,
      });
      router.push({
        name: "MatrixView",
        params: { id: data.data.id },
      });
    });
  }
  
  function deleteMatrix() {
    if (
      confirm(
        `Are you sure you want to delete this matrix? Operation can't be undone!!`
      )
    ) {
      store.dispatch("deleteMatrix", model.value.id).then(() => {
        router.push({
          name: "Matrices",
        });
      });
    }
  }
  </script>
  
  <style></style>
  