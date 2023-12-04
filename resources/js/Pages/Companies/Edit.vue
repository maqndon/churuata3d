<template>

    <Head title="Edit Company" />

    <BreezeAuthenticatedLayout>
        <div class="py-14">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden">
                    <div class="p-4 bg-gray-100 border-gray-200">
                        <!-- Page Title -->
                        <h1 class="text-gray-600 text-2xl">Edit Company</h1>
                    </div>
                </div>
                <!-- Flash Updated Message -->
                <div id="flashMessage" v-if="$page.props.flash.message"
                    class="bg-green-100 rounded-lg py-1 px-6 mb-4 text-base text-green-700 mb-3" role="alert">{{
                            $page.props.flash.message
                    }}</div>
                <!-- Form -->
                <div class="mt-5 md:col-span-2 md:mt-0 lg:px-4">
                    <!-- Company Name -->
                    <h2 class="text-gray-600 text-base pb-4">Company: {{ $page.props.data.company.name }}</h2>
                    <form @submit.prevent="submit">
                        <div class="overflow-hidden shadow sm:rounded-md">
                            <div class="bg-white px-4 py-5 sm:p-6">
                                <div class="grid grid-cols-6 gap-6">

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="name" class="block text-sm font-medium text-gray-700"
                                            @input="write">Name</label>
                                        <input type="text" name="name" id="name" autocomplete="name"
                                            v-model="form.name"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <div v-if="errors.name"
                                            class="my-1 bg-red-100 rounded-lg py-2 px-3 mb-4 text-base text-red-700 mb-3"
                                            role="alert">{{ errors.name }}</div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="last-website" class="block text-sm font-medium text-gray-700">Website</label>
                                        <input type="text" name="website" id="website" autocomplete="website-name"
                                            v-model="form.website"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <div v-if="errors.website"
                                            class="my-1 bg-red-100 rounded-lg py-2 px-3 mb-4 text-base text-red-700 mb-3"
                                            role="alert">{{ errors.website }}</div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="email-address" class="block text-sm font-medium text-gray-700">Email
                                            address</label>
                                        <input type="text" name="email" id="email" autocomplete="email"
                                            v-model="form.email"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <div v-if="errors.email"
                                            class="my-1 bg-red-100 rounded-lg py-2 px-3 mb-4 text-base text-red-700 mb-3"
                                            role="alert">{{ errors.email }}</div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                                        <input type="text" name="logo" id="logo" autocomplete="logo"
                                            v-model="form.logo"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <div v-if="errors.logo"
                                            class="my-1 bg-red-100 rounded-lg py-2 px-3 mb-4 text-base text-red-700 mb-3"
                                            role="alert">{{ errors.logo }}</div>
                                    </div>

                                    <div>
                                        <input type="hidden" name="company_id" id="company_id" autocomplete="company_id"
                                            v-model="form.company_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>

                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                <button type="submit" :disabled="form.processing"
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

const props = defineProps({
    data: Object,
    errors: Object,
})

const form = reactive({
    name: props.data.company.name,
    email: props.data.company.email,
    website: props.data.company.website,
    logo: props.data.company.logo,
})

function submit() {
    router.put('/companies/' + props.data.company.id, form)
}

//remove the flash message after 2 seconds
onUpdated(() => {
    if (document.getElementById('flashMessage')) {
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