<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
          project: Object
        })

const projectNameInput = ref(null);


const form = useForm({
    name: props.project.name,
});

const updateProjectName = () => {
    form.put(route('project_settings.update', { project: props.project.id }), {
        onError: () => {
            if (form.errors.name) {
                form.reset('name');
                projectNameInput.value.focus();
            }
        },
    })
};
</script>


<template>
    <Head title="Projects-Show" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Project Settings</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-around">

            <div>Tab</div>


    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Project Name</h2>

            <p class="mt-1 text-sm text-gray-600">
                project name
            </p>
        </header>

        <form @submit.prevent="updateProjectName" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    ref="projectNameInput"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="off"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>


            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>

                </div>
            </div>

            </div>

        </AuthenticatedLayout>
</template>
