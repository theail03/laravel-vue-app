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
            <div class="matrix-view-form-group grid-cols-2">
              <div> 
                <label for="rows" class="custom-label">Rows</label>
                <input type="number" name="rows" id="rows" v-model="model.rows" step="1" min="1" class="custom-input"/>
              </div>
              <div> 
                <label for="columns" class="custom-label">Columns</label>
                <input type="number" name="columns" id="columns" v-model="model.columns" step="1"  min="1" class="custom-input"/>
              </div>
            </div>
            <!--/ Rows and Columns -->

            <!-- Matrix and Image -->
            <div class="matrix-view-form-group">

              <!-- Matrix -->
              <div> 
                <label class="custom-label">Matrix</label>
                <div class="mt-1 grid justify-center gap-1" :style="{ 'grid-template-columns': `repeat(${computedColumns}, min-content)` }">
                  <template v-for="rowIndex in computedRows">
                    <div v-for="colIndex in computedColumns" 
                        :key="`${rowIndex}-${colIndex}`" 
                        class="w-6 h-6 bg-gray-400 relative"
                        @click="selectCell(rowIndex, colIndex)">
                      <!-- Image or color for each cell -->
                      <img v-if="getImage(rowIndex, colIndex)" 
                          :src="getImage(rowIndex, colIndex)" 
                          :alt="`Image for row ${rowIndex} column ${colIndex}`" 
                          class="w-full h-full object-cover absolute inset-0" />
                      <!-- Selection Indicator -->
                      <div class="absolute inset-0" :class="{ 'ring-2 ring-blue-500 ring-opacity-75': isSelectedCell(rowIndex, colIndex) }"></div>
                      <!-- Hover Overlay -->
                      <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-25"></div>
                    </div>
                  </template>
                </div>
              </div>
              <!--/ Matrix -->

              <!-- Image -->
              <div v-if="route.params.id">
                <label class="custom-label">
                  Image
                </label>
                <div class="flex">
                  <button
                    type="button"
                    class="relative overflow-hidden bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
                  >
                    Change
                    <input
                      type="file"
                      @change="onImageChoose"
                      class="absolute left-0 top-0 w-full h-full opacity-0 cursor-pointer"
                    />
                  </button>
                  <TButton type="button" color="red">
                    <TrashIcon class="w-5 h-5 mr-2" />
                    Delete
                  </TButton>
                </div>
                <div class="mt-1 flex items-center justify-center">
                  <img
                    v-if="selectedImage"
                    :src="selectedImage"
                    :alt="model.title"
                    class="w-4/5 object-cover"
                  />
                  <span
                    v-else
                    class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-[80%] w-[80%] text-gray-300"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </span>
                </div>
              </div>
              <!--/ Image -->

            </div>
            <!--/ Matrix and Image -->
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
  const model = ref({
    title: "",
    slug: "",
    rows: 1,
    columns: 1,
  });

  const matrixImages = ref([]);

  const selectedCell = ref({
    row: null,
    column: null,
  });

  const maxRows = 20;
  const maxColumns = 20;

  const computedRows = computed(() => Math.min(model.value.rows, maxRows));
  const computedColumns = computed(() => Math.min(model.value.columns, maxColumns));

  const selectedImage = computed(() => {
    if (!selectedCell.value.row || !selectedCell.value.column) {
      return null;
    }
    
    return getImage(selectedCell.value.row, selectedCell.value.column);
  });

  function getImage(row, column) {
    // matrixImages is an array of image objects with row, column, and path properties
    const image = matrixImages.value.find(image => 
      image.row === row && image.column === column
    );
    return image ? image.path : null;
  }
  
  // Watch to current matrix data change and when this happens we update local model
  watch(
    () => store.state.currentMatrix.data,
    (newVal, oldVal) => {
      model.value = {
        ...JSON.parse(JSON.stringify(newVal)),
      };
    }
  );

  // Watch to images data change and when this happens we update local model
  watch(
    () => store.state.images.data,
    (newVal, oldVal) => {
      matrixImages.value = [...newVal];
    }
  );
  
  // If the current component is rendered on matrix update route we make a request to fetch matrix
  if (route.params.id) {
    store.dispatch("getMatrix", route.params.id);
    store.dispatch("getImages", route.params.id);
  }

  // Function to select a cell
  function selectCell(rowIndex, colIndex) {
    selectedCell.value.row = rowIndex;
    selectedCell.value.column = colIndex;
  }

  // Function to check if a cell is selected
  function isSelectedCell(rowIndex, colIndex) {
    return selectedCell.value.row === rowIndex && selectedCell.value.column === colIndex;
  }

  function onImageChoose(ev) {
    // Check if a cell is selected
    if (selectedCell.value.row === null || selectedCell.value.column === null) {
      store.commit("notify", {
        type: "error",
        message: "Please select a cell first.",
      });
      ev.target.value = "";
      return; // Exit the function early
    }

    const file = ev.target.files[0];

    const reader = new FileReader();
    reader.onload = () => {
      store.dispatch("saveImage", { ...selectedCell.value, matrixId: route.params.id, data: reader.result }).then(({ data }) => {
        store.commit("notify", {
          type: "success",
          message: "The image was successfully saved",
        });
        ev.target.value = "";
      });
    };
    reader.readAsDataURL(file);
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
  