<template>
    <MatricesDashboard viewAllLink="MatricesPublic">
        <template v-slot:top-center-content="slotProps">
            <div class="metric-value">
                {{ slotProps.totalUserMatrices }}
            </div>
        </template>
        <template v-slot:left-title>
            Your Latest Matrix
        </template>
        <template v-slot:left-buttons="slotProps">
            <TButton :to="{ name: 'MatrixEdit', params: { id: slotProps.latestMatrixId } }" link>
                <PencilIcon class="w-5 h-5 mr-2" />
                Edit Matrix
            </TButton>

            <TButton :to="{ name: 'MatrixView', params: { id: slotProps.latestMatrixId } }" link>
                <EyeIcon class="w-5 h-5 mr-2" />
                View Matrix
            </TButton>
        </template>
        <template v-slot:left-no-content>
            Your don't have matrices yet
        </template>
        <template v-slot:right-title>
            Your Latest Matrices
        </template>  
        <template v-slot:right-no-content>
            Your don't have matrices yet
        </template> 
    </MatricesDashboard>
</template>

<script setup>
import { EyeIcon, PencilIcon} from "@heroicons/vue/solid"
import TButton from "../components/core/TButton.vue";
</script>
