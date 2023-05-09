<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <span class="brand-text font-weight-light"> <b>VipLinks</b> admin</span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <div class="nav-header">ПАНЕЛЬ</div>
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <!--                    <li class="nav-item menu-open">-->
                    <!--                        <a href="#" class="nav-link 1active">-->
                    <!--                            <i class="nav-icon fas fa-tachometer-alt"></i>-->
                    <!--                            <p>-->
                    <!--                                Starter Pages-->
                    <!--                                <i class="right fas fa-angle-left"></i>-->
                    <!--                            </p>-->
                    <!--                        </a>-->
                    <!--                        <ul class="nav nav-treeview">-->
                    <!--                            <li class="nav-item">-->
                    <!--                                <a href="#" class="nav-link active">-->
                    <!--                                    <i class="far fa-circle nav-icon"></i>-->
                    <!--                                    <p>Active Page</p>-->
                    <!--                                </a>-->
                    <!--                            </li>-->
                    <!--                            <li class="nav-item">-->
                    <!--                                <a href="#" class="nav-link">-->
                    <!--                                    <i class="far fa-circle nav-icon"></i>-->
                    <!--                                    <p>Inactive Page</p>-->
                    <!--                                </a>-->
                    <!--                            </li>-->
                    <!--                        </ul>-->
                    <!--                    </li>-->

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            <p>Тикеты(dev) </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('admin.service.index')" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Сервисы</p>
                        </Link>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cart-plus"></i>
                            <p>Заказы (dev)</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-list"></i>
                            <p>Подписки (dev)</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-divide"></i>
                            <p>Splitting (dev)</p>
                        </a>
                    </li>

                    <p class="nav-header border-bottom"></p>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Контент (dev)</p>
                        </a>

                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>FAQ (dev)</p>
                        </a>
                    </li>

                    <p class="nav-header border-bottom"></p>

                    <li class="nav-item">
                        <Link :href="route('admin.users.index')" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Пользователи</p>
                        </Link>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-key"></i>
                            <p>Права и роли</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

</template>
<script>
import {Link} from '@inertiajs/vue3'

export default {
    components: {
        Link
    },
    methods: {
        logout() {
            //this.$inertia.post(route('logout'));
        },
    }
}
</script>
