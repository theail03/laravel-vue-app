<template>
    <MatricesDashboard viewAllLink="Matrices">
        <template v-slot:top-center-content="slotProps">
            Sign up to create your own matrices
        </template>
        <template v-slot:left-title>
            Latest Public Matrix
        </template>
        <template v-slot:left-buttons="slotProps">
            <TButton :to="{ name: 'MatrixView', params: { id: slotProps.latestMatrixId } }" link>
                <EyeIcon class="w-5 h-5 mr-2" />
                View Matrix
            </TButton>
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
    </MatricesDashboard>
</template>

<script setup>
import { EyeIcon } from "@heroicons/vue/solid"
import TButton from "../components/core/TButton.vue";
</script>
