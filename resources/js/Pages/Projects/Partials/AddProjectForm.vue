<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
          show: {
           type: Boolean,
           default: false
        }
        })

const emit = defineEmits(['close'])
const closeModal = () => emit('close')

const form = useForm({
  name: '',
  provider_id: 1,
  repository: '',
})

const createProject = () => {
  form.post(route('projects.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      showProjectDialog.value = false
    },
    onError: () => {
    },
  })
}
</script>


<template>
    <Modal
      :show="props.show"
      @close="closeModal"
    >
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          Add Project
        </h2>

        <p class="mt-1 text-sm text-gray-600">
          Start deploying your app
        </p>

        <div class="mt-6">
          <InputLabel
            for="name"
            value="Name"
          />

          <TextInput
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Name"
            autocomplete="off"
          />
        </div>



        <div class="mt-6">
          <InputLabel
            for="provider"
            value="Provider"
          />

          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Provider"
            value="GitHub"
          />
        </div>




        <div class="mt-6">
          <InputLabel
            for="repository"
            value="Repository"
          />

          <TextInput
            id="repository"
            v-model="form.repository"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Repository"
          />
        </div>

        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal">
            Cancel
          </SecondaryButton>

          <PrimaryButton
            class="ml-3"
            :class="{ 'opacity-25': false}"
            :disabled="false"
            @click="createProject()"
          >
            Save Project
          </PrimaryButton>
        </div>
      </div>
    </Modal>
</template>
