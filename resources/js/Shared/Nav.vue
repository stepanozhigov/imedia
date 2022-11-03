<template>
    <nav>
        <ul class="flex space-x-4">
            <li>
                <NavLink
                    href="/"
                    :active="$page.component === 'Dashboard'"
                >
                    Dashboard
                </NavLink>
            </li>
            <li>
                <NavLink
                    href="/overloads"
                    :active="$page.component === 'Overloads'"
                >
                    Overloads
                </NavLink>
            </li>
            <li>
                <NavLink
                    href="/maxloads"
                    :active="$page.component === 'MaxLoads'"
                >
                    Max Loads
                </NavLink>
            </li>
        </ul>
    </nav>
</template>
<script>
    import NavLink from './NavLink.vue';
    export default {
        components: {NavLink}
    }
</script>
