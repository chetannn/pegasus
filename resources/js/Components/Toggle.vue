<script setup>
import { computed } from 'vue'
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue'

const emit = defineEmits(['update:modelValue'])

const props = defineProps({
  label: String,
  modelValue: Boolean
})

</script>

<template>
  <SwitchGroup>
    <div class="flex items-center">
      <SwitchLabel class="mr-4 text-sm">{{label}}</SwitchLabel>
        <Switch
        @update:modelValue="value => emit('update:modelValue', value)"
       :model-value="props.modelValue"
        :class='props.modelValue ? "bg-blue-600" : "bg-gray-200"'
        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
      >
        <span
          :class='props.modelValue ? "translate-x-6" : "translate-x-1"'
          class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
        />
      </Switch>
    </div>
    </SwitchGroup>
</template>
