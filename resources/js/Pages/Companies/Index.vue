<template>
    <Head title="Companies" />

    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden">
                    <div class="p-6 bg-gray-100 border-gray-200">
                        <!-- Table Title -->
                        <h1 class="text-gray-600 text-2xl">Companies</h1>
                        <div class="inline-flex items-center">
                            <!-- Search -->
                            <div class="mt-4 mb-6 px-1">
                                <SearchInput v-model="query"/>
                            </div>
                            <div class="place-items-end">
                                <form @submit.prevent="submit">
                                    <button v-if = "can.createCompany">Create Company</button>
                                </form>
                            </div>
                        </div>
                        <!-- Flash Updated Message -->
                        <div id="flashMessage" v-if="$page.props.flash.message" class="bg-green-100 rounded-lg py-1 px-6 mb-4 text-base text-green-700 mb-3" role="alert">{{ $page.props.flash.message }}</div>
                        <!-- Companies Results -->
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
                                            <span class="text-s leading-4 font-semibold text-gray-800 tracking-wider">Website</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                    <tr v-for="company in companies.data" :key="company.id" class="py-4 hover:bg-gray-100 focus-within:bg-gray-100">
                                        <td v-if="company.email!=$page.props.companies.email" class="whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <Link class="flex items-center px-6 py-4" :href="route('companies.edit', company.id)" tabindex="-1">
                                            {{ company.name }}
                                            </Link>
                                        </td>
                                        <td v-if="company.email!=$page.props.companies.email" class="whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <Link class="flex items-center px-6 py-4" :href="route('companies.edit', company.id)" tabindex="-1">
                                            {{ company.email }}
                                            </Link>
                                        </td>
                                        <td v-if="company.email!=$page.props.companies.email" class="whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <Link class="flex items-center px-6 py-4" :href="route('companies.edit', company.id)" tabindex="-1">
                                            {{ company.website }}
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="!companies.data.length">
                                        <p class="px-6 py-5 whitespace-no-wrap text-sm leading-5 text-gray-900">No results for the search criteria entered</p>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <Pagination v-if="companies.data.length" :links="companies.links" :from_link_number="companies.from" :to_link_number="companies.to" :total_links="companies.total"/>
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
    import { Head, Link, router } from '@inertiajs/vue3'
    import { reactive, onMounted } from 'vue'

    defineProps({
        companies: Object,
        query: Object,
        can: Object,
    })

    const form = reactive({
    })

    function submit() {
        router.get('/companies/create', form)
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