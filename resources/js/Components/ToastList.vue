<script setup>
import ToastListItem from '@/Components/ToastListItem.vue'
import { ref, onUnmounted, onMounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-vue3'

const props = defineProps({
                message: String
        })


const items = ref([])


const page = usePage()

let removeFinishEventListener = Inertia.on('finish', () => {
        if(page.props.value.toast) {

            items.value.unshift({
                         key: Symbol(),
                        message: page.props.value.toast
                })
        }
})

function remove(index) {
        items.value.splice(index, 1)
}


onUnmounted(() => removeFinishEventListener())
</script>

<template>
     <TransitionGroup tag="div" 
     enter-from-class="translate-x-full opacity-0" 
     enter-active-class="duration-500" 
     leave-active-class="duration-500" 
     leave-to-class="translate-x-full opacity-0" 
     class="fixed top-4 right-4 z-50 space-y-4 w-full max-w-xs">
        <ToastListItem v-for="(item) in items" :key="item.key" :message="item.message" @remove="remove(index)" />
     </TransitionGroup>

</template>
