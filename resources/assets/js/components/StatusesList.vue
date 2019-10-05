<template>
  <div>
    <div v-for="status in statuses" :key="status.id" v-text="status.body"></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      statuses: []
    };
  },
  mounted() {
    axios
      .get("/statuses")
      .then(res => {
        this.statuses = res.data.data;
      })
      .catch(err => {
        console.log(err.response.data);
      });

    EventBus.$on("status_created", status => {
      this.statuses.unshift(status);
    });
  }
};
</script>

<style>
</style>