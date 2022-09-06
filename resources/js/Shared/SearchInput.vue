<template>
    <input
    v-model="search"
    type="text"
    placeholder="Search..."
    class="shadow-lg rounded-md border-inherit"
    />
</template>

<script setup>
    import { ref, watch } from "vue"
    import { Inertia } from "@inertiajs/inertia"
    import debounce from "lodash/debounce"

    const props = defineProps({
        modelValue: String,
    })
    
    let search = ref(props.modelValue)
    
    watch(search, debounce(function (value) {
        Inertia.get('users', { search: value }, {
            preserveState: true
        })
    }, 300)) 

</script>