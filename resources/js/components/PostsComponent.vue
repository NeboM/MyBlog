<template>
    <div class="container">
        <list-posts-component v-bind:posts="posts"></list-posts-component>
            <div class="card-footer max" v-if="loaded === true">
                <a href="#" class="up"><i class="fas fa-angle-double-up float-right mt-3 mr-3"></i></a>
                <pagination :data="posts" @pagination-change-page="getResults" class="mt-3"></pagination>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                'posts': {},
                'loaded': false,
            }
        },
        mounted(){
            let uri = window.location.search.substring(1);
            let params = new URLSearchParams(uri);
            if(params.get("page") !== null){
                this.getResults(params.get("page"));
            }else{
                this.getResults();
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get('/get/posts?page=' + page)
                    .then(response => {
                        this.posts = response.data;
                        this.loaded = true;
                    });
            }
        },
    }
</script>

<style>
    .max {
        max-width: 850px !important;
        margin: 0 auto;
    }
    .up {
        font-size: 30px;
    }
    .up:hover {
        cursor: pointer;
    }
    .custom-link:active, .custom-link:focus {
        outline: none !important;
    }
    .custom-link {
        background: none;
        border: none;
        color: gray !important;
        font-size: 18px;
    }
    .pen:hover {
        color: #0f74a8;
        cursor: pointer;
    }
    .trash:hover {
        color: #012 !important;
    }
    .btn-submit,.btn-cancel {
        font-weight: 600;
        height: 40px;
        width: 100px;
        margin-bottom: 10px;
    }
    .btn-submit {
        color: #fff !important;
        background-color: #CCCCCC;
    }
    .btn-cancel {
        color: #CCCCCC !important;
        background-color: #F8FAFC !important;
    }
</style>
