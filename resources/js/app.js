require('./bootstrap');

window.Vue = require('vue');


// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


import AOS from 'aos';
import 'aos/dist/aos.css'; // You can also use <link> for styles
// ..
AOS.init();

Vue.component('posts-component', require('./components/PostsComponent.vue').default);
Vue.component('post-component', require('./components/PostComponent.vue').default);
Vue.component('create-post-component', require('./components/CreatePostComponent.vue').default);
Vue.component('comments-component', require('./components/CommentsComponent.vue').default);
Vue.component('edit-post-component', require('./components/EditPostComponent.vue').default);
Vue.component('profile-component', require('./components/ProfileComponent.vue').default);
Vue.component('list-posts-component', require('./components/ListPostsComponent.vue').default);
Vue.component('votes-component', require('./components/VotesComponent.vue').default);


Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '#app',
});



/// AUTO-RESIZE TEXTAREA
var tx = document.getElementsByTagName('textarea');
for (var i = 0; i < tx.length; i++) {
    tx[i].setAttribute('style', 'height:' + (tx[i].scrollHeight) + 'px;overflow-y:hidden;');
    tx[i].addEventListener("input", OnInput, false);
}
function OnInput() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
}
