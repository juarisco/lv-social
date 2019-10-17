<template>
  <div>
    <div v-if="localFriendshipStatus === 'pending'">
      <span v-text="sender.name"></span> te ha enviado una solicitud de amistad
      <button
        @click="acceptFriendshipRequest"
      >Aceptar solicitud</button>
    </div>
    <div v-if="localFriendshipStatus === 'accepted'">
      Tú y
      <span v-text="sender.name"></span> son amigos
    </div>
  </div>
</template>

<script>
export default {
  props: {
    sender: {
      type: Object,
      required: true
    },
    friendshipStatus: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      localFriendshipStatus: this.friendshipStatus
    };
  },
  methods: {
    acceptFriendshipRequest() {
      axios
        .post(`/accept-friendships/${this.sender.name}`)
        .then(res => {
          this.localFriendshipStatus = "accepted";
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