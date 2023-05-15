<template>
    <div>
        <admin-layout>
            <Head :title="title"/>
            <template v-slot:header>{{ title }}</template>

            <button class="btn btn-success btn-sm" @click="openModal">
                <i class="fa fa-plus"></i> Добавить
            </button>

            <div class="table-responsive p-0 mt-2">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th class="text-capitalize">Name</th>
<!--                        <th class="text-capitalize">Description</th>-->
                        <th class="text-capitalize">Created</th>
                        <th class="text-capitalize text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(permission, index) in permissions.data" :key="index">
                        <td>{{ permission.name }}</td>
<!--                        <td>{{ permission.description }}</td>-->
                        <td>{{ permission.created_at }}</td>
                        <td class="text-right">
                            <button class="btn btn-success btn-sm"
                                    @click="editModal(permission)"><i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm ml-1"
                                    @click="deletePermission(permission)"><i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix" v-if="permissions.data.links">
                <pagination :links="permissions.links"></pagination>
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

                                        <div class="form-group">
                                            <label for="name" class="">Permission Name</label>
                                            <input type="text" class="form-control" id="permission"
                                                   placeholder="Permission Name" v-model="form.name"
                                                   :class="{ 'is-invalid' : form.errors.name }" autofocus="autofocus"
                                                   autocomplete="off">
                                        </div>
                                        <div class="invalid-feedback mb-3" :class="{ 'd-block' : form.errors.name}">
                                            {{ form.errors.name }}
                                        </div>

<!--                                        <div class="form-group">-->
<!--                                            <label for="description" class="">Permission Description</label>-->
<!--                                            <input type="text" class="form-control" id="description"-->
<!--                                                   placeholder="Permission Description" v-model="form.description"-->
<!--                                                   :class="{ 'is-invalid' : form.errors.description }"-->
<!--                                                   autocomplete="off">-->
<!--                                        </div>-->
<!--                                        <div class="invalid-feedback mb-3"-->
<!--                                             :class="{ 'd-block' : form.errors.description}">-->
<!--                                            {{ form.errors.description }}-->
<!--                                        </div>-->



                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger btn-sm"
                                             @click="closeModal">Cancel
                                        </button>
                                        <button type="submit" class="btn btn-info btn-sm"
                                             :disabled="!form.name ||  form.processing">
                                            {{ buttonTxt }}
                                        </button>
                                    </div>
                                </form>

                        </div>
                    </div>
                </div>
            </div>

        </admin-layout>
    </div>
</template>

<script>

import Pagination from '@/Components/Pagination.vue';
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head, Link} from '@inertiajs/vue3';

export default {
    props: ['permissions', 'title'],
    components: {
        AdminLayout,
        Link,
        Pagination,
        Head,
    },
    data() {
        return {
            editedIndex: -1,
            editMode: false,
            form: this.$inertia.form({
                id: '',
                name: '',
                description: ''
            }),
        }
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Добавление' : 'Редактирование';
        },
        buttonTxt() {
            return this.editedIndex === -1 ? 'Добавление' : 'Редактирование';
        },
        checkMode() {
            return this.editMode === false ? this.createPermission : this.editPermission;
        }
    },
    methods: {
        editModal(permission) {
            this.editMode = true
            $('#modal-lg').modal('show')
            this.editedIndex = this.permissions.data.indexOf(permission)
            this.form.name = permission.name
            this.form.id = permission.id
            this.form.description = permission.description
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
        createPermission() {
            this.form.post(this.route('admin.permissions.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeModal()
                    Toast.fire({
                        icon: 'success',
                        title: 'New permission created!'
                    })
                }
            })
        },
        editPermission() {
            this.form.patch(this.route('admin.permissions.update', this.form.id, this.form), {
                preserveScroll: true,
                onSuccess: () => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Permission has been updated!'
                    })
                    this.closeModal()
                }
            })
        },
        deletePermission(permission) {
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
                    this.form.delete(this.route('admin.permissions.destroy', permission), {
                        preserveScroll: true,
                        onSuccess: () => {
                            Swal.fire(
                                'Deleted!',
                                'Permission has been deleted.',
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
