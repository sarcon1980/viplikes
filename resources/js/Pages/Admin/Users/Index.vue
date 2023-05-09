<template>
    <admin-layout>
        <Head :title="title"/>
        <template v-slot:header>{{ title }}</template>

        <Link :href="route('admin.service.create')" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Добавить пользователя
        </Link>

        <table class="table table-striped " v-if="users.data.length">
            <thead>
            <tr>
                <th  class="">#</th>
                <th>Логин</th>
                <th>Email</th>
                <th>Статус</th>
                <th>Создан</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="(user, index) in users.data" :key="user.id">
                <td>{{ user.id}}</td>
                <td>{{ user.name}}</td>
                <td>{{ user.email}}</td>
                <td>{{ user.profile.last_name}}</td>
                <td>{{ user.profile.first_name}}</td>
                <td>{{ user.created_at}}</td>
            </tr>
            </tbody>
        </table>

        <div class="mt-3" v-if="users.data.length === 0">
            <h5 class="text-muted">Ничего не добавлено</h5>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head, Link} from '@inertiajs/vue3'

export default {
    props: {
        title: String,
        users: Object,
    },
    components: {
        AdminLayout,
        Link,
        Head,
    },
}
</script>

<style scoped>

</style>
