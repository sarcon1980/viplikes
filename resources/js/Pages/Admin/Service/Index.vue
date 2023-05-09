<template>
    <admin-layout>
        <Head :title="title"/>
        <template v-slot:header>{{ title }}</template>

        <Link :href="route('admin.service.create')" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Добавить сервис
        </Link>

        <table class="table table-striped " v-if="services.length">
            <thead>
            <tr>
                <th style="width: 2%" class="">#</th>
                <th style="width: 60%">Сервис</th>
                <th style="width: 10%">Статус</th>
                <th style="width: 1%">Создан</th>
                <th></th>
            </tr>
            </thead>

            <tbody v-for="service in services" :key="service.id">

            <tr data-widget="expandable-table" aria-expanded="false">
                <service-rows :service="service"></service-rows>
            </tr>

            <tr v-if="service.children && service.children.length" class="expandable-body d-none">
                <td colspan="5" class="">
                    <div class="" style="display: none; margin-right: -12px">
                        <table>
                            <tr class="border-left border-info border-width-2" v-for="serviceChildren in service.children"
                                :key="serviceChildren.id">
                                <service-rows :service="serviceChildren"></service-rows>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="mt-3" v-if="services.length === 0">
            <h5 class="text-muted">Ничего не добавлено</h5>
        </div>
    </admin-layout>
</template>

<script>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head, Link} from '@inertiajs/vue3';
import ServiceRows from "../../../Components/ServiceRows.vue";

export default {

    props: {
        title: String,
        services: Object,
    },

    components: {
        AdminLayout,
        Link,
        Head,
        ServiceRows
    },

}
</script>
