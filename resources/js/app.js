require('./bootstrap');

window.Vue=require('vue');

Vue.component('front-page',require('./components/exoDev.vue').default);

const app = new Vue({
    el: '#app',
});

