<template>
    <admin-layout>
        <Head :title="title"/>
        <template v-slot:header>
            <Link :href="route('admin.service.index')" title="Назад"><i class="fa fa-arrow-left"></i></Link>
            {{ title }}
        </template>

        <button type="button" class="btn btn-success btn-sm" @click="openModal">
            <i class="fa fa-plus"></i> Добавить пакет
        </button>

        <div v-if="service.options.types" class="mt-3">
            <Link :href="route('admin.service-items.index', { 'service': service.id , 'filter[type]':null })"
                  class="btn btn-outline-primary mr-1"
                  :class=" {active : this.route().params.hasOwnProperty('filter') == false }"
            >
                Все
            </Link>

            <span v-for="type in service.options.types" :key="type.id">
                <Link :href="route('admin.service-items.index', { 'service': service.id, 'filter[type]':type.text })"
                      class="btn btn-outline-primary mr-1"
                      :class=" {active : this.route().params.filter && this.route().params.filter.type ==  type.text }"
                >
                    {{ type.text }}
                </Link>
            </span>

        </div>

        <table class="table table-striped" v-if="items.length">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Статус</th>
                <th>Состав</th>
                <th>Тип</th>
                <th>Стоимость</th>
                <th>Скидка</th>
                <th>Стоимость продажи</th>
                <th>Создан</th>
                <th></th>
            </tr>
            </thead>
            <draggable v-model="draggableList" tag="tbody" drag-class="drag" item-key="position"
                       :component-data="getComponentData()" @end="onEnd(draggableList, $event,)">
                <template #item="{ element, index }">
                    <tr>
                        <td scope="row" class="text-center"><i class="fa fas fa-ellipsis-v  drag"></i>
                        </td>
                        <td class="text-center" v-html="showStatus(element.is_active)"></td>
                        <td>
                            <div v-for="t in element.package_items">
                                {{ t.name }}
                            </div>
                        </td>
                        <td>{{ element.type }}</td>
                        <td>{{ element.price }}</td>
                        <td>{{ element.price_for_sale }}</td>
                        <td>{{ element.discount }}</td>
                        <td>{{ element.created_at }}</td>
                        <td class="text-right">
                            <button class="btn btn-info" @click="editModal(element)">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </draggable>
        </table>

        <div class="mt-3" v-if="items.length === 0">
            <h5 class="text-muted">Ничего не добавлено</h5>
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
                                <div class="p-3">
                                <div class="row">
                                    <div class=" col-12">
                                        <div class="form-group">
                                            <label for="name" class="">Состав пакета</label>

                                            <!--                                                {{  JSON.stringify(options) }}-->
                                            <!--                                                    <hr>-->
                                            <!--                                                {{ JSON.stringify(optionsOrg)}}-->
                                            <div>
                                                <multiselect
                                                    v-model="form.package_items"
                                                    :options="options"
                                                    :multiple="true"
                                                    group-values="items"
                                                    group-label="name"
                                                    :group-select="false"
                                                    placeholder="Type to search"
                                                    track-by="id"
                                                    label="name"
                                                    :max="3"
                                                    :max-height="350"
                                                    :limit="3"
                                                    :class="{ 'is-invalid' : form.errors.package_items }"

                                                >
                                                </multiselect>
                                            </div>
                                            <div class="invalid-feedback mb-3"
                                                 :class="{ 'd-block' : form.errors.package_items}">
                                                {{ form.errors.package_items }}
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class=" col-4">
                                        <div v-if="service.options.types">
                                            <label for="type" class="">Вариант</label>
                                            <select v-model="form.type" class="form-control custom-select">
                                                <option v-for="type in service.options.types"
                                                        v-bind:value="type.text"
                                                        id="type"
                                                >
                                                    {{ type.text }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class=" col-4">
                                        <div class="form-group">
                                            <label for="name" class="">Стоимость</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Цена"
                                                       v-model="form.price"
                                                       :class="{ 'is-invalid' : form.errors.price }"
                                                       autofocus="autofocus" autocomplete="off"

                                                >
                                                <div class="input-group-append">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <div class="invalid-feedback mb-3"
                                                     :class="{ 'd-block' : form.errors.price}">
                                                    {{ form.errors.price }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="">Скидка</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Цена"
                                                       v-model="form.discount"
                                                       :class="{ 'is-invalid' : form.errors.discount }"
                                                       autofocus="autofocus" autocomplete="off"
                                                >
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                <div class="invalid-feedback mb-3"
                                                     :class="{ 'd-block' : form.errors.discount}">
                                                    {{ form.errors.discount }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input v-model="form.is_active" class="form-check-input" type="checkbox"
                                                   true-value="1"
                                                   false-value="0">
                                            <label class="form-check-label">Активен</label>
                                        </div>

                                    </div>
                                    <div class=" col-4">
                                        <div class="form-group">
                                            <label for="name" class="">Стоимость продажи</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Цена"
                                                       v-model="form.price_for_sale"
                                                       :class="{ 'is-invalid' : form.errors.price_for_sale }"
                                                       autofocus="autofocus" autocomplete="off"

                                                >
                                                <div class="input-group-append">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <div class="invalid-feedback mb-3"
                                                     :class="{ 'd-block' : form.errors.price_for_sale}">
                                                    {{ form.errors.price_for_sale }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger 1btn-sm"
                                            @click="closeModal">Отмена
                                    </button>
                                    <button type="submit" class="btn btn-info 1btn-sm"
                                            :disabled="form.processing">{{ buttonTxt }}
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
import {Head, Link, router} from '@inertiajs/vue3';
import draggable from "vuedraggable";
import {ref} from "vue";
import Multiselect from '@suadelabs/vue3-multiselect'


export default {
    display: "Table",
    order: 8,
    props: {
        title: String,
        service: Object,
        items: Object,
        errors: {},
        itemsForPackage: Object,

    },
    components: {
        AdminLayout,
        Link,
        Head,
        draggable,
        ref,
        Multiselect
    },

    setup(props, data) {
        const draggableList = ref(props.items);
        return {
            draggableList,
        };
    },

    data() {
        return {
            editedIndex: -1,
            editMode: false,
            form: this.$inertia.form({
                id: '',
                // name: [],
                is_active: 1,
                service_id: this.service.id,
                type: this.service.options ? this.service.options.types[0]['text'] : '',
                count: 1,
                price: 0,
                price_for_sale: 0,
                discount: 0,
                package_items: []
            }),
            inputValues: [],

            options: this.itemsForPackage,
            selected: null,
        }
    },

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Добавление пакета' : 'Редактирование пакета';
        },
        buttonTxt() {
            return this.editedIndex === -1 ? 'Добавить' : 'Редактировать';
        },
        checkMode() {
            return this.editMode === false ? this.createItem : this.editItem
        },
        titleItems() {
            return this.service.options.title
        },

    },

    methods: {

        showStatus(value) {
            return value ? '<span class="badge  badge-success">on</span>' : '<span class="badge  badge-danger">off</span>'
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

        editModal(item) {
            this.editMode = true
            $('#modal-lg').modal('show')
            this.editedIndex = 1
            this.form.id = item.id
            this.form.is_active = item.is_active
            this.form.type = item.type
            this.form.count = item.count
            this.form.price = item.price
            this.form.price_for_sale = item.price_for_sale
            this.form.discount = item.discount
            this.form.package_items = item.package_items
        },

        createItem() {
            this.form.post(this.route('admin.service-items.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeModal()
                    this.draggableList = ref(this.items);
                    Toast.fire({
                        icon: 'success',
                        title: 'Пакет успешно добавлен'
                    })
                }
            })
        },

        editItem() {

            this.form.patch(this.route('admin.service-items.update', this.form.id, this.form), {
                preserveScroll: true,
                onSuccess: () => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Пакет успешно обновлен'
                    })
                    this.draggableList = ref(this.items);
                    this.closeModal()
                }
            })
        },

        onEnd(list, $event) {

            if (this.handleChange() === true) {
                router.post(this.route('admin.service-items.position'), {data: list.map(i => i.id)}, {
                    preserveScroll: true,
                    onSuccess: () => {
                        Toast.fire({
                            icon: 'success',
                            title: 'Сортировка сохранена'
                        })
                    }
                })
            }
        },

        handleChange() {
            // console.log('changed');
            return true
        },

        inputChanged(value) {
            this.activeNames = value;
        },

        getComponentData() {
            return {
                onChange: this.handleChange,
                onInput: this.inputChanged,
                wrap: true,
                value: this.activeNames
            };
        }
    }
}
</script>


<style scoped>

.drag {
    cursor: grab;
}
</style>
