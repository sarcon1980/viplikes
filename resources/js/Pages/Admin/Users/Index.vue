<template>
    <admin-layout>
        <Head :title="title"/>
        <template v-slot:header>{{ title }}</template>

        <button class="btn btn-success btn-sm" @click="openModal">
            <i class="fa fa-plus"></i> Добавить
        </button>

        <div class="mt-2">
            <table class="table table-striped " v-if="users.data.length">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Статус</th>
                    <th class="text-center">Верификация</th>
                    <th>Логин</th>
                    <th>Email</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Роли</th>
                    <th>Создан</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="(u, index) in users.data" :key="index">
                    <td class="text-center">{{ u.id }}</td>
                    <td class="text-center" v-html="showStatus(!u.deleted_at)"></td>
                    <td class="text-center" v-html="showStatusVerified(u.email_verified_at)"></td>
                    <td>{{ u.name }}</td>
                    <td>{{ u.email }}</td>
                    <td>{{ u.profile.first_name }}</td>
                    <td>{{ u.profile.last_name }}</td>
                    <td>
                        <span v-for="role in u.roles" :key="index">
                            {{ role.name }}&nbsp;
                        </span>
                    </td>
                    <td>{{ u.created_at }}</td>

                    <td class="text-right">
                        <span v-if="!u.deleted_at">
                        <button class="btn btn-success  btn-sm" @click="editModal(u)"><i
                            class="fa fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm ml-1" @click="deleteUser(u)"><i
                            class="fa fa-minus"></i></button>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="card-footer clearfix" v-if="users.data.links">
                <pagination :links="users.data.links"></pagination>
            </div>
        </div>

        <div class="mt-3" v-if="users.data.length === 0">
            <h5 class="text-muted">Ничего не найдено</h5>
        </div>

        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ formTitle }}</h4>
                        <button type="button" class="close" @click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body overflow-hidden">
                        <form @submit.prevent="checkMode">
                            <!--                            {{ form}}-->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="name" class="">Логин</label>
                                            <input type="text" class="form-control" placeholder="Name"
                                                   v-model="form.name" :class="{ 'is-invalid' : form.errors.name }"
                                                   autofocus="autofocus" autocomplete="new-name">
                                        </div>
                                        <div class="invalid-feedback mb-3" :class="{ 'd-block' : form.errors.name}">
                                            {{ form.errors.name }}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="emailForm" class="">E-mail</label>
                                            <input type="email" id="emailForm" class="form-control"
                                                   placeholder="E-mail Address"
                                                   v-model="form.email" :class="{ 'is-invalid' : form.errors.email }"
                                                   autocomplete="new-email">
                                        </div>
                                        <div class="invalid-feedback mb-3" :class="{ 'd-block' : form.errors.email}">
                                            {{ form.errors.email }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="first_name" class="">Имя</label>
                                            <input type="text" class="form-control" placeholder=""
                                                   v-model="form.first_name"
                                                   :class="{ 'is-invalid' : form.errors.first_name }"
                                                   autofocus="autofocus" autocomplete="new-first-name">
                                        </div>
                                        <div class="invalid-feedback mb-3"
                                             :class="{ 'd-block' : form.errors.first_name}">
                                            {{ form.errors.first_name }}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="lastForm" class="">Фамилия</label>
                                            <input type="text" id="lastForm" class="form-control" placeholder=""
                                                   v-model="form.last_name"
                                                   :class="{ 'is-invalid' : form.errors.last_name }"
                                                   autocomplete="new-email">
                                        </div>
                                        <div class="invalid-feedback mb-3"
                                             :class="{ 'd-block' : form.errors.last_name}">
                                            {{ form.errors.last_name }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="passwordForm" class="">Пароль</label>
                                            <input type="password" id="passwordForm" class="form-control" placeholder=""
                                                   v-model="form.password"
                                                   :class="{ 'is-invalid' : form.errors.password }"
                                                   autocomplete="new-password">
                                        </div>
                                        <div class="invalid-feedback mb-3" :class="{ 'd-block' : form.errors.password}">
                                            {{ form.errors.password }}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="confirm-password" class="">Подтверждение пароля</label>
                                            <input type="password" class="form-control" placeholder=""
                                                   v-model="form.confirmPassword"
                                                   :class="{ 'is-invalid' : form.errors.confirmPassword }"
                                                   autocomplete="new-password-confirm">
                                        </div>
                                        <div class="invalid-feedback mb-3"
                                             :class="{ 'd-block' : form.errors.confirmPassword}">
                                            {{ form.errors.confirmPassword }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" >
                                    <label for="roles" class="">Роли</label>
                                    <multiselect
                                        v-model="form.roles"
                                        :options="roleOptions"
                                        :multiple="true"
                                        :taggable="true"
                                        placeholder="Choose new role"
                                        @tag="addTag"
                                        label="name"
                                        track-by="id"
                                    ></multiselect>
                                </div>
                                <div class="invalid-feedback" :class="{ 'd-block' : form.errors.roles}">
                                    {{ form.errors.roles }}
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-danger" @click="closeModal">Cancel</button>
                                <button type="submit" class="btn btn-info"
                                        :disabled="!form.name || !form.email || form.processing">{{ buttonTxt }}
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
import Multiselect from '@suadelabs/vue3-multiselect'

export default {
    props: {
        title: String,
        users: Object,
        roles: Object
    },
    components: {
        AdminLayout,
        Link,
        Pagination,
        Head,
        Multiselect
    },

    data() {
        return {
            editedIndex: -1,
            editMode: false,
            form: this.$inertia.form({
                id: '',
                name: null,
                email: null,
                confirmPassword: null,
                password: null,
                first_name: null,
                last_name: null,
                roles: []
            }),
            roleOptions: this.roles,
        }
    },

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Добавление пользователя' : 'Редактирование пользователя';
        },
        buttonTxt() {
            return this.editedIndex === -1 ? 'Сохранить' : 'Редактировать';
        },
        checkMode() {
            return this.editMode === false ? this.createUser : this.editUser
        }
    },
    methods: {
        showStatus(value) {
            return value ? '<span class="badge  badge-success">active</span>' : '<span class="badge  badge-danger">deleted</span>'
        },
        showStatusVerified(value) {
            return value ? '<span class="badge  badge-success">+</span>' : '<span class="badge  badge-danger">-</span>'
        },

        addTag(newRole) {
            let tag = {
                name: newRole,
            }

            this.roleOptions.push(tag)
            this.form.roles.push(tag)
        },
        editModal(user) {
            this.editMode = true
            $('#modal-lg').modal('show')
            this.editedIndex = this.users.data.indexOf(user)
            this.form.name = user.name
            this.form.email = user.email
            this.form.id = user.id
            this.form.first_name = user.profile.first_name
            this.form.last_name = user.profile.last_name
            this.form.roles = user.roles
        },
        openModal() {
            this.editedIndex = -1
            $('#modal-lg').modal('show')
        },
        closeModal() {
            this.form.clearErrors()
            this.editMode = false
            this.form.reset()
            $('#modal-lg').modal('hide')
        },
        createUser() {
            this.form.post(this.route('admin.users.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeModal()
                    Toast.fire({
                        icon: 'success',
                        title: 'New user created!'
                    })
                }
            })
        },
        editUser() {
            this.form.patch(this.route('admin.users.update', this.form.id, this.form), {
                preserveScroll: true,
                onSuccess: () => {
                    Toast.fire({
                        icon: 'success',
                        title: 'User has been updated!'
                    })
                    this.closeModal()
                }
            })
        },
        deleteUser(user) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.form.delete(this.route('admin.users.destroy', user), {
                        preserveScroll: true,
                        onSuccess: () => {
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            )
                        }
                    })
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
