<script setup>
import { ref } from 'vue'
import {  Link } from '@inertiajs/inertia-vue3'
import { ArrowPathIcon } from '@heroicons/vue/24/solid'
import AddServerForm from '@/Pages/Projects/Partials/AddServerForm.vue'
import ServerDetailForm from '@/Pages/Projects/Partials/ServerDetailForm.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
  servers: Array
})


const showServerDetailDialog = ref(false)

const closeServerDetailDialog = () => showServerDetailDialog.value = false
</script>

<template>
  <table class="w-full text-sm text-left text-gray-500">
    <thead class="text-sm text-gray-700 bg-gray-50">
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
        class="bg-white border-b"
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
</template>
