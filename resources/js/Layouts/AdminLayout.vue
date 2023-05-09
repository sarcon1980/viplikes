<template>
    <div class="">
        <div class="wrapper" style="overflow: auto;">
            <NavBar/>
            <SideBar/>
            <div class="content-wrapper" style="height: 100%">
                <!--            <section class="content-header">-->
                <!--                <div class="container-fluid">-->
                <!--                    <div class="row mb-2">-->
                <!--                        <div class="col-sm-6">-->
                <!--                            <h1>Blank Page</h1>-->
                <!--                        </div>-->
                <!--                        <div class="col-sm-6">-->
                <!--                            <ol class="breadcrumb float-sm-right">-->
                <!--                                <li class="breadcrumb-item"><a href="#">Home</a></li>-->
                <!--                                <li class="breadcrumb-item active">Blank Page</li>-->
                <!--                            </ol>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </section>-->

                <section class="content mt-3">
                    <div v-if="$page.props.message" class="callout callout-success">
                        <h5>{{ $page.props.message }}</h5>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <slot name="header"></slot>
                            </h5>
                        </div>
                        <div class="card-body">
                            <slot></slot>
                        </div>
                    </div>
                </section>
            </div>
            <Footer/>
        </div>
    </div>
</template>

<script>
import Footer from '@/Components/Footer.vue';
import NavBar from '@/Components/NavBar.vue';
import SideBar from '@/Components/SideBar.vue';


export default {
    props: {

    },
    components: {
        Footer,
        NavBar,
        SideBar,
    },
}

</script>
