<template>
    <div class="container">
        <div class="row my-5">
            <div class="col-md-4 mx-auto">
                <div v-if="failed" class="text-white bg-danger p-2 rounded my-2">{{ failed }}</div>
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="text-center mt-2">
                            Login
                        </h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="auth" class="mt-5">
                            <div class="mb-3">
                                <label htmlFor="email" class="form-label">Email*</label>
                                <input
                                    type="text"
                                    v-model="form.email"
                                    class="form-control"
                                    placeholder="Email*">
                                <div v-if="form.errors.email" class="text-white bg-danger p-2 rounded my-2">
                                    {{ form.errors.email }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label htmlFor="password" class="form-label">Password*</label>
                                <input
                                    type="password"
                                    v-model="form.password"
                                    class="form-control"
                                    placeholder="Password*">
                                <div v-if="form.errors.password" class="text-white bg-danger p-2 rounded my-2">
                                    {{ form.errors.password }}
                                </div>
                            </div>
                            <div class="mb-3 text-center">
                                <button
                                    type="submit"
                                    class="btn btn-primary">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";

const form = useForm({
    email: '',
    password: '',
});

const props = defineProps({
    failed: {
        type: String,
        required: false
    }
});

const auth = () => {
    form.post(route('auth'));
}
</script>

<style>

</style>





