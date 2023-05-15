<template>
    <div>
        <admin-layout>

            <Head :title="title"/>
            <template v-slot:header>{{ title }}</template>

            <button class="btn btn-success btn-sm" @click="openModal">
                <i class="fa fa-plus"></i> Добавить
            </button>


            <div class="mt-2">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th class="text-capitalize">Role Name</th>
                        <th class="text-capitalize">Permissions</th>
                        <th class="text-capitalize">Created</th>
                        <th class="text-capitalize text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(role, index) in roles.data" :key="index">
                        <td>{{ role.name }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                                        <span v-for="(permission, index) in role.permissions"
                                                              :key="index">
                                                            {{ permission.name }}
                                                        </span>
                            </div>
                        </td>
                        <td>{{ role.created_at }}</td>
                        <td class="text-right">
                            <button class="btn btn-success btn-sm"
                                    @click="editModal(role)"><i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm ml-1"
                                    @click="deleteRole(role)"><i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix" v-if="roles.data.links">
                <pagination :links="roles.links"></pagination>
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
                                            <label for="role" class="">Role Name</label>
                                            <input type="text" class="form-control" id="role" placeholder="Role Name"
                                                   v-model="form.name" :class="{ 'is-invalid' : form.errors.name }"
                                                   autofocus="autofocus" autocomplete="off">
                                        </div>
                                        <div class="invalid-feedback mb-3" :class="{ 'd-block' : form.errors.name}">
                                            {{ form.errors.name }}
                                        </div>

                                        <div class="form-group">
                                            <label for="permissions" class="">Permissions</label>
                                            <multiselect
                                                v-model="form.permissions"
                                                :options="permissionOptions"
                                                :multiple="true"
                                                :taggable="true"
                                                placeholder="Choose permission(s)"
                                                @addPermissions="addPermissions"
                                                label="name"
                                                track-by="id"
                                            ></multiselect>
                                        </div>
                                        <div class="invalid-feedback" :class="{ 'd-block' : form.errors.permissions}">
                                            {{ form.errors.permissions }}
                                        </div>


                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger"
                                                @click="closeModal">Отмена
                                        </button>
                                        <button type="submit" class="btn btn-info"
                                                :disabled="!form.name || form.processing">{{ buttonTxt }}
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
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import Multiselect from '@suadelabs/vue3-multiselect'

export default {
    props: ['roles', 'permissions', 'title'],
    components: {
        AdminLayout,
        Pagination,
        Multiselect
    },
    data() {
        return {
            editedIndex: -1,
            editMode: false,
            form: this.$inertia.form({
                id: '',
                name: '',
                permissions: []
            }),
            permissionOptions: this.permissions,
        }
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Добавление роли' : 'Редактирование роли';
        },
        buttonTxt() {
            return this.editedIndex === -1 ? 'Добавить' : 'Изменить';
        },
        checkMode() {
            return this.editMode === false ? this.createRole : this.editRole;
        }
    },
    methods: {
        addPermissions(newPermission) {
            let permission = {
                name: newPermission,
            }
            this.permissionOptions.push(permission)
            this.form.permissions.push(permission)
        },
        editModal(role) {
            this.editMode = true
            $('#modal-lg').modal('show')
            this.editedIndex = this.roles.data.indexOf(role)
            this.form.name = role.name
            this.form.id = role.id
            this.form.permissions = role.permissions
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
        createRole() {
            this.form.post(this.route('admin.roles.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeModal()
                    Toast.fire({
                        icon: 'success',
                        title: 'New role created!'
                    })
                }
            })
        },
        editRole() {
            this.form.patch(this.route('admin.roles.update', this.form.id, this.form), {
                preserveScroll: true,
                onSuccess: () => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Role has been updated!'
                    })
                    this.closeModal()
                }
            })
        },
        deleteRole(role) {
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
                    this.form.delete(this.route('admin.roles.destroy', role), {
                        preserveScroll: true,
                        onSuccess: () => {
                            Swal.fire(
                                'Deleted!',
                                'Role has been deleted.',
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
