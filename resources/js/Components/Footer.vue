<template>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022-{{ currentDate.getFullYear() }} VipLikes <inertia-link href="#"></inertia-link>.</strong>
            <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0-beta
        </div>
    </footer>
</template>

<script>
    export default {
        data() {
            return {
                currentDate: new Date,
            }
        }
    }
</script>
