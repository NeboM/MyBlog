<template>
    <div>
    <div class="noselect">
        <span class="mr-1">
            <i class="fa fa-angle-up fa-size" ref="upvote" v-bind:class="{ bulmaColor: this.post.vote === 1}" @click="vote(1,post.id,$event)"></i>
            <template v-if="fromPost">
                    {{ this.newPost.upvotes === null ? 0 : this.newPost.upvotes}}
            </template>
            <template v-else>
                {{ this.post.upvotes === null ? 0 : this.post.upvotes}}
            </template>

        </span>
        <span>
            <i class="fa fa-angle-down fa-size" ref="downvote" v-bind:class="{ bulmaColor: this.post.vote === 0}" @click="vote(0,post.id,$event)"></i>
            <template v-if="fromPost">
                    {{ this.newPost.downvotes === null ? 0 : this.newPost.downvotes}}
            </template>
            <template v-else>
                {{ this.post.downvotes === null ? 0 : this.post.downvotes}}
            </template>
        </span>
    </div>
    </div>
</template>

<script>
    export default {
        name: "VotesComponent",
        props: ['post','fromPost'],
        data(){
            return {
                upvotes: '',
                downvotes: '',
                votes: '',
                newPost: {},
            }
        },
        methods: {
            vote(downORup,postID,e){
                if(downORup === 1){
                    // Upvote
                    axios.put('/upvote/'+postID)
                        .then(res => {
                            if(res.data === 1){
                                if(this.fromPost)  ++this.newPost.upvotes;
                                else ++this.post.upvotes;
                                e.target.classList.add('bulmaColor');
                            }else if(res.data === -1){
                                if(this.fromPost) --this.newPost.upvotes;
                                else --this.post.upvotes;
                                e.target.classList.remove('bulmaColor');
                            }else{
                                if(this.fromPost){
                                    ++this.newPost.upvotes;
                                    --this.newPost.downvotes;
                                }else{
                                    ++this.post.upvotes;
                                    --this.post.downvotes;
                                }
                                e.target.classList.add('bulmaColor');
                                e.target.parentElement.nextElementSibling.firstElementChild.classList.remove('bulmaColor');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                        });
                }else{
                    // Downvote
                    axios.put('/downvote/'+postID)
                        .then(res => {
                            if(res.data === 1){
                                if(this.fromPost) ++this.newPost.downvotes;
                                else ++this.post.downvotes;
                                e.target.classList.add('bulmaColor');

                            }else if(res.data === -1){
                                if(this.fromPost) --this.newPost.downvotes;
                                else --this.post.downvotes;
                                e.target.classList.remove('bulmaColor');

                            }else{
                                if(this.fromPost){
                                    ++this.newPost.downvotes;
                                    --this.newPost.upvotes;
                                }else{
                                    ++this.post.downvotes;
                                    --this.post.upvotes;
                                }
                                e.target.classList.add('bulmaColor');
                                e.target.parentElement.previousElementSibling.firstElementChild.classList.remove('bulmaColor');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                        });
                }
            }
        },
        mounted() {
            if(this.fromPost === true){
                this.newPost = this.post;
            }
        }
    }
</script>

<style scoped>
    .fa-size {
        font-size: 20px;
        font-weight: 100 !important;
    }
    i:hover {
        cursor: pointer !important;
    }
</style>
