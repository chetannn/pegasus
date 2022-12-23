<script setup>
import { ArrowRightCircleIcon, EllipsisVerticalIcon,ArrowUturnLeftIcon, CircleStackIcon, TrashIcon, CloudArrowUpIcon } from '@heroicons/vue/24/outline'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'

        const props = defineProps({
                        deployments: {
                            type: Array
                        }
                })
</script>

<template>
  <table class="w-full text-sm text-left text-gray-500">
    <thead class="text-sm text-gray-700 bg-gray-50">
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
          Action
        </th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="deployment in deployments"
        :key="deployment.id"
        class="bg-white border-b"
      >
        <td class="py-3 px-5">
          {{ deployment.created_at }}
        </td>

        <td class="py-3 px-5">
          <div class="inline-flex justify-center items-center space-x-2">
          <img :src="deployment.committer_avatar_url" class="rounded-full h-10 w-10" />
          <a target="_blank" :href="deployment.committer_url"><span class="">{{ deployment.committer }}</span></a>
          </div>
        </td>

        <td class="py-3 px-5">
          <a class="text-blue-600 hover:border-b-2 hover:border-blue-600" :href="deployment.commit_url" target="_blank">{{ deployment.commit_hash }}</a>
        </td>

        <td class="py-3 px-5">
            <div class="inline-block px-2 py-1 text-xs text-slate-700 font-bold bg-yellow-500 rounded-full">Deploying...</div>
        </td>

        <td class="py-3 px-5">
         <Menu as="div" class="relative inline-block text-left">
      <div>
        <MenuButton
          class="inline-flex w-full justify-center rounded-md px-4 py-2 text-sm font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
        >
          <EllipsisVerticalIcon
            class="ml-2 h-5 w-5"
            aria-hidden="true"
          />
        </MenuButton>
      </div>

      <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <MenuItems
          class="absolute z-50 right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        >
          <div class="px-1 py-1">
            <MenuItem v-slot="{ active }">
              <button
                :class="[
                  active ? 'bg-green-500 text-white' : 'text-gray-900',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
              >
                <CloudArrowUpIcon
                  :active="active"
                :class="[
                  active ? 'text-white' : 'text-green-400',
                  'mr-2 h-5 w-5',
                  ]"
                  aria-hidden="true"
                />
                Re-Deploy
              </button>
            </MenuItem>
            <MenuItem v-slot="{ active }">
              <button
                :class="[
                  active ? 'bg-green-500 text-white' : 'text-gray-900',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
              >
                <CircleStackIcon
                  :class="[
                  active ? 'text-white' : 'text-green-400',
                  'mr-2 h-5 w-5',
                  ]"
                  aria-hidden="true"
                />
                Logs
              </button>
            </MenuItem>
          </div>
          <div class="px-1 py-1">
            <MenuItem v-slot="{ active }">
              <button
                :class="[
                  active ? 'bg-red-500 text-white' : 'text-gray-900',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
              >
                <TrashIcon
                  :class="[
                  active ? 'text-white' : 'text-red-400',
                  'mr-2 h-5 w-5',
                  ]"
                  aria-hidden="true"
                />
                Delete
              </button>
            </MenuItem>
          </div>
        </MenuItems>
      </transition>
    </Menu>
        </td>
      </tr>
    </tbody>
  </table>
</template>
