<template>
    <DashboardLayout viewAllLink="MatricesPublic">
        <template v-slot:top-center-content="slotProps">
            <div class="text-sm pb-4 flex-1 flex items-center justify-center">
                Sign in to start creating your own matrices!
            </div>
        </template>
        <template v-slot:left-title>
            Latest Public Matrix
        </template>
        <template v-slot:left-buttons="slotProps">
            <div class="flex justify-center mt-3">
                <TButton :to="{ name: 'MatrixView', params: { id: slotProps.latestMatrixId } }" link>
                    <EyeIcon class="w-5 h-5 mr-2" />
                    View Matrix
                </TButton>
            </div>
        </template>
        <template v-slot:left-no-content>
            There are no public matrices available at the moment
        </template>
        <template v-slot:right-title>
            Latest Public Matrices
        </template>  
        <template v-slot:right-no-content>
            There are no public matrices available at the moment.
        </template> 
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from "./DashboardLayout.vue";
import { EyeIcon } from "@heroicons/vue/solid"
import TButton from "../components/core/TButton.vue";
</script>
