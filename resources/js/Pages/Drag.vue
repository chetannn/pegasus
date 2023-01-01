<script setup>
  import Draggable from 'vuedraggable'
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  import { ref } from 'vue'
import {TrashIcon} from '@heroicons/vue/24/outline'

  const steps = ref(['Clone New Release', 'Install Composer Dependencies', 'Link Storage Directory', 'Link Env File', 'Activate New Release', 'Purge Old Releases'])
  const drag = ref(false)
</script>

<template>
<AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Pipeline Steps
      </h2>
      </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Draggable
        v-model="steps"
        group="steps"
        item-key="index"
        class="space-y-2"
        drag-class="drag"
        ghost-class="ghost"
        tag="transition-group"
        @start="drag = true"
        @end="drag = false"
         :component-data="{
          tag: 'ul',
          type: 'transition-group',
          name: !drag ? 'flip-list' : null
        }"
        v-bind="{ animation: 200 }"
        >
        <template #item="{element}">
        <li class="bg-white overflow-hidden flex justify-between items-center p-6 shadow-sm sm:rounded-md">
          <div class="text-sm">{{ element }}</div>
          <button class="rounded-md hover:bg-gray-200 px-3 py-2">
                <TrashIcon class="w-5 h-5 text-gray-700" />
           </button>
        </li>
        </template>
        </Draggable>
      </div>
    </div>
</AuthenticatedLayout>
</template>

<style scoped>
.drag {
        @apply border-2 border-blue-600;
}
.ghost {
    @apply bg-gray-50 text-gray-400;
}
</style>
