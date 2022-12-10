<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import DangerButton from '@/Components/DangerButton.vue'
import Toggle from '@/Components/Toggle.vue'

const props = defineProps({
  project: Object
})

const projectNameInput = ref(null)


const form = useForm({
  name: props.project.name,
})

const updateProjectName = () => {
  form.put(route('project_settings.update', { project: props.project.id }), {
    onError: () => {
      if (form.errors.name) {
        form.reset('name')
        projectNameInput.value.focus()
      }
    },
  })
}
</script>


<template>
  <Head title="Projects-Show" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Project Settings
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <section>
            <header>
              <h2 class="text-lg font-medium text-gray-900">
                 Project Details
              </h2>

              <p class="mt-1 text-sm text-gray-600">
                project name
              </p>
            </header>

            <form
              class="mt-6 space-y-6"
              @submit.prevent="updateProjectName"
            >
              <div>
                <InputLabel
                  for="name"
                  value="Name"
                />

                <TextInput
                  id="name"
                  ref="projectNameInput"
                  v-model="form.name"
                  type="text"
                  class="mt-1 block w-full"
                  autocomplete="off"
                />

                <InputError
                  :message="form.errors.name"
                  class="mt-2"
                />
              </div>

              <div>
                <Toggle label="Deploy when code is pushed" />
              </div>


              <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">
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
            </form>
          </section>
        </div>


        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <section class="space-y-6">
            <header>
              <h2 class="text-lg font-medium text-gray-900">Source Control</h2>

              <p class="mt-1 text-sm text-gray-600">
                Once your project is deleted, all of its resources and data will be permanently deleted. Before deleting
                your project, please download any data or information that you wish to retain.
              </p>
            </header>



            <form
              class="mt-6 space-y-6"
              @submit.prevent="updateProjectName"
            >

              <div>
                <InputLabel
                  for="name"
                  value="Provider"
                />

                <TextInput
                  id="name"
                  ref="projectNameInput"
                  type="text"
                  class="mt-1 block w-full"
                  autocomplete="off"
                  value="GitHub"
                />

                <InputError
                  :message="form.errors.name"
                  class="mt-2"
                />
              </div>



              <div>
                <InputLabel
                  for="name"
                  value="Repository Url"
                />

                <TextInput
                  id="name"
                  ref="projectNameInput"
                  type="text"
                  class="mt-1 block w-full"
                  autocomplete="off"
                />

                <InputError
                  :message="form.errors.name"
                  class="mt-2"
                />
              </div>

              <div>
                <InputLabel
                  for="branch"
                  value="Branch"
                />

                <TextInput
                  id="name"
                  ref="projectNameInput"
                  type="text"
                  class="mt-1 block w-full"
                  autocomplete="off"
                />

                <InputError
                  :message="form.errors.name"
                  class="mt-2"
                />
              </div>

              <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">
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
            </form>
          </section>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <section class="space-y-6">
            <header>
              <h2 class="text-lg font-medium text-gray-900">
                Delete Project
              </h2>

              <p class="mt-1 text-sm text-gray-600">
                Once your project is deleted, all of its resources and data will be permanently deleted. Before deleting
                your project, please download any data or information that you wish to retain.
              </p>
            </header>

            <DangerButton @click="confirmUserDeletion">
              Delete Project
            </DangerButton>
          </section>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>
