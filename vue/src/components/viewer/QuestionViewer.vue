<template>
  <fieldset class="mb-4">
    <div>
      <legend class="text-base font-medium text-gray-900">
        {{ index + 1 }}. {{ question.question }}
      </legend>
      <p class="text-gray-500 text-sm">
        {{ question.description }}
      </p>
    </div>
    <div class="mt-3">
      <div v-if="question.type === 'select'">
        <select
          :value="modelValue"
          @change="emits('update:modelValue', $event.target.value)"
          class="custom-input py-2 px-3 border bg-white focus:outline-none"
        >
          <option value="">Please Select</option>
          <option v-for="option in question.data.options" :key="option.uuid" :value="option.text">
            {{ option.text }}
          </option>
        </select>
      </div>
      <div v-else-if="question.type === 'radio'">
        <div
          v-for="(option, ind) of question.data.options"
          :key="option.uuid"
          class="flex items-center"
        >
          <input
            :id="option.uuid"
            :name="'question' + question.id"
            :value="option.text"
            @change="emits('update:modelValue', $event.target.value)"
            type="radio"
            class="custom-control"
          />
          <label
            :for="option.uuid"
            class="ml-3 custom-label"
          >
            {{ option.text }}
          </label>
        </div>
      </div>
      <div v-else-if="question.type === 'checkbox'">
        <div
          v-for="(option, ind) of question.data.options"
          :key="option.uuid"
          class="flex items-center"
        >
          <input
            :id="option.uuid"
            v-model="model[option.text]"
            @change="onCheckboxChange"
            type="checkbox"
            class="custom-control rounded"
          />
          <label
            :for="option.uuid"
            class="ml-3 custom-label"
          >
            {{ option.text }}
          </label>
        </div>
      </div>
      <div v-else-if="question.type === 'text'">
        <input
          type="text"
          :value="modelValue"
          @input="emits('update:modelValue', $event.target.value)"
          class="custom-input"
        />
      </div>
      <div v-else-if="question.type === 'textarea'">
        <textarea
          :value="modelValue"
          @input="emits('update:modelValue', $event.target.value)"
          class="custom-input"
        ></textarea>
      </div>
    </div>
  </fieldset>
  <hr class="mb-4" />
</template>

<script setup>
import { ref } from "vue";
const { question, index, modelValue } = defineProps({
  question: Object,
  index: Number,
  modelValue: [String, Array],
});
const emits = defineEmits(["update:modelValue"]);

let model;
if (question.type === "checkbox") {
  model = ref({});
}

function shouldHaveOptions() {
  return ["select", "radio", "checkbox"].includes(question.type);
}

function onCheckboxChange($event) {
  const selectedOptions = [];
  for (let uuid in model.value) {
    if (model.value[uuid]) {
      selectedOptions.push(uuid);
    }
  }
  emits("update:modelValue", selectedOptions);
}
</script>

<style></style>
