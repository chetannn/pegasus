<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import IntegrationCard from '@/Components/IntegrationCard.vue'

const props = defineProps({
  providers: Array
})
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Integrations
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4 mb-10">
          <IntegrationCard
            v-for="provider in providers"
            :key="provider.id"
            :provider="provider"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
