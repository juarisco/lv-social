<template>
  <div @click="redirectIfGuest">
    <transition-group name="status-list-transition">
      <status-list-item v-for="status in statuses" :key="status.id" :status="status"></status-list-item>
    </transition-group>
  </div>
</template>

<script>
export default {
  props: {
    url: String
  },
  data() {
    return {
      statuses: []
    };
  },
  mounted() {
    axios
      .get(this.getUrl)
      .then(res => {
        this.statuses = res.data.data;
      })
      .catch(err => {
        console.log(err.response.data);
      });

    EventBus.$on("status_created", status => {
      this.statuses.unshift(status);
    });

    Echo.channel("statuses").listen("StatusCreated", ({ status }) => {
      this.statuses.unshift(status);

      console.log(status);
    });
  },
  computed: {
    getUrl() {
      return this.url ? this.url : "/statuses";
    }
  }
};
</script>

<style>
.status-list-transition-move {
  transition: all 0.8s;
}
</style>