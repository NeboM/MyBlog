<template>
    <div class="max container">
        <a :href="'/profile/'+this.post.user_id"><img class="rounded-circle" :src="'/storage/avatars/'+this.post.avatar" /></a>
        <h5 class="pt-1">{{post.name}}<br>{{post.email}}</h5>
        <div class="clearfix"></div>
        <p><small><i class="fa fa-pen fa-size-1"></i> Modified: {{post.updated_at}}</small></p>
        <div v-if="post.user_id === userID">
            <a :href="'/posts/' + post.id +'/edit'" class="custom-link pen"><i class="fas pen fa-pen"></i></a>
            <form :action="'/posts/' + post.id" method="POST" @submit="deleteData($event)" id="myForm">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" :value="csrf">
                <button type="submit" class="custom-link mr-2 trash"><i class="fas fa-trash-alt"></i></button>
            </form>
        </div>
        <!--Display the upvotes and votes-->
        <votes-component :post="post" :fromPost="true"></votes-component>
        <div class="clearfix"></div>
        <h3 class="mt-1">{{post.title}}</h3>
        <p>{{post.body}}</p>
        <div class="add-space"></div>
        <comments-component v-bind:postID="post.id" class="mt-4"></comments-component>
    </div>
</template>

<script>

    import bootbox from 'bootbox';
    export default {
        name: "PostCompnent",
        props: ['post'],
        data(){
            return {
                'csrf': '',
                'userID': ''
            }
        },
        methods: {
            deleteData(e){
                e.preventDefault();
                bootbox.confirm("Are you sure you want to delete this post?", function(imsure){
                    if(imsure){
                        document.getElementById("myForm").submit();
                    }
                });
            }
        },
        created() {
            this.csrf = window.Laravel.csrfToken;
            this.userID = window.Laravel.user.id;
        }
    }
</script>

<style scoped>
    .max {
        max-width: 900px !important;
    }
    .custom-link {
        float: left;
    }
    .rounded-circle {
        width: 50px !important;
        height: 50px !important;
        object-fit: cover !important;
        float: left;
        margin: 0 6px 6px 0;
    }
    .add-space {
        height: 20px !important;
    }
</style>
