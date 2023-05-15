<template>

    <td style="width: 2%">
        {{ service.id }}
    </td>

    <td class="text-left" style="width: 56.35%">
        <div class="">
            <i v-if="service.children && service.children.length"
               class="expandable-table-caret fas fa-caret-right fa-fw"></i>
            {{ service.name }}
        </div>
    </td>

    <td style="width: 9.7%" class="text-left" v-html="showStatus(service.is_active)"></td>

    <td style="width: 1%" class="text-left">{{ service.created_at }}</td>

    <td class="text-right ">
        <Link v-if="service.parent_id !== 0" :href="route('admin.service-items.index',{ service: service.id } )"
              data-toggle="popover" title="Управление продуктами" class="btn btn-info btn-sm mr-1">
            <i class="fa fa-pager"></i>
        </Link>
        <Link :href="route('admin.service.edit',service.id)" class="btn btn-info btn-sm"
              data-toggle="popover" title="Редактировать">
            <i class="fas fa-pencil-alt"></i>
        </Link>
    </td>
</template>

<script>

import {Link} from '@inertiajs/vue3';

export default {
    name: "ServiceRows",

    props: {
        service: Object,
    },

    components: {
        Link,
    },

    methods: {
        showStatus(value) {
            return value ? '<span class="badge  badge-success">on</span>' : '<span class="badge  badge-danger">off</span>'
        }
    }
}
</script>

<style scoped>

</style>
