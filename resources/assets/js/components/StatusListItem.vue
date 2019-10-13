<template>
  <div class="card border-0 mb-3 shadow-sm">
    <div class="card-body d-flex flex-column">
      <div class="d-flex align-items-center mb-3">
        <img
          class="rounded mr-3 shadow-sm"
          width="40px"
          src="https://aprendible.com/images/default-avatar.jpg"
          alt
        />
        <div class>
          <h5 class="mb-1" v-text="status.user_name"></h5>
          <div class="small text-muted" v-text="status.ago"></div>
        </div>
      </div>
      <p class="card-text text-secondary" v-text="status.body"></p>
    </div>
    <div class="card-footer p-2 d-flex justify-content-between align-items-center">
      <like-btn dusk="like-btn" :url="`/statuses/${status.id}/likes`" :model="status"></like-btn>

      <div class="text-secondary mr-2">
        <i class="far fa-thumbs-up"></i>
        <span dusk="likes-count">{{ status.likes_count }}</span>
      </div>
    </div>

    <div class="card-footer">
      <div v-for="comment in comments" :key="comment.id" class="mb-3">
        <div class="d-flex">
          <img
            class="rounded shadow-sm mr-2"
            height="34px"
            width="34px"
            :src="comment.user_avatar"
            :alt="comment.user_name"
          />
          <div class="flex-grow-1">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-2 text-secondary">
                <a href="#">
                  <strong>{{ comment.user_name }}</strong>
                </a>
                {{ comment.body }}
              </div>
            </div>
            <small
              class="badge badge-pill badge-primary py-1 px-2 mt-1 float-right"
              dusk="comment-likes-count"
            >
              <i class="fa fa-thumbs-up"></i>
              {{ comment.likes_count }}
            </small>

            <like-btn
              dusk="comment-like-btn"
              :url="`/comments/${comment.id}/likes`"
              :model="comment"
              class="comments-like-btn"
            ></like-btn>
          </div>
        </div>
      </div>
      <form @submit.prevent="addComment" v-if="isAuthenticated">
        <div class="d-flex align-items-center">
          <img
            class="rounded shadow-sm mr-2"
            width="34px"
            src="https://aprendible.com/images/default-avatar.jpg"
            :alt="currentUser.name"
          />
          <div class="input-group">
            <textarea
              v-model="newComment"
              class="form-control border-0 shadow-sm"
              name="comment"
              placeholder="Escribe un comentario..."
              rows="1"
              required
            ></textarea>
            <div class="input-group-append">
              <button class="btn btn-primary" dusk="comment-btn">Enviar</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import LikeBtn from "./LikeBtn";
export default {
  props: {
    status: {
      type: Object,
      required: true
    }
  },
  components: { LikeBtn },
  data() {
    return {
      newComment: "",
      comments: this.status.comments
    };
  },
  methods: {
    addComment() {
      axios
        .post(`/statuses/${this.status.id}/comments`, { body: this.newComment })
        .then(res => {
          this.newComment = "";
          this.comments.push(res.data.data);
        })
        .catch(err => {
          console.log(err.response.data);
        });
    }
  }
};
</script>

<style>
</style>