<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PipelineCard from '@/Components/PipelineCard.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import {  PlusIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
        project: {
           type: Object
        }
})

const form = useForm({
        })

function createPipeline() {
        form.post(route('pipelines.store', { project: props.project.id }), {
                onSuccess: () => {
                        console.log('done...');
                }
        })
}
</script>

<template>
  <Head title="Pipelines" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Deployment Pipelines
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
       <PipelineCard @actionClick="createPipeline()">
        <PlusIcon class="h-5 w-5 text-gray-700 group-hover:text-white" />
       </PipelineCard>
      </div>
      </div>
</AuthenticatedLayout>
</template>
