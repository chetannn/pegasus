<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/inertia-vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import NavLink from '@/Components/NavLink.vue'
import AddProjectForm from '@/Pages/Projects/Partials/AddProjectForm.vue'


const showProjectDialog = ref(false)

const props = defineProps({
  projects: Array
})


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
          <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
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
                class="bg-white border-b"
              >
                <th
                  scope="row"
                  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap "
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


   <AddProjectForm :show="showProjectDialog" @close="showProjectDialog = false" />

  </AuthenticatedLayout>
</template>
