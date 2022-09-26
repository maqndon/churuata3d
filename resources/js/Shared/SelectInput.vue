<template>

    <label
        v-if="label"
        :for="label" 
        class="block text-sm font-medium text-gray-700">
        {{ label }}
    </label>
    <select :id="label"
            :name="label"
            v-model="selected"
            @click="$emit('update:modelValue', selected)"
            class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
        <slot/>
    </select>
</template>

<script setup>
    import { ref } from 'vue'

    let selected = ref(props.modelValue)
    
    const props = defineProps({
        modelValue: Number,
        label: String,
    })
    
    const emits = defineEmits(['update:modelValue'])

</script>