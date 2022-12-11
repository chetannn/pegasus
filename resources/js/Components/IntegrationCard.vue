<script setup>
import GithubIcon from '@/Components/GithubIcon.vue'
import GitlabIcon from '@/Components/GitlabIcon.vue'
import BitbucketIcon from '@/Components/BitbucketIcon.vue'

const props = defineProps({
  provider: Object
})


function getIntegrationConnectLink() {
  const provider = props.provider
  let integrationLink = ''

  switch(provider.id) {
  case 1:
    integrationLink = '/integrations/github'
    break

  case 2:
    integrationLink = '/integrations/gitlab'
    break

  case 3:
    integrationLink = '/integrations/bitbucket'
  }

  return integrationLink
}



function getIntegrationDisConnectLink() {
  const provider = props.provider
  let integrationLink = ''

  switch(provider.id) {
  case 1:
    integrationLink = '/integrations/github/disconnect'
    break

  case 2:
    integrationLink = '/integrations/gitlab/disconnect'
    break

  case 3:
    integrationLink = '/integrations/bitbucket/disconnect'
  }

  return integrationLink
}

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
        class="px-4 rounded-b py-2 bg-green-600 flex items-center justify-center text-white hover:bg-red-600 group transition-all"
        :href="getIntegrationDisConnectLink()"
      >
       <span class="group-hover:hidden">Connected</span>
       <span class="hidden group-hover:inline-block">Disconnect</span>
        
      </a>
      <a
        v-else
        class="px-4 rounded-b py-2 hover:bg-green-600 flex items-center justify-center bg-white hover:text-white"
        :href="getIntegrationConnectLink()"
      >Connect</a>
    </div>
  </div>
</template>
