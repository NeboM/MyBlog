<template>
    <div class="container max">
        <h3>Create post</h3>
        <form @submit="checkForm" action="/posts" method="POST">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-group">
                <input class="form-control" placeholder="Post title..." v-model="title" name="title">
            </div>
            <div class="form-group">
                <textarea class="form-control" placeholder="Your content..." v-model="body" name="body" rows="7">
                </textarea>
            </div>
            <button type="submit" class="btn btn-bulma-full long">Submit</button>
            <p v-for="error in this.errors">{{error}}</p>
        </form>
    </div>
</template>

<script>
    export default {
        name: "CreatePostComponent",
        data(){
            return {
                'csrf': '',
                'title': null,
                'body': null,
                'errors': [],
            }
        },
        created() {
            this.csrf = window.Laravel.csrfToken;
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
