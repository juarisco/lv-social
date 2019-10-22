
<template>
  <div>
    <a
      :key="notification.id"
      :dusk="notification.id"
      :href="notification.data.link"
      class="dropdown-item"
    >{{ notification.data.message }}</a>
    <button
      v-if="isRead"
      @click.stop="markAsUnread"
      :dusk="`mark-as-unread-${notification.id}`"
    >Marcar como NO leído</button>
    <button
      v-else
      @click.stop="markAsRead"
      :dusk="`mark-as-read-${notification.id}`"
    >Marcar como leída</button>
  </div>
</template>

<script>
export default {
  props: {
    notification: Object
  },
  data() {
    return {
      isRead: !!this.notification.read_at
    };
  },
  methods: {
    markAsRead() {
      axios.post(`/read-notifications/${this.notification.id}`).then(res => {
        this.isRead = true;
      });
    },
    markAsUnread() {
      axios.delete(`/read-notifications/${this.notification.id}`).then(res => {
        this.isRead = false;
      });
    }
  }
};
</script>

<style>
</style>