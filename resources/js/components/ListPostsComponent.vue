
<template>
    <div class="max">

        <div v-for="(post,index) in this.posts.data" v-bind:key="post.id">
            <hr v-show="index !== 0" class="mb-5 mt-5">
            <a :href="'/profile/'+post.user_id"><img class="rounded-circle" :src="'/storage/avatars/'+post.avatar" /></a>
            <a :href="'/profile/'+post.user_id"><h5 class="pt-2">{{post.name}}</h5></a>
            <div class="clearfix"></div>
            <p><small><i class="fa fa-pen fa-size-1"></i> Modified: {{post.updated_at}}</small></p>

            <span class="mr-1 float-left">
                <i class="fas fa-comment-alt"></i>
                {{ post.comments === null ? 0 : post.comments}}
            </span>

            <votes-component :post="post"></votes-component>

            <a :href="'/posts/' + post.id"><h2>{{post.title}}</h2></a>
            <p>{{sliceString(post.body)}}</p>
            <a :href="'/posts/' + post.id" class="readMore btn btn-bulma">Read more...</a>

        </div>
    </div>
</template>

<script>
    export default {
        name: "ListPostsComponent",
        props: ['posts'],
        methods: {
            sliceString(str){
                if(str.length > 30){
                    return str.slice(0,450) + '...';
                }else{
                    return str;
                }
            }
        }
    }
</script>

<style scoped>
    .fa-size-1 {
        font-size: 12px !important;
    }
    .rounded-circle {
        width: 40px !important;
        height: 40px !important;
        object-fit: cover !important;
        float: left;
        margin: 0 6px 6px 0;
    }
    .fa-comment-alt {
        font-size: 12px;
        color: silver;
    }
</style>
