<template>
    <Head title="Edit User"/>

    <BreezeAuthenticatedLayout>
        <div class="py-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden">
                    <div class="p-4 bg-gray-100 border-gray-200">
                        <!-- Page Title -->
                        <h1 class="text-gray-600 text-2xl">User</h1>
                    </div>
                </div>
                <!-- Flash Updated Message -->
                <div id="flashMessage" v-if="$page.props.flash.message" class="bg-green-100 rounded-lg py-1 px-6 mb-4 text-base text-green-700 mb-3" role="alert">{{ $page.props.flash.message }}</div>
                <!-- Form -->
                <div class="mt-5 md:col-span-2 md:mt-0 lg:px-4">
                    <form @submit.prevent="submit">
                        <div class="overflow-hidden shadow sm:rounded-md">
                            <div class="bg-white px-4 py-5 sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="first-name" class="block text-sm font-medium text-gray-700" @input="write">First name</label>
                                        <input type="text" name="first-name" id="first-name" autocomplete="given-name" v-model="form.first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <div v-if="errors.first_name" class="my-1 bg-red-100 rounded-lg py-2 px-3 mb-4 text-base text-red-700 mb-3" role="alert">{{ errors.first_name }}</div>
                                    </div>
                                    
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                                        <input type="text" name="last_name" id="last_name" autocomplete="family-name" v-model="form.last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <div v-if="errors.last_name" class="my-1 bg-red-100 rounded-lg py-2 px-3 mb-4 text-base text-red-700 mb-3" role="alert">{{ errors.last_name }}</div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                                        <input type="text" name="username" id="username" autocomplete="user" v-model="form.username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <div v-if="errors.username" class="my-1 bg-red-100 rounded-lg py-2 px-3 mb-4 text-base text-red-700 mb-3" role="alert">{{ errors.username }}</div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                                        <input type="text" name="email" id="email" autocomplete="email" v-model="form.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <div v-if="errors.email" class="my-1 bg-red-100 rounded-lg py-2 px-3 mb-4 text-base text-red-700 mb-3" role="alert">{{ errors.email }}</div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <SelectInput v-model="form.role" v-if="$page.props.auth.user.role_id===1" label="Role">
                                            <option v-for="role in data.roles" :value="role.id">{{ role.name }}</option>
                                        </SelectInput>
                                        <div v-if="errors.role" class="my-1 bg-red-100 rounded-lg py-2 px-3 mb-4 text-base text-red-700 mb-3" role="alert">{{ errors.role }}</div>
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
    import { Head, router } from '@inertiajs/vue3'
    import { reactive, onUpdated } from 'vue'
    import SelectInput from '@/Shared/SelectInput.vue';

    const props = defineProps({
        data: Object,
        errors: Object,
    })

    const form = reactive({
        first_name: props.data.user.first_name,
        last_name: props.data.user.last_name,
        username: props.data.user.username,
        email: props.data.user.email,
        role: props.data.user.role_id,
    })
    
    function submit() {
        router.put('/users/' + props.data.user.id, form)
    }

    //remove the flash message after 2 seconds
    onUpdated(() => {
        if(document.getElementById('flashMessage')){
            let flashMessage = document.getElementById('flashMessage')
            //remove the class hidden (display: none)
            flashMessage.classList.remove("hidden")
            setTimeout(() => {
                //add the class hidden (display: none)
                flashMessage.classList.add("hidden")
            }, "2000")
        }
    })
</script>