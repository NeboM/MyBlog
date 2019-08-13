<template>
    <div class="container">

        <div class="text-center">
            <h3 class="label label-default rank-label">{{this.user.name}}</h3>
            <img class="rounded-circle" :src="'/storage/avatars/'+this.user.avatar" />
            <br><span class="text-secondary">{{this.user.email}}</span>
            <p class="mt-3"><a href="#" @click="showImageUpload = true" v-if="showImageUpload === false && checkIfSelf(this.user.id)">Change image</a></p>
        </div>


        <div class="row justify-content-center" v-if="showImageUpload">
            <form action="/profile" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" :value="csrf">
                <div class="form-group">
                    <input type="file" class="form-control-file"  name="avatar" id="avatarFile" aria-describedby="fileHelp">
                    <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                </div>
                <button type="submit" class="btn btn-submit">Submit</button>
                <button class="btn btn-cancel" @click="showImageUpload = false">CANCEL</button>
            </form>
        </div>


        <h5 class="mb-5 text-secondary text-center">{{this.user.name}} posts</h5>

        <list-posts-component v-bind:posts="posts"></list-posts-component>


        <div class="card-footer max"  v-if="loaded === true && this.posts.data.length >= 6">
            <a href="#" class="custom"><i class="fas fa-angle-double-up float-right mt-3 mr-3"></i></a>
            <pagination :data="posts" class="mt-3" @pagination-change-page="getResults"></pagination>
        </div>





    </div>
</template>

<script>
    export default {
        name: "ProfileComponent",
        props: ['user'],
        data() {
            return {
                'posts': {},
                'csrf': '',
                'userID': '',
                'showImageUpload': false,
                'loaded': false
            }
        },
        mounted() {
            this.getResults();
        },
        created(){
            this.csrf = window.Laravel.csrfToken;
            this.userID = window.Laravel.user.id;
        },
        methods: {
            getResults(page = 1){
                axios.get('/get/posts/'+this.user.id+'?page=' + page)
                    .then(res=>{
                        this.posts = res.data;
                        this.loaded = true;
                    })
                    .catch(err=>{
                        console.error(err);
                    });

            },
            checkIfSelf(id){
                return this.userID === id;
            }
        }

    }
</script>

<style scoped>
    .max {
        max-width: 850px !important;
        margin: 0 auto;
    }
    .rounded-circle {
        width: 200px !important;
        height: 200px !important;
        object-fit: cover !important;
    }

    .btn-submit {
        float: left;
    }

    .form-control-file {
        border: none;
    }
    .custom {
        font-size: 30px;
    }
    .custom:hover {
        cursor: pointer;
    }
</style>
