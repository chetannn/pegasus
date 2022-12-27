<script setup>
import {  PencilSquareIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
        label: {
            type: String,
            default: 'Add Pipeline'
        }
})

const emit = defineEmits(['actionClick'])
</script>

<template>
<div class="bg-white max-w-xs w-full flex flex-col items-center divide divide-y rounded-md shadow-sm">
                        <div class="px-3 py-2 flex flex-col items-center justify-center">
                        <img src="https://laravel.com/img/logomark.min.svg" />
                        <h3 class="text-md text-gray-600">Laravel Latest</h3>
                        </div>
                        <button @click="emit('actionClick')" class="rounded-b group inline-flex w-full items-center justify-center text-sm font-normal px-3 py-2 hover:bg-green-600 hover:text-white">
                         <slot />
                         <span class="ml-2 text-gray-800 group-hover:text-white">{{ label }}</span>
                        </button>
                  </div>
</template>
