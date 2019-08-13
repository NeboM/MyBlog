<template>
    <div>
        <h4 class="text-muted">{{ comments.length }} Comments</h4>

        <form @submit.prevent="submitComment" class="mt-3">
            <input type="hidden" name="_token" :value="csrf">
            <textarea  rows="1" class="comment" name="body"  placeholder="Add a public comment" @click="showSubmit = true"  v-model="commentBody">
            </textarea>


            <template v-if="showSubmit">
                <input type="submit" class="btn btn-submit" value="COMMENT">
                <button class="btn btn-cancel" @click="showSubmit = false">CANCEL</button>
            </template>
            <div class="add-space"></div>
        </form>
        <div class="clearfix"></div>
        <div v-for="(comment,index) in this.comments" v-bind:key="comment.id">


            <a :href="'/profile/'+comment.user_id"><img class="rounded-circle" :src="'/storage/avatars/'+comment.avatar" /></a>
            <a :href="'/profile/'+comment.user_id"><h5 class="pt-2 d-inline-block">{{ comment.name }}</h5></a>

            <div v-if="comment.user_id === userID && comment.id !== editCommentID" class="inline-absolute">
                <a @click="editComment(comment.id,comment.body)" class="custom-link"><i class="fas pen fa-pen"></i></a>
                <form @submit.prevent="deleteComment(comment.id,index)">
                    <input type="hidden" name="_token" :value="csrf">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="custom-link mr-2 trash"><i class="fas fa-trash-alt"></i></button>
                </form>
            </div>

            <div class="clearfix"></div>
            <p><small><i class="fa fa-pen fa-size-1"></i> Modified: {{comment.updated_at}}</small></p>


            <template v-if="comment.id === editCommentID">


                <form @submit.prevent="updateComment(comment.id)">
                    <textarea rows="1" placeholder="Comment..." class="comment" name="body" v-model="newComment"></textarea>
                    <input type="hidden" name="_token" :value="csrf">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="submit" class="btn btn-submit" value="SAVE">
                </form>
                <button @click="cancelEdit" class="btn btn-cancel">CANCEL</button>
                <div class="add-space"></div>
                <div class="clearfix"></div>

            </template>
            <template v-else>
                <p>{{ comment.body }}</p>
            </template>

            <div class="add-space"></div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "CommentsController",
        props: ['postID'],
        data(){
            return {
                'csrf': '',
                'userID': '',
                'comments': [],
                'commentBody': null,
                'showSubmit': false,
                'newComment': '',
                'editCommentID': 0, // We use this number to show the TEXTAREA for editing the comment with that ID
                // If the value is 0 the user is not editing any comment
            }
        },
        created(){
            this.csrf = window.Laravel.csrfToken;
            this.userID = window.Laravel.user.id;
            axios.get('/comments/'+this.postID)
                .then(res => {
                    this.comments = res.data;
                })
                .catch(err => {
                    console.error(err);
                });
        },
        methods: {
            submitComment(e){
                if (this.commentBody) {
                    axios.post('/comment/'+this.postID,{
                        body: this.commentBody,
                        post_id: this.postID
                    })
                        .then(res => {
                            this.comments.unshift(res.data);
                            // console.log(res.data);
                            this.showSubmit = false;
                        })
                        .catch(err => {
                            console.error(err);
                        });
                    this.commentBody = null;
                }
            },
            editComment(id,commentBody){
                this.editCommentID = id;
                this.newComment = commentBody;
            },
            cancelEdit(){
                this.editCommentID = 0;
            },
            updateComment(id){
                axios.put('/comment/'+id,{
                    body: this.newComment
                })
                    .then(res => {
                        this.comments[0].body = res.data.body;
                        this.cancelEdit();
                    })
                    .catch(err => {
                        console.error(err);
                    });
            },
            deleteComment(id,index){
                axios.delete('/comment/'+id)
                    .then(res => {
                        this.comments.splice(index,1);
                    })
                    .catch(err => {
                        console.error(err);
                    });
            }
        }
    }
</script>

<style scoped>
    .rounded-circle {
        width: 40px !important;
        height: 40px !important;
        object-fit: cover !important;
        float: left;
        margin: 0 6px 6px 0;
    }
    .comment {
        background-color: #F8FAFC;
        border: none;
        border-bottom: 1px solid gray;
        border-radius: 0;
        width: 100%;
        resize: none !important;
    }
    .comment:focus, .comment:active {
        outline: none !important;
        border-bottom: 1px solid black;

    }
    .btn-submit,.btn-cancel {
        float: right;
    }
    .custom-link {
        position: absolute;
        top: -15px;
        left: 6px !important;
        margin-top: 20px !important;
    }
    .trash {
        left: 25px !important;
    }
    .inline-absolute {
        display: inline;
        position: absolute;
    }
</style>
