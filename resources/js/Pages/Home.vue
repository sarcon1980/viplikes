<template>
    <div>
        <admin-layout>
            <template v-slot:header>Управление сервисами</template>

        </admin-layout>
    </div>

</template>

<script>

import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    name: "Home",

    components: {
        AdminLayout
    },
}
</script>

<style scoped>

</style>
