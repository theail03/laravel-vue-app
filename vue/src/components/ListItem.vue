<template>
  <div
    class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[470px]"
  >
    <div class="w-full h-48">
      <slot name="top"></slot>
    </div>
    <h4 class="mt-4 text-lg font-bold">{{ item.title }}</h4>
    <div class="overflow-hidden flex-1">
      <slot name="info"></slot>
    </div>

    <div class="flex justify-between items-center mt-3">
      <TButton :to="itemEdit" v-if="!publicMode">
        <PencilIcon class="wo-5 h-5 mr-2 " />
        Edit
      </TButton>
      <div class="flex items-center">
        <TButton :to="itemView" circle link>
          <EyeIcon class="w-5 h-5" />
        </TButton>

        <TButton v-if="item.id && !publicMode" @click="emit('delete', item)" circle link color="red">
          <TrashIcon class="w-5 h-5" />
        </TButton>
      </div>
    </div>
  </div>
</template>

<script setup>
import TButton from "./core/TButton.vue";
import { PencilIcon, EyeIcon, TrashIcon } from '@heroicons/vue/solid'

const { item } = defineProps({
  item: Object,
  itemEdit: String,
  itemView: String,
  publicMode: Boolean,
});
const emit = defineEmits(["delete", "edit"]);
</script>

<style></style>
