<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import Modal from '@/Components/Modal.vue'
import NavLink from '@/Components/NavLink.vue'

const showProjectDialog = ref(false)

const closeModal = () => {
  showProjectDialog.value = false

}

const props = defineProps({
  projects: Array
})


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
  <Head title="Projects" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Projects
        </h2>
        <PrimaryButton
          @click="showProjectDialog = true"
        >
          Create Project
        </PrimaryButton>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          v-if="!projects.length > 0"
          class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4"
        >
          <div class="p-6 text-gray-900">
            Add Project to get started
          </div>
        </div>

        <div class="overflow-x-auto relative">
          <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                <th
                  scope="col"
                  class="py-3 px-6"
                >
                  Name
                </th>
                <th
                  scope="col"
                  class="py-3 px-6"
                >
                  Repository
                </th>
                <th
                  scope="col"
                  class="py-3 px-6"
                >
                  Last Deployed
                </th>


                <th
                  scope="col"
                  class="py-3 px-6"
                >
                  #
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="project in projects"
                :key="project.id"
                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
              >
                <th
                  scope="row"
                  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                >
                  <NavLink
                    :href="route('projects.show', { project: project.id })"
                    :active="route().current('projects.show')"
                  >
                    {{ project.name }}
                  </NavLink>
                </th>

                <td class="py-4 px-6">
                  {{ project.repository }}
                </td>


                <td class="py-4 px-6">
                  {{ project.created_at }}
                </td>

                <td class="py-4 px-6">
                  ->
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>








    <Modal
      :show="showProjectDialog"
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
  </AuthenticatedLayout>
</template>
