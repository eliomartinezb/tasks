<script setup>
const props = defineProps({
    showModal: {
        type: Boolean,
        default: false,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue', 'close'])

const confirmCancel = (mode) => {
    if (mode === 'cancel') {
        value.value = false
    }
    emit(mode)
}

const cancel = () => confirmCancel('close')
</script>

<template>
    <div
        class="flex z-50 items-center flex-col justify-center overflow-hidden fixed inset-0"
        v-show="showModal"
    >
        <transition
            enter-active-class="transition duration-150 ease-in"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                class="overlay absolute inset-0 bg-gradient-to-tr opacity-90 dark:from-gray-700 dark:via-gray-900 dark:to-gray-700"
                @click="cancel"
            />
        </transition>
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="animate-fade-out"
        >
            <div class="p-10 z-10 w-1/3 bg-white rounded">
                <slot/>
            </div>
        </transition>
    </div>
</template>
