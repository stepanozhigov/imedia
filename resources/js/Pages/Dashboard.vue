<template>
    <Head>
        <title>Home</title>
        <meta type="description" content="Dashboard page">
    </Head>

    <section class="flex justify-between mt-4">
        <h1 class="text-3xl">Dashboard</h1>
        <input v-model="selectedDate" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 datepicker-input" placeholder="Select date">
    </section>

    <section class="w-full overflow-hidden rounded-lg drop-shadow-md border border-gray-300 text-gray-900 mt-4">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap sm:text-sm">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                      <th class="px-4 py-3">Call Start</th>
                      <th class="px-4 py-3">Call End</th>
                      <th class="px-4 py-3">Duration</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr
                        v-for="(call,index) in calls" :key="index"
                    >
                        <td class="px-4 py-3">{{ call.start_date_time }}</td>
                        <td class="px-4 py-3">{{ call.end_date_time }}</td>
                        <td class="px-4 py-3">{{ call.duration_seconds }}</td>
                    </tr>
                  </tbody>
            </table>
            <pagination
                v-if="count>0"
                :date="selectedDate"
                :page="selectedPage"
                :count="count"
                :onPage="onPage"
                :route="'/'"
                :limit=0
            ></pagination>
        </div>
    </section>
</template>
<script>
    import Layout from '../Shared/Layout'
    import { Inertia } from '@inertiajs/inertia'
    import Pagination from '../Shared/Pagination'
    export default {
        layout: Layout,
        data() {
            return {
                selectedPage: this.page,
                selectedDate: this.date,
            }
        },
        computed: {
        },
        props: {
            date: String,
            calls: Object,
            count: Number,
            page: Number,
            onPage: Number,
        },
        watch: {
            selectedDate() {this.changeDate();}
        },
        methods: {
            changeDate() {
                this.$inertia.get('/',{ date: this.selectedDate, page: this.selectedPage, onPage: this.onPage}, {
                    preserveState: true
                });
            }
        },
        components: {
            Pagination
        }
    }
</script>
