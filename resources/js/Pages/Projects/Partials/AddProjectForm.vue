<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { ref, computed } from 'vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'
import GithubIcon from '@/Components/GithubIcon.vue'
import GitlabIcon from '@/Components/GitlabIcon.vue'
import BitbucketIcon from '@/Components/BitbucketIcon.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import {
  Combobox,
  ComboboxInput,
  ComboboxButton,
  ComboboxOptions,
  ComboboxOption,
  TransitionRoot,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import axios from 'axios'


const sourceProviders = ['Github', 'Gitlab', 'Bitbucket'];
const repositories = ref([])

let selected = ref(null)
let selectedSourceProviderIndex = ref(null)
let query = ref('')

function handleSelectedSourceProvider(providerIndex) {
        selectedSourceProviderIndex.value = providerIndex
        axios.get('/github/repositories')
             .then(res => {
                 repositories.value = res.data
                 selected.value = repositories.value[0]
             })

}

let filteredPeople = computed(() =>
  query.value === ''
    ? repositories.value
    : repositories.value.filter((repo) =>
        repo.full_name
      )
)

const props = defineProps({
          show: {
           type: Boolean,
           default: false
        }
        })

const emit = defineEmits(['close'])
const closeModal = () => emit('close')

const form = useForm({
  name: '',
  provider_id: selectedSourceProviderIndex,
  repository: ''
})

const createProject = () => {
  form
   .transform((data) => ({
        ...data,
        repository: selected.value ? selected.value.full_name : ''
   }))
  .post(route('projects.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      closeModal()
    }
  })
}
</script>


<template>
    <Modal
      :show="props.show"
      @close="closeModal"
    >
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          Add Project
        </h2>

        <p class="mt-1 text-sm text-gray-600">
          Start deploying your app
        </p>

        <div class="mt-6">
          <InputLabel
            for="name"
            value="Name"
          />

          <TextInput
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Name"
            autocomplete="off"
          />


        <InputError
          :message="form.errors.name"
          class="mt-2"
        />

        </div>


        <div class="mt-6">

          <InputLabel
            for="provider"
            value="Source Provider"
          />


          <div class="grid mt-1 grid-cols-4 gap-6">
          <div v-for="(provider, index) in sourceProviders" :key="index" 
          @click="handleSelectedSourceProvider(index)"
          :class="[ selectedSourceProviderIndex === index ? 'ring-2 ring-blue-600' : '',
          'p-2 cursor-pointer flex items-center justify-center bg-white border border-gray-300 rounded-md focus:outline-none hover:ring-2 hover:ring-blue-600 shadow-sm']">
                <GithubIcon v-if="index === 0" class="h-8 w-8" />
                <GitlabIcon v-else-if="index === 1" class="h-8 w-8" />
                <BitbucketIcon v-else-if="index === 2" class="h-8 w-8" />
          </div>
          </div>

        <InputError
          :message="form.errors.provider_id"
          class="mt-2"
        />

        </div>


        <div class="mt-6">
          <InputLabel
            for="repository"
            value="Repository"
          />

    <Combobox v-model="selected">
      <div class="relative mt-1">
        <div
          class="relative w-3/4 cursor-default overflow-hidden rounded-lg bg-white text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm"
        >
          <ComboboxInput
            class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm shadow-sm text-gray-900 focus:ring-0"
            :displayValue="(person) => person ? person.full_name : '' "
            @change="query = $event.target.value"
          />
          <ComboboxButton
            class="absolute inset-y-0 right-0 flex items-center pr-2"
          >
            <ChevronUpDownIcon
              class="h-5 w-5 text-gray-400"
              aria-hidden="true"
            />
          </ComboboxButton>
        </div>
        <TransitionRoot
          leave="transition ease-in duration-100"
          leaveFrom="opacity-100"
          leaveTo="opacity-0"
          @after-leave="query = ''"
        >
          <ComboboxOptions
            class="absolute mt-1 max-h-60 w-3/4 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
          >
            <div
              v-if="filteredPeople.length === 0 && query !== ''"
              class="relative cursor-default select-none py-2 px-4 text-gray-700"
            >
              Nothing found.
            </div>

            <ComboboxOption
              v-for="repo in repositories"
              as="template"
              :key="repo.id"
              :value="repo"
              v-slot="{ selected, active }"
            >
              <li
                class="relative cursor-default select-none py-2 pl-10 pr-4"
                :class="{
                  'bg-blue-600 text-white': active,
                  'text-gray-900': !active,
                }"
              >
                <span
                  class="block truncate"
                  :class="{ 'font-medium': selected, 'font-normal': !selected }"
                >
                  {{ repo.full_name }}
                </span>
                <span
                  v-if="selected"
                  class="absolute inset-y-0 left-0 flex items-center pl-3"
                  :class="{ 'text-white': active, 'text-blue-600': !active }"
                >
                  <CheckIcon class="h-5 w-5" aria-hidden="true" />
                </span>
              </li>
            </ComboboxOption>
          </ComboboxOptions>
        </TransitionRoot>
      </div>
    </Combobox>



        <InputError
          :message="form.errors.repository"
          class="mt-2"
        />


        </div>

        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal">
            Cancel
          </SecondaryButton>

          <PrimaryButton
            class="ml-3"
            :class="{ 'opacity-25': false}"
            :disabled="false"
            @click="createProject()"
          >
            Save Project
          </PrimaryButton>
        </div>
      </div>
    </Modal>
</template>
