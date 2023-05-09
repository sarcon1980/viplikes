import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';

//const appName = window.document.getElementsByTagName('title').innerText || 'Laravel';

window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

// import modulesJson from "../../modules_statuses.json";
// var modulesObject = Object.keys(modulesJson);
//
//
// () => {
//     for (let i = 0; i < modulesObject.length; i++) {
//         console.log(modulesObject[i])
//         return '../../Modules/' + modulesObject[i] + '/Resources/Pages/' + name
//     }
// },

createInertiaApp({
    title: (title) => `${title} - VipLinks admin`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
