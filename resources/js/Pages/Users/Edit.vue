<template>
    <Head title="Edit User"/>

    <BreezeAuthenticatedLayout>
        <div class="py-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden">
                    <div class="p-4 bg-gray-100 border-gray-200">
                        <!-- Page Title -->
                        <h1 class="text-gray-600 text-2xl">Users</h1>
                    </div>
                </div>
                <div class="mt-5 md:col-span-2 md:mt-0 lg:px-4">
                    <form @submit.prevent="submit">
                        <div class="overflow-hidden shadow sm:rounded-md">
                            <div class="bg-white px-4 py-5 sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="first-name" class="block text-sm font-medium text-gray-700" @input="write">First name</label>
                                        <input type="text" name="first-name" id="first-name" autocomplete="given-name" v-model="form.first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                    
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                                        <input type="text" name="last-name" id="last-name" autocomplete="family-name" v-model="form.last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="user-name" class="block text-sm font-medium text-gray-700">Username</label>
                                        <input type="text" name="user-name" id="user-name" autocomplete="user" v-model="form.username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                                        <input type="text" name="email-address" id="email-address" autocomplete="email" v-model="form.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <SelectInput v-model="form.role" v-if="$page.props.auth.user.role_id===1" label="Role">
                                            <option v-for="role in data.roles" :value="role.id">{{ role.name }}</option>
                                        </SelectInput>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
  </template>

<script setup>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
    import { Head } from '@inertiajs/inertia-vue3'
    import { reactive } from 'vue'
    import SelectInput from '@/Shared/SelectInput.vue';
    import { Inertia } from "@inertiajs/inertia"

    defineProps({
        data: Object,
    })

    const form = reactive({
        first_name: Inertia.page.props.data.user.first_name,
        last_name: Inertia.page.props.data.user.last_name,
        username: Inertia.page.props.data.user.name,
        email: Inertia.page.props.data.user.email,
        role: Inertia.page.props.data.user.role_id,
    })
    
    function submit() {
        Inertia.put('/users/' + Inertia.page.props.data.user.id, form)
    }
</script>