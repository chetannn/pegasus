<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { ArrowPathIcon } from '@heroicons/vue/24/solid'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AddServerForm from '@/Pages/Projects/Partials/AddServerForm.vue'
import ServerDetailForm from '@/Pages/Projects/Partials/ServerDetailForm.vue'

const props = defineProps({
  project: Object,
  servers: Array
})

const showAddServerDialog = ref(false)

const showServerDetailDialog = ref(false)

const closeAddServerDialog = () => showAddServerDialog.value = false
const closeServerDetailDialog = () => showServerDetailDialog.value = false

</script>

<template>
  <Head title="Projects-Show" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Project: {{ project.name }}
        </h2>

        <div class="flex space-x-4">
          <Link
            as="button"
            type="button"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
            :href="route('project_settings.index', { project: project.id })"
          >
            Settings
          </Link>
          <PrimaryButton>Deploy</PrimaryButton>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <TabGroup>
          <TabList class="flex space-x-1 p-1 border-b mb-4">
            <Tab
              v-slot="{selected}"
              as="template"
            >
              <button :class="['w-full py-2 text-sm font-medium rounded-md', selected ? 'bg-white' : '']">
                Deployments
              </button>
            </Tab>

            <Tab
              v-slot="{selected}"
              as="template"
            >
              <button :class="['w-full py-2 text-sm font-medium rounded-md', selected ? 'bg-white' : '']">
                Servers
              </button>
            </Tab>


            <Tab as="template">
              <button class="w-full py-2 text-sm font-medium">
                Deployment Hooks
              </button>
            </Tab>

            <Tab as="template">
              <button class="w-full py-2 text-sm font-medium">
                Heartbeats
              </button>
            </Tab>

            <Tab as="template">
              <button class="w-full py-2 text-sm font-medium">
                Notifications
              </button>
            </Tab>


            <Tab as="template">
              <button class="w-full py-2 text-sm font-medium">
                Collaborators
              </button>
            </Tab>
          </TabList>

          <TabPanels>
            <TabPanel>
              <div class="flex justify-between">
                <h2 class="text-lg font-normal mb-2">
                  Recent Deployments
                </h2>
              </div>

              <div class="overflow-x-auto relative">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                  <thead class="text-sm text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                      <th
                        scope="col"
                        class="py-3 px-6"
                      >
                        Started
                      </th>
                      <th
                        scope="col"
                        class="py-3 px-6"
                      >
                        Committer
                      </th>
                      <th
                        scope="col"
                        class="py-3 px-6"
                      >
                        Commit
                      </th>
                      <th
                        scope="col"
                        class="py-3 px-6"
                      >
                        Status
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
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" />
                  </tbody>
                </table>
              </div>
            </TabPanel>

            <TabPanel>
              <div class="flex justify-between mb-4">
                <h2 class="text-lg font-normal">
                  Servers
                </h2>
                <div class="flex space-x-2">
                  <SecondaryButton>Manage Environment</SecondaryButton>
                  <SecondaryButton @click="showAddServerDialog = true">
                    Add Server
                  </SecondaryButton>
                  <AddServerForm
                    :project="project"
                    :show-dialog="showAddServerDialog"
                    @close="closeAddServerDialog"
                  />
                </div>
              </div>

              <div class="overflow-x-auto relative">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                  <thead class="text-sm text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                        Connect As
                      </th>
                      <th
                        scope="col"
                        class="py-3 px-6"
                      >
                        IP Address
                      </th>
                      <th
                        scope="col"
                        class="py-3 px-6"
                      >
                        Connection Status
                      </th>
                      <th
                        scope="col"
                        class="py-3 px-6"
                      >
                        Action
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="server in servers"
                      :key="server.id"
                      class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                    >
                      <td class="py-4 px-6">
                        {{ server.name }}
                      </td>


                      <td class="py-4 px-6">
                        {{ server.username }}
                      </td>

                      <td class="py-4 px-6">
                        {{ server.ip_address }}
                      </td>

                      <td class="py-4 px-6">
                        <div class="flex">
                          <span v-if="server.connection_status === 0">Unknown</span>
                          <span
                            v-else-if="server.connection_status === 1"
                            class="text-green-600 font-bold"
                          >connected</span>
                          <span
                            v-else-if="server.connection_status === 2"
                            class="text-red-600 font-bold"
                          >failed</span>
                          <Link
                            as="button"
                            type="button"
                            method="put"
                            :href="route('servers.server_status', { server: server.id })"
                          >
                            <ArrowPathIcon class="ml-1 w-4 h-4" />
                          </Link>
                        </div>
                      </td>

                      <td class="py-4 px-6">
                        <SecondaryButton @click="showServerDetailDialog = true">
                          View Detail
                        </SecondaryButton>
                        <ServerDetailForm
                          :show-dialog="showServerDetailDialog"
                          :server="server"
                          @close="showServerDetailDialog = false"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
