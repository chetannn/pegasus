<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import { ref, watch, onMounted } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { ArrowPathIcon } from '@heroicons/vue/24/solid'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Toggle from '@/Components/Toggle.vue'
import ServerTable from '@/Components/ServerTable.vue'
import DeploymentTable from '@/Components/DeploymentTable.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AddServerForm from '@/Pages/Projects/Partials/AddServerForm.vue'
import ServerDetailForm from '@/Pages/Projects/Partials/ServerDetailForm.vue'
import { Inertia } from '@inertiajs/inertia'
import { RocketLaunchIcon, ServerStackIcon, BoltIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  project: Object,
  servers: Array,
  deployments: Array
})

const showAddServerDialog = ref(false)
const closeAddServerDialog = () => showAddServerDialog.value = false
const autoDeploy = ref(!!props.project.deploy_when_code_is_pushed)


const listenForConnectionStatus = () => {
        window.Echo
               .private(`server-status`)
               .listen('CheckServerConnectionStatus', (e) => {
                           reload()
                       })
}


const reload = () => {
    Inertia.reload({
        only: ['servers'],
        preserveScroll: true,
        headers: { 'X-Socket-ID': Echo.socketId() },
        onFinish: () => {

        }
    })
}


watch(autoDeploy, () => {
        Inertia.patch(route('project_settings.toggle_auto_deploy', { project: props.project.id }), {
                enableAutoDeploy: autoDeploy.value
        }, { preserveState: true })
})


function deployProject() {

        Inertia.get(`/deploy/${props.project.deployment_trigger_token}`)
}

onMounted(() => {
        listenForConnectionStatus()
})

</script>

<template>
  <Head title="Projects-Show" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Project: {{ project.name }}
        </h2>

        <div class="flex justify-center items-center space-x-4">
          <Toggle v-model="autoDeploy" label="Auto Deploy" />
          <Link
            as="button"
            type="button"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
            :href="route('project_settings.index', { project: project.id })"
          >
            Settings
          </Link>
          <PrimaryButton @click="deployProject()">Deploy</PrimaryButton>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <TabGroup>
          <div class="border-b text-center text-sm text-gray-600">
          <TabList as="ul" class="flex flex-wrap">
            <Tab
              v-slot="{selected}"
              class="mx-4 focus:outline-none"
              as="li"
            >
              <button
                :class="['inline-flex items-center p-3 border-b-2 border-transparent group font-normal', selected ? 'text-blue-600 border-blue-600' : 'hover:text-gray-700 hover:border-gray-300']"
               >
               <RocketLaunchIcon :class="['w-5 h-5 mr-2 text-gray-500 ', selected ? 'text-blue-600' : 'group-hover:text-gray-600']" />
              Deployments
              </button>
            </Tab>

            <Tab
              v-slot="{selected}"
              as="li"
              class="mx-4 focus:outline-none"
            >
              <button
                :class="['inline-flex items-center p-3 border-b-2 border-transparent group font-normal', selected ? 'text-blue-600 border-blue-600' : 'hover:text-gray-700 hover:border-gray-300']"
                >
               <ServerStackIcon 
                :class="['w-5 h-5 mr-2 text-gray-500', selected ? 'text-blue-600' : 'group-hover:text-gray-600']"
                />
                Servers
              </button>
            </Tab>


            <Tab
              v-slot="{selected}"
              as="li"
              class="mx-4 focus:outline-none"
            >
              <button 
                :class="['inline-flex items-center p-3 border-b-2 border-transparent group font-normal', selected ? 'text-blue-600 border-blue-600' : 'hover:text-gray-700 hover:border-gray-300']"
              >
               <BoltIcon 
                :class="['w-5 h-5 mr-2 text-gray-500', selected ? 'text-blue-600' : 'group-hover:text-gray-600']"
               />
                Deployment Hooks
              </button>
            </Tab>


          </TabList>
          </div>

          <TabPanels>
            <TabPanel>
              <div class="flex justify-between my-4">
                <h2 class="text-lg font-normal">
                  Recent Deployments
                </h2>
              </div>

              <div class="overflow-x-auto relative">
                <DeploymentTable :deployments="props.deployments" />
              </div>
            </TabPanel>

            <TabPanel>
              <div class="flex justify-between my-4">
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
                <ServerTable :servers="servers" />
              </div>
            </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
