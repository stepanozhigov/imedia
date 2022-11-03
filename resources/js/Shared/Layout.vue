<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <section class="p-6 bg-gray-200">
            <header class="flex justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="font-bold text-lg">Server Load</h1>
                </div>
                <Nav/>
            </header>
        </section>
        <section class="p-6">
            <div class="max-w-3xl mx-auto">
                <slot/>
            </div>
        </section>
    </div>
</template>
<script>
    import Nav from './Nav.vue';
    export default {
        components: {
            Nav
        },
    }
</script>
