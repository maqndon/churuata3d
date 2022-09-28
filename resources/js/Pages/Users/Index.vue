<template>
    <Head title="Users" />

    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden">
                    <div class="p-6 bg-gray-100 border-gray-200">
                        <!-- Table Title -->
                        <h1 class="text-gray-600 text-2xl">Users</h1>
                        <div class="inline-flex items-center">
                            <!-- Search -->
                            <div class="mt-4 mb-6 px-1">
                                <SearchInput v-model="query"/>
                            </div>
                            <div class="place-items-end">
                                <form @submit.prevent="submit">
                                    <button>Create User</button>
                                </form>
                            </div>
                        </div>
                        <!-- Flash Updated Message -->
                        <div id="flashMessage" v-if="$page.props.flash.message" class="bg-green-100 rounded-lg py-1 px-6 mb-4 text-base text-green-700 mb-3" role="alert">{{ $page.props.flash.message }}</div>
                        <!-- Users Results -->
                        <div class="rounded-md shadow overflow-x-auto shadow-lg">
                            <table class="min-w-full divide-y border-b bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left">
                                            <span class="text-s leading-4 font-semibold text-gray-800 tracking-wider">First Name</span>
                                        </th>
                                        <th class="px-6 py-3 text-left">
                                            <span class="text-s leading-4 font-semibold text-gray-800 tracking-wider">Last Name</span>
                                        </th>
                                        <th class="px-6 py-3 text-left">
                                            <span class="text-s leading-4 font-semibold text-gray-800 tracking-wider">Username</span>
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
                                    <tr v-for="user in users.data" :key="user.id" class="py-4 hover:bg-gray-100 focus-within:bg-gray-100">
                                        <td v-if="user.email!=$page.props.auth.user.email" class="whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <Link class="flex items-center px-6 py-4" :href="route('users.edit', user.id)" tabindex="-1">
                                            {{ user.first_name }}
                                            </Link>
                                        </td>
                                        <td v-if="user.email!=$page.props.auth.user.email" class="whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <Link class="flex items-center px-6 py-4" :href="route('users.edit', user.id)" tabindex="-1">
                                            {{ user.last_name }}
                                            </Link>
                                        </td>
                                        <td v-if="user.email!=$page.props.auth.user.email" class="whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <Link class="flex items-center px-6 py-4" :href="route('users.edit', user.id)" tabindex="-1">
                                            {{ user.username }}
                                            </Link>
                                        </td>
                                        <td v-if="user.email!=$page.props.auth.user.email" class="whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <Link class="flex items-center px-6 py-4" :href="route('users.edit', user.id)" tabindex="-1">
                                            {{ user.email }}
                                            </Link>
                                        </td>
                                        <td v-if="user.email!=$page.props.auth.user.email" class="whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <Link class="flex items-center px-6 py-4" :href="route('users.edit', user.id)" tabindex="-1">
                                            {{ user.role }}
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="!users.data.length">
                                        <p class="px-6 py-5 whitespace-no-wrap text-sm leading-5 text-gray-900">No results for the search criteria entered</p>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <Pagination v-if="users.data.length" :links="users.links" :from_link_number="users.from" :to_link_number="users.to" :total_links="users.total"/>
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
    import { Head, Link } from '@inertiajs/inertia-vue3'
    import { Inertia } from "@inertiajs/inertia"
    import { reactive, onMounted } from 'vue'

    defineProps({
        users: Object,
        query: Object,
    })

    const form = reactive({
    })

    function submit() {
        Inertia.get('/users/create', form)
    }

    // remove the flash message after 2 seconds
    onMounted(() => {
        if(document.getElementById('flashMessage')){
            let flashMessage = document.getElementById('flashMessage')
            setTimeout(() => {
                flashMessage.remove()
            }, "2000")
        }
    })

</script>