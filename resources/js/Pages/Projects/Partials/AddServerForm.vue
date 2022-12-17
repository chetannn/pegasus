<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  showDialog: Boolean,
  project: Object
})


const emit = defineEmits(['close'])
const closeModal = () => emit('close')

const form = useForm({
  name: '',
  ip_address: '',
  username: '',
  project_path: '',
})

const saveServer = () => {
  form.post(route('servers.store', { project: props.project.id }), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation')
        passwordInput.value.focus()
      }
      if (form.errors.current_password) {
        form.reset('current_password')
      }
    },
  })
}


</script>

<template>
  <Modal
    :show="showDialog"
    @close="closeModal"
  >
    <div class="p-3">
      <header>
        <h2 class="text-lg font-medium text-gray-900">
          Add Server
        </h2>

        <p class="mt-1 text-sm text-gray-600">
          Ensure your account is using a long, random password to stay secure.
        </p>
      </header>

      <div class="mt-3">
        <InputLabel
          for="current_password"
          value="Name"
        />

        <TextInput
          id="current_password"
          ref="currentPasswordInput"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full"
          autocomplete="current-password"
        />

        <InputError
          :message="form.errors.name"
          class="mt-2"
        />
      </div>

      <div class="mt-3">
        <InputLabel
          for="password"
          value="IP Address"
        />

        <TextInput
          id="password"
          ref="passwordInput"
          v-model="form.ip_address"
          type="text"
          class="mt-1 block w-full"
          autocomplete="new-password"
        />

        <InputError
          :message="form.errors.ip_address"
          class="mt-2"
        />
      </div>

      <div class="mt-3">
        <InputLabel
          for="username"
          value="Connect As"
        />

        <TextInput
          id="username"
          v-model="form.username"
          type="text"
          class="mt-1 block w-full"
          autocomplete="off"
        />

        <InputError
          :message="form.errors.username"
          class="mt-2"
        />
      </div>

      <div class="mt-3">
        <InputLabel
          for="project_path"
          value="Project Path"
        />

        <TextInput
          id="project_path"
          v-model="form.project_path"
          type="text"
          class="mt-1 block w-full"
          autocomplete="off"
        />

        <InputError
          :message="form.errors.project_path"
          class="mt-2"
        />
      </div>

      <div class="mt-3 flex items-center gap-4">
        <PrimaryButton
          :disabled="form.processing"
          @click="saveServer()"
        >
          Save
        </PrimaryButton>

        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p
            v-if="form.recentlySuccessful"
            class="text-sm text-gray-600"
          >
            Saved.
          </p>
        </Transition>
      </div>
    </div>
  </Modal>
</template>
