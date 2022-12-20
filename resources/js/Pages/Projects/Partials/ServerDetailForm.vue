<script setup>
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  showDialog: Boolean,
  server: Object
})

const emit = defineEmits(['close'])

const closeModal = () => emit('close')

</script>


<template>
  <Modal
    :show="showDialog"
    @close="closeModal"
  >
    <div class="p-3">
      <header>
        <h2 class="text-lg font-medium text-gray-900">
          Server Detail
        </h2>

        <p class="mt-1 text-sm text-gray-600">
          Please do not share the public key
        </p>
      </header>

      <div class="mt-3 p-2 w-md overflow-x-auto  bg-gray-100">
        <div class="whitespace-pre-line">
                   {{ server.public_key }}
                </div>
      </div>
    </div>
  </Modal>
</template>
