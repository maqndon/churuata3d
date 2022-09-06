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
        modelValue: Object,
    })
    
    let search = ref(props.modelValue.filters)
    
    watch(search, debounce(function (value) {
        Inertia.get(props.modelValue.table, { search: value }, {
            preserveState: true,
            replace: true
        })
    }, 300)) 

</script>