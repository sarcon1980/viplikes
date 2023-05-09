<template>
    <nav class="main-header navbar navbar-expand navbar-dark">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    {{ user.data.name }}
                    <i class="fa fa-caret-down"></i>
                </a>
                <div class="dropdown-menu dropdown-menu dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">Menu</span>
                    <div class="dropdown-divider"></div>
                    <Link class="dropdown-item" method="post" as="button" :href="route('logout')">
                        <i class="fa "></i> Выход
                    </Link>

                    <div class="dropdown-divider"></div>
<!--                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>-->
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>

    </nav>
</template>
<script>
    export default {}
</script>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const user = computed(() => usePage().props.user);

</script>
