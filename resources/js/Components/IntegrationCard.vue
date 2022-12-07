<script setup>
import GithubIcon from "@/Components/GithubIcon.vue"

const props = defineProps({
        providerName: String
})
</script>


<template>
<div class="shadow-md shadow-slate-200">
                <div class="p-4 bg-white rounded-t">
                <div class="text-center">
                <div class="flex items-center justify-center">
                         <GithubIcon class="h-10 w-10" />
                 </div>
                 <h5 class="text-lg mt-2 mb-3 text-gray-800">{{providerName}}</h5>
                </div>
                </div>
                  
                  <div class="text-center border-t">
                        <a class="px-4 rounded-b py-2 hover:bg-green-600 flex items-center justify-center bg-white hover:text-white" href="/integrations/github">Connect</a>
                  </div>
                </div>
</template>
