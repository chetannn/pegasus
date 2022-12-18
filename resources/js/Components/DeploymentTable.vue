<script setup>
import { ArrowRightCircleIcon } from '@heroicons/vue/24/outline'

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
        <td class="py-4 px-6">
          {{ deployment.created_at }}
        </td>

        <td class="py-4 px-6">
          {{ deployment.committer }}
        </td>

        <td class="py-4 px-6">
          {{ deployment.commit_hash }}
        </td>

        <td class="py-4 px-6">
            <div class="inline-block px-2 py-1 text-xs text-slate-700 font-bold bg-yellow-500 rounded-full">Deploying...</div>
        </td>

        <td class="py-4 px-6">
           <ArrowRightCircleIcon class="w-5 h-5 text-slate-700" />
        </td>
      </tr>
    </tbody>
  </table>
</template>
