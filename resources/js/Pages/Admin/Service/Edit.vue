<template>
    <admin-layout>
        <Head :title="title"/>
        <template v-slot:header>
            <Link :href="route('admin.service.index')" class="1btn 1btn-link" title="Назад"><i class="fa fa-arrow-left"></i></Link> {{ title }}
        </template>

        <form @submit.prevent="update">
            <div class="row">
                <div class="col-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Основные данные</h3>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Название сервиса</label>
                                <input
                                    :class="{'is-invalid': form.errors.name}"
                                    v-model.trim="form.name"
                                    type="text"
                                    class="form-control"
                                    id="inputName"
                                >
                                <div class="error invalid-feedback" v-if="form.errors.name">{{ form.errors.name }}</div>
                            </div>

                            <div class="form-group">
                                <label for="inputType">Тип сервиса</label>
                                <select disabled v-model="form.type" id="inputType" class="form-control custom-select"
                                        :class="{'is-invalid': form.errors.type}">
                                    <option value="" selected></option>
                                    <option value="product">Продукт</option>
                                    <option value="package">Пакет</option>
                                </select>
                                <div class="error invalid-feedback" v-if="form.errors.type">{{ form.errors.type }}</div>
                            </div>

                            <div class="form-group">
                                <label for="inputParent">Cервис</label>
                                <select v-model="form.parent_id" id="inputParent" class="form-control custom-select"
                                        :class="{'is-invalid': form.errors.parent_id}">
                                    <option selected=""></option>
                                    <option v-for="service in servicesList" v-bind:value="service.id">
                                        {{ service.name }}
                                    </option>
                                </select>
                                <div class="error invalid-feedback" v-if="form.errors.parent_id">
                                    {{ form.errors.parent_id }}
                                </div>
                            </div>

                            <div class="form-check">
                                <input v-model="form.is_active" class="form-check-input" type="checkbox" true-value="1"
                                       false-value="0">
                                <label class="form-check-label">Активен</label>
                            </div>

                            <div v-if="form.type != null">
                                <div class="form-check">
                                    <input v-model="form.is_bestseller" class="form-check-input" type="checkbox"
                                           true-value=1 false-value=0>
                                    <label class="form-check-label">Бестселлер</label>
                                </div>
                                <div class="form-check">
                                    <input v-model="form.is_popular" class="form-check-input" type="checkbox"
                                           true-value="1"
                                           false-value="0">
                                    <label class="form-check-label">Популярное</label>
                                </div>
                                <div class="form-check">
                                    <input v-model="form.is_recommendation" class="form-check-input" type="checkbox"
                                           true-value="1" false-value="0">
                                    <label class="form-check-label"> В рекомендации</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3" v-if="form.type == 'package' || form.type == 'product'">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Настройки</h3>
                        </div>

                        <!--Item name-->
                        <div class="card-body">

                            <div class="form-group">
                                <label for="inputName">Url</label>
                                <input
                                    :class="{'is-invalid': form.errors.url}"
                                    v-model.trim="form.url"
                                    type="text"
                                    class="form-control"
                                    id="inputName"
                                    placeholder='/facebook-premium-likes'
                                >
                                <div class="error invalid-feedback" v-if="form.errors.url">
                                    {{ form.errors.url }}
                                </div>
                            </div>


                            <div class="form-group" v-if="form.type == 'product' ">
                                <label for="inputName">Название единицы товара</label>
                                <input
                                    :class="{'is-invalid': form.errors.optionsTitleProduct}"
                                    v-model.trim="form.optionsTitleProduct"
                                    type="text"
                                    class="form-control"
                                    id="inputName"
                                    placeholder='Like, Followers'
                                >
                                <div class="error invalid-feedback" v-if="form.errors.optionsTitleProduct">
                                    {{ form.errors.optionsTitleProduct }}
                                </div>
                            </div>
                        </div>

                        <!--Accounts-->
                        <div class="card-body">
                            <div class="form-group" v-if="form.type !== null ">
                                <label for="inputName">Виды аккаунтов</label>
                                <div class="input-group input-group-sm">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder='@username, E-mail'
                                        v-model="newOptionAccount"
                                    >
                                    <span class="input-group-append">
                                        <button
                                            @click.prevent="addOptionAccount()"
                                            type="button"
                                            class="btn btn-info btn-flat">+</button>
                                    </span>
                                </div>

                                <ul class="list-unstyled mt-2">
                                    <li class="m-1"
                                        v-for="(optionAccount, index) in optionAccountRef"
                                        :key="index"
                                    >
                                        <div class="input-group input-group-sm">
                                            <input
                                                v-model="optionAccount.text"
                                                type="text"
                                                class="form-control"
                                                :class="{'border-red-500': errors[`optionsAccount.${index}.text`]}"
                                            >
                                            <span class="input-group-append">
                                                <button @click="removeOptionAccount(index)" type="button"
                                                        class="btn btn-danger btn-flat">-</button>
                                            </span>
                                        </div>
                                        <span class="text-red-500 mb-2" v-if="errors[`optionsAccount.${index}.text`]"> {{
                                                errors[`optionsAccount.${index}.text`]
                                            }}</span>
                                    </li>
                                </ul>

                                <h5 class="text-muted" v-if="optionAccountRef.length === 0">Ничего не добавлено</h5>
                                <span class="text-red mb-2" v-if="errors[`optionsAccount`]"> {{
                                        errors[`optionsAccount`]
                                    }}</span>
                            </div>
                        </div>

                        <!--Variation  Items                       -->

                        <div class="card-body">
                            <div class="form-group" v-if="form.type !== null ">
                                <label for="inputName">Виды</label>
                                <!--For product-->
                                <div v-if="form.type == 'product'" class="input-group input-group-sm">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder='Buy Real Link , Day'
                                        v-model="newOptionVar"
                                    >
                                    <span class="input-group-append">
                                        <button
                                            @click.prevent="addOptionVar()"
                                            type="button"
                                            class="btn btn-info btn-flat">+</button>
                                    </span>
                                </div>
                                <!--For package-->
                                <div v-if="form.type == 'package'" class="input-group input-group-sm">

                                    <select v-model="newOptionVar" class="form-control custom-select">
                                        <option selected=""></option>
                                        <option v-for="period in servicePeriods" v-bind:value="period.alias">
                                            {{ period.name }}
                                        </option>
                                    </select>
                                    <span class="input-group-append">
                                        <button
                                            @click.prevent="addOptionVar()"
                                            type="button"
                                            class="btn btn-info btn-flat">+</button>
                                    </span>
                                </div>

                                <ul class="list-unstyled mt-2">
                                    <li class="m-1"
                                        v-for="(optionVar, index) in optionVarRef"
                                        :key="index"
                                    >
                                        <div class="input-group input-group-sm">
                                            <input
                                                v-model="optionVar.text"
                                                type="text"
                                                class="form-control"
                                                :class="{'border-red-500': errors[`optionsVar.${index}.text`]}"
                                            >
                                            <span class="input-group-append">
                                                <button @click="removeOptionVar(index)" type="button"
                                                        class="btn btn-danger btn-flat">-</button>
                                            </span>
                                        </div>
                                        <span class="text-red-500 mb-2" v-if="errors[`optionsVar.${index}.text`]"> {{
                                                errors[`optionsVar.${index}.text`]
                                            }}</span>
                                    </li>
                                </ul>

                                <h5 class="text-muted" v-if="optionVarRef.length === 0">Ничего не добавлено</h5>
                                <span class="text-red mb-2" v-if="errors[`optionsVar`]"> {{
                                        errors[`optionsVar`]
                                    }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="">
                <button
                    :disabled="form.processing"
                    type="submit"
                    class="btn btn-success">
                    Обновить
                </button>
            </div>
        </form>
    </admin-layout>
</template>

<script>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head, Link, useForm,} from '@inertiajs/vue3';
import {ref, toRef} from "vue";

export default {

    props: {
        title: String,
        errors: {},
        servicesList: Object,
        servicePeriods: Object,
        service: Object
    },

    components: {
        AdminLayout,
        Link,
        Head,
        useForm,
    },
    setup(props, errors) {

        const newOptionTitle = ref('');
        const optionTitleData = props.service.options ? props.service.options.title : [];
        const optionTitleRef = ref(optionTitleData);

        const newOptionAccount = ref('');
        //const optionAccountData = JSON.parse(props.service.options.accounts);
        const optionAccountData = props.service.options ?props.service.options.accounts : [];
        const optionAccountRef = ref(optionAccountData);

        const newOptionVar = ref('');
        const optionVarData = (props.service.options) ? props.service.options.types : [];
        const optionVarRef = ref(optionVarData);

        function addOptionTitle() {
            console.log(optionTitleData.length < 1)
            console.log(optionTitleRef)

            if (newOptionTitle.value) {
                optionTitleRef.value.push({
                    text: newOptionTitle.value
                });

                this.form.optionsTitle.push({
                    text: newOptionTitle.value
                });
                newOptionTitle.value = '';
            }
        }

        function addOptionAccount() {
            console.log(optionAccountData.length < 1)
            console.log(optionAccountRef)

            if (newOptionAccount.value) {
                optionAccountRef.value.push({
                    text: newOptionAccount.value
                });

                this.form.optionsAccount.push({
                    text: newOptionAccount.value
                });
                newOptionAccount.value = '';
            }
        }

        function addOptionVar() {

            if (newOptionVar.value) {
                optionVarRef.value.push({
                    text: newOptionVar.value
                });

                this.form.optionsVar.push({
                    text: newOptionVar.value
                });
                newOptionVar.value = '';
            }
        }

        function removeOptionTitle(index) {
            optionTitleRef.value.splice(index, 1);
            this.form.optionsTitle.splice(index, 1);
        }

        function removeOptionAccount(index) {
            optionAccountRef.value.splice(index, 1);
            this.form.optionsAccount.splice(index, 1);
        }

        function removeOptionVar(index) {
            optionVarRef.value.splice(index, 1);
            this.form.optionsVar.splice(index, 1);
        }

        const form = useForm({
            name: props.service.name,
            type: props.service.type,
            parent_id: props.service.parent_id,
            is_active: props.service.is_active,
            is_bestseller: props.service.is_bestseller,
            is_popular: props.service.is_popular,
            is_recommendation: props.service.is_recommendation,
            url: props.service.url,

            optionsTitle: optionTitleRef,
            optionsTitleProduct:props.service.options ? props.service.options.title : '',
            optionsAccount: optionAccountRef,
            optionsVar: optionVarRef
        })

        console.log(props.service)

        function update() {
            //console.log(form)
            form.put(route('admin.service.update', props.service.id))
        }

        return {
            optionTitleRef,
            newOptionTitle,
            addOptionTitle,
            removeOptionTitle,

            optionAccountRef,
            newOptionAccount,
            addOptionAccount,
            removeOptionAccount,

            optionVarRef,
            newOptionVar,
            addOptionVar,
            removeOptionVar,

            form,
            update,
        }
    }
}
</script>
