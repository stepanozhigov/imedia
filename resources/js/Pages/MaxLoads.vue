<template>

    <Head>
        <title>Maximum Server Loads</title>
        <meta type="description" content="Maximum Server Loads page">
    </Head>

    <section class="flex justify-between mt-4">
        <h1 class="text-3xl">Maximum Server Loads</h1>
        <div class="flex space-x-4">
            <input :disabled="processing" v-model="selectedDate" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Select date">
            <input :disabled="processing" v-model="selectedOverloadRate" type="number" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 form-input">
        </div>
    </section>

    <section class="w-full overflow-hidden rounded-lg drop-shadow-md border border-gray-300 text-gray-900 mt-4">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                      <th class="px-4 py-3">Time</th>
                      <th class="px-4 py-3">Load</th>
                      <th class="px-4 py-3">Status</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr
                        v-for="(item,index) in data" :key="index"
                    >
                        <td class="px-4 py-3">{{ item.time }}</td>
                        <td class="px-4 py-3">{{ item.count }}</td>
                        <td class="px-4 py-3">{{ item.status }}</td>
                    </tr>
                  </tbody>
            </table>
            <pagination
                v-if="count>0"
                :date="selectedDate"
                :page="selectedPage"
                :count="count"
                :onPage="onPage"
                :route="'/maxloads'"
                :limit="limit"
            ></pagination>
        </div>
    </section>
</template>
<script>
    import { Inertia } from '@inertiajs/inertia'
    import Layout from '../Shared/Layout'
    import Pagination from '../Shared/Pagination'
    import lodash from 'lodash'
    export default {
        layout: Layout,
        data() {
            return {
                selectedPage: this.page,
                selectedDate: this.date,
                selectedOverloadRate: this.limit,
                processing:false
            }
        },
        props: {
            date:String,
            data: Object,
            limit: Number,
            count: Number,
            page: Number,
            onPage: Number
        },
        watch: {
            selectedPage() {
                this.updateTableData();
            },
            selectedDate() {
                this.updateTableData();
            },
            selectedOverloadRate() {
                this.delayedTableUpdate();
            }
        },
        methods: {
            updateTableData() {
                this.$inertia.get('/overloads',
                    {
                        date: this.selectedDate,
                        page: this.selectedPage,
                        onPage: this.onPage,
                        limit: this.selectedOverloadRate
                    },
                    {
                        preserveState: true,
                        preserveScroll:true,
                        onStart: () => {this.processing = true;},
                        onFinish: () => {this.processing = false;},
                    }
                );
            },
            delayedTableUpdate:
                lodash.debounce(function() {
                    this.updateTableData();
                },500)
        },
        components: {
            Pagination
        }
    }
</script>
