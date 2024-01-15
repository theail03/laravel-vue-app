<template>
  <div>
    <div class="info-item">
      <div>Rows:</div>
      <div>{{ matrix.rows }}</div>
    </div>
    <div class="info-item">
      <div>Columns:</div>
      <div>{{ matrix.columns }}</div>
    </div>
    <div class="info-item">
      <div>Create Date:</div>
      <div>{{ matrix.created_at }}</div>
    </div>
    <div class="info-item">
      <div>Update Date:</div>
      <div>{{ matrix.updated_at }}</div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  matrix: {
    type: Object,
    required: true
  }
})
</script>
  