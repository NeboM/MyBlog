<template>
    <div class="container max">
        <h3>Edit post</h3>
        <form @submit="checkForm" :action="'/posts/' + post.id" method="POST">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group">
                <input class="form-control" placeholder="Post title..." v-model="title" name="title">
            </div>
            <div class="form-group">
                <textarea class="form-control" placeholder="Post content..." v-model="body" name="body" rows="7">
                </textarea>
            </div>
            <button type="submit" class="btn btn-bulma-full long">Submit</button>
            <p v-for="error in this.errors">{{error}}</p>
        </form>
    </div>
</template>

<script>
    export default {
        name: "EditPostComponent",
        data(){
            return {
                'csrf': '',
                'title': null,
                'body': null,
                'errors': [],
            }
        },
        props: ['post'],
        created() {
            this.csrf = window.Laravel.csrfToken;
            this.body = this.post.body;
            this.title = this.post.title;
        },
        methods: {
            checkForm(e){
                if (this.title && this.body) {
                    return true;
                }

                this.errors = [];

                if (!this.title) {
                    this.errors.push('Title required.');
                }
                if (!this.body) {
                    this.errors.push('Body required.');
                }

                e.preventDefault();
            }
        }
    }
</script>
<style scoped>
    .long {
        width: 140px !important;
    }
</style>
