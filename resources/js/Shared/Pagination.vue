<template>
    <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
        <span class="flex items-center col-span-3">
          {{showing}}
        </span>
        <span class="col-span-2"></span>
        <!-- Pagination -->
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
          <nav aria-label="Table navigation">
            <ul class="inline-flex items-center">
                <!-- to first page -->
              <li>
                <button @click="toFirstPage" class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple" aria-label="Previous">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                    </svg>
                </button>
              </li>
              <!-- previous page -->
              <li>
                <button @click="pageDown" class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple" aria-label="Previous">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
              </li>
              <!-- Pages Info -->
              <li>
                <span class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                    {{pageInfo}}
                </span>
              </li>
              <!-- next page -->
              <li>
                <button @click="pageUp" class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple" aria-label="Next">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
              </li>
              <!-- last page -->
              <li>
                <button @click="toLastPage" class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple" aria-label="Next">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
              </li>
            </ul>
          </nav>
        </span>
    </div>
</template>
<script>
    import { Inertia } from '@inertiajs/inertia'
    export default {
        props: {
            date: String,
            count: Number,
            page: Number,
            onPage: Number
        },
        data() {
            return {
                selectedPage: this.page,
            }
        },
        computed: {
            pagesTotal() {
                return Math.ceil(this.count/this.onPage);
            },
            callsRange() {
                if(this.selectedPage < this.pagesTotal) {
                    return (this.onPage*(this.selectedPage-1)+1)
                    +'-'
                    +(this.onPage*(this.selectedPage-1)+this.onPage);
                } else {
                    return (this.onPage*(this.selectedPage-1)+1)+'-'+this.count;
                }
            },
            showing() {
                return 'Showing '+this.callsRange+' Of '+this.count;
            },
            pageInfo() {
                return this.selectedPage+' Of '+this.pagesTotal;
            }
        },
        methods: {
            pageUp() {
                if(this.selectedPage >= this.pagesTotal) this.selectedPage=1;
                else this.selectedPage=this.selectedPage+1;

                this.getCalls();
            },
            pageDown() {
                if(this.selectedPage == 1) this.selectedPage=this.pagesTotal;
                else this.selectedPage=this.selectedPage-1;

                this.getCalls();
            },
            toFirstPage() {
                this.selectedPage=1;
                this.getCalls();
            },
            toLastPage() {
                this.selectedPage=this.pagesTotal;
                this.getCalls();
            },
            getCalls() {
                Inertia.get('/',{ date: this.date, page: this.selectedPage, onPage: this.onPage}, {
                    preserveState: true, preserveScroll: true
                });
            }
        }
    }
</script>
