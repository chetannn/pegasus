<script setup>
import GithubIcon from '@/Components/GithubIcon.vue'
import GitlabIcon from '@/Components/GitlabIcon.vue'
import BitbucketIcon from '@/Components/BitbucketIcon.vue'

const props = defineProps({
  provider: Object
})
</script>

<template>
  <div class="shadow-md shadow-slate-200">
    <div class="p-4 bg-white rounded-t">
      <div class="text-center">
        <div class="flex items-center justify-center">
          <GithubIcon
            v-if="provider.id === 1"
            class="h-10 w-10"
          />
          <GitlabIcon
            v-else-if="provider.id === 2"
            class="h-10 w-10"
          />
          <BitbucketIcon
            v-else-if="provider.id === 3"
            class="h-10 w-10"
          />
        </div>
        <h5 class="text-lg mt-2 mb-3 text-gray-800">
          {{ provider.name }}
        </h5>
      </div>
    </div>
                  
    <div class="text-center border-t">
      <a
        v-if="provider.status === 1"
        class="px-4 rounded-b py-2 bg-green-600 flex items-center justify-center text-white"
        href="/integrations/github"
      >Connected</a>
      <a
        v-else
        class="px-4 rounded-b py-2 hover:bg-green-600 flex items-center justify-center bg-white hover:text-white"
        href="/integrations/github"
      >Connect</a>
    </div>
  </div>
</template>
