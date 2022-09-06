<template>
    <Head title="Users" />

    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden">
                    <div class="p-6 bg-gray-100 border-gray-200">
                        <!-- Table Title -->
                        <h1 class="text-gray-600 text-2xl">Users</h1>
                        <!-- Search -->
                        <div class="mt-4 mb-6">
                            <SearchInput v-model="actualQuery"/>
                        </div>
                        <div class="rounded-md shadow overflow-x-auto shadow-lg">
                            <table class="min-w-full divide-y border-b bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left">
                                            <span class="text-s leading-4 font-semibold text-gray-800 tracking-wider">Name</span>
                                        </th>
                                        <th class="px-6 py-3 text-left">
                                            <span class="text-s leading-4 font-semibold text-gray-800 tracking-wider">Email</span>
                                        </th>
                                        <th class="px-6 py-3 text-left">
                                            <span class="text-s leading-4 font-semibold text-gray-800 tracking-wider">Role</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ user.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ user.email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ user.role }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <Pagination :links="users.links" :from_link_number="users.from" :to_link_number="users.to" :total_links="users.total"/>
                        <!-- end pagination -->
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import SearchInput from '@/Shared/SearchInput.vue'
import Pagination from '@/Shared/Pagination.vue'
import { Head } from '@inertiajs/inertia-vue3'
import {Inertia} from "@inertiajs/inertia"

defineProps({
    users: Object,
    actualQuery: String,
})
</script>