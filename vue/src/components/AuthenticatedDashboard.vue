<!-- Previous file paths for this file:
- ./vue/src/views/AuthenticatedDashboard.vue (initial location)
- ./vue/src/components/AuthenticatedDashboard.vue (current location) 
-->
<template>
    <DashboardLayout viewAllLink="Matrices">
        <template v-slot:top-center-content="slotProps">
            <div class="metric-value">
                {{ slotProps.totalUserMatrices }}
            </div>
        </template>
        <template v-slot:left-title>
            Your Latest Matrix
        </template>
        <template v-slot:left-buttons="slotProps">
            <div class="flex justify-between mt-3">
                <TButton :to="{ name: 'MatrixEdit', params: { id: slotProps.latestMatrixId } }" link>
                    <PencilIcon class="w-5 h-5 mr-2" />
                    Edit Matrix
                </TButton>

                <TButton :to="{ name: 'MatrixView', params: { id: slotProps.latestMatrixId } }" link>
                    <EyeIcon class="w-5 h-5 mr-2" />
                    View Matrix
                </TButton>
            </div>
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
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from "./DashboardLayout.vue";
import { EyeIcon, PencilIcon } from "@heroicons/vue/solid"
import TButton from "./core/TButton.vue";
</script>
