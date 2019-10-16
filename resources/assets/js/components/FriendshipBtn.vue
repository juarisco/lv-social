<template>
  <button @click="sendFriendshipRequest" dusk="request-friendship">{{ textBtn }}</button>
</template>

<script>
export default {
  props: {
    recipient: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      textBtn: "Solicitar amistad"
    };
  },
  methods: {
    sendFriendshipRequest() {
      axios
        .post(`friendships/${this.recipient.name}`)
        .then(res => {
          this.textBtn = "Solicitud enviada";
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