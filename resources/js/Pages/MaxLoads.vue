<template>

    <Head>
        <title>Maximum Server Loads</title>
        <meta type="description" content="Maximum Server Loads page">
    </Head>

    <section class="flex justify-between mt-4">
        <h1 class="text-3xl">Maximum Server Loads</h1>
        <input v-model="selectedDate" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 datepicker-input" placeholder="Select date">
    </section>

    <section class="w-full overflow-x-auto mt-4">
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
                    <td>{{ item.time }}</td>
                    <td>{{ item.count }}</td>
                    <td>{{ item.status }}</td>
                </tr>
              </tbody>
        </table>
    </section>
</template>
<script>
    import Layout from '../Shared/Layout'
    export default {
        layout: Layout,
        data() {
            return {
                page: 1,
                perPage:25,
                selectedDate: this.date,
            }
        },
        props: {
            date:String,
            data: Object,
            limit: Number
        },
    }
</script>
