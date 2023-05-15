<template>
    <admin-layout>
        <Head :title="title"/>
        <template v-slot:header>{{ title }}</template>

        <table class="table table-striped " v-if="orders.data.length">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Статус</th>
                <th>User</th>
                <th class="text-center">Тип</th>
                <th>Создан</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="(order, index) in orders.data" :key="index">
                <!--                 {{ order }}-->
                <td class="text-center">{{ order.id }}</td>
                <td class="text-center"> {{ order.status }}</td>
                <td>{{ order.user.name }}</td>
                <td class="text-center">{{ order.payment_type }}</td>
                <td>{{ order.created_at }}</td>

                <td class="text-right">
                    <button class="btn btn-info  btn-sm" @click="editModal(order)"><i
                        class="fa fa-eye"></i></button>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="card-footer clearfix" v-if="orders.links">
            <pagination :links="orders.links"></pagination>
        </div>

        <div class="mt-3" v-if="orders.data.length === 0">
            <h5 class="text-muted">Ничего не добавлено</h5>
        </div>


        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Просмотр заказа</h5>
                        <button type="button" class="close" @click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body overflow-hidden modal-lg" v-if="currentOrder">
                        <form @submit.prevent="this.editOrder">
                            <div class="row">
                                <!--                                    {{ this.currentOrder}}-->
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="inputParent">Статус заказа</label>
                                        <select v-model="form.status" id="inputParent"
                                                class="form-control custom-select"
                                                :class="{'is-invalid': form.errors.status}">
                                            <option v-for="status in statusList" v-bind:value="status">
                                                {{ status }}
                                            </option>
                                        </select>
                                        <div class="error invalid-feedback" v-if="form.errors.parent_id">
                                            {{ form.errors.parent_id }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-9">
                                    <b>История изменения статусов</b>
                                    <!--                                    {{ this.currentOrder.statuses}}-->
                                    <table class="table mt-1">
                                        <tr>
                                            <th>Cтатус</th>
                                            <th>Причина</th>
                                            <th>Создан</th>
                                        </tr>

                                        <tbody>
                                        <tr v-for="status in this.currentOrder.statuses" :key="status.id">
                                            <td>{{ status.name }}</td>
                                            <td>{{ status.reason }}</td>
                                            <td>{{ formatCreatedAt(status.created_at) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <!--                                {{ this.currentOrder}}-->
                                <div class="col-12">
                                    <b>Информация о заказе</b>
                                    <table class="table mt-1">
                                        <tr>
                                            <th>#</th>
                                            <th>Оплата</th>
                                            <th>Дата оплаты</th>
                                            <th>Тип заказа</th>
                                            <th v-if="this.currentOrder.payment_type == 'subscribe' ">Период оплаты</th>
                                            <th>Заказчик</th>
                                            <th>Создан</th>
                                        </tr>

                                        <tbody>
                                        <tr>
                                            <td>{{ this.currentOrder.id }}</td>
                                            <td>{{ this.currentOrder.payment_type }}</td>
                                            <td>{{ this.currentOrder.payed_at }}</td>
                                            <td>{{ this.currentOrder.payment_type }}</td>
                                            <td v-if="this.currentOrder.payment_type == 'subscribe' ">
                                                {{ this.currentOrder.payment_period }}
                                            </td>
                                            <td>{{ this.currentOrder.user.name }}</td>
                                            <td>{{ this.currentOrder.created_at }}</td>

                                        </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <b>Состав заказа</b>
                                    <table class="table table-striped mt-1">
                                        <tr>
                                            <th>#</th>
                                            <th>Сервис</th>
                                            <th>Продукт</th>
                                        </tr>
                                        <tbody>
                                        <tr v-for="item in this.currentOrder.items" :key="item.id">
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.service_item.service.name }}</td>
                                            <td>
                                                {{ item.service_item.count }}
                                                {{ item.service_item.service.options.title }}
                                                {{ item.service_item.type }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-danger" @click="closeModal">Cancel</button>
                                {{ form.change }}

                                <button type="submit" class="btn btn-info"
                                        :disabled="form.processing">Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head, Link} from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

export default {
    props: {
        title: String,
        orders: Object,
        statusList: Array

    },

    components: {
        AdminLayout,
        Link,
        Head,
        Pagination,
    },
    data() {
        return {
            editedIndex: -1,
            editMode: false,
            form: this.$inertia.form({
                id: '',
                status: null,
            }),
            currentOrder: null
        }
    },

    methods: {
        editModal(order) {
            $('#modal-lg').modal('show')
            this.form.id = order.id
            this.form.status = order.status
            this.currentOrder = order
        },

        formatCreatedAt(created_at) {
            return new Date(created_at).toLocaleString();
        },

        closeModal() {
            this.form.clearErrors()
            this.editMode = false
            this.form.reset()
            this.currentOrder = null
            $('#modal-lg').modal('hide')
        },

        editOrder() {
            this.form.patch(this.route('admin.orders.update', this.form.id, this.form), {
                preserveScroll: true,
                onSuccess: () => {
                    Toast.fire({
                        icon: 'success',
                        title: 'User has been updated!'
                    })
                    this.closeModal()
                }
            })
        }
    },


}
</script>

<style scoped>

</style>
