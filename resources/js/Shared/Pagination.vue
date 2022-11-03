<template>
    <div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="link in links" :href="link.url" :key="link.label" v-text="link.label"
            v-html="link.label"
            class="px-1"
            :class="{'text-gray-500': !link.url,'font-bold': link.active}"
            preserve-scroll
        >

        </Component>
    </div>
</template>
<script>
    export default {
        props: {
            links: Array
        }
    }
</script>
