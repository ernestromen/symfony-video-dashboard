<template>
  <div class="container-fluid px-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <router-link
        class="navbar-brand"
        to="/"
      >
        App
      </router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon" />
      </button>
      <div
        id="navbarNav"
        class="collapse navbar-collapse"
      >
        <ul class="navbar-nav">
          <router-link
            v-if="currentUser !== null && currentUser.login === 'admin'"

            class="nav-item"
            tag="li"
            to="/dashboard"
            active-class="active"
          >
            <a class="nav-link">Dashboard</a>
          </router-link>
          <li
            v-if="isAuthenticated"
            class="nav-item"
          >
            <a
              class="nav-link"
              href="/api/security/logout"
            >Logout</a>
          </li>
        </ul>
      </div>
      <span v-if="isAuthenticated">{{ currentUser.login }}</span>
    </nav>

    <router-view />
  </div>
</template>

<script>
import axios from "axios";
  
export default {
  name: "App",
  computed: {
    isAuthenticated() {
      return this.$store.getters["security/isAuthenticated"]
    },
    getState(){
      return this.$store.getters["security/getState"]
    },
    currentUser(){
      return this.$store.getters["security/getUser"];
    }
  },

  mounted(){
  },
  created() {

    let isAuthenticated = JSON.parse(this.$parent.$el.attributes["data-is-authenticated"].value),
      user = JSON.parse(this.$parent.$el.attributes["data-user"].value);
    let payload = { isAuthenticated: isAuthenticated, user: user };
    this.$store.dispatch("security/onRefresh", payload);

    axios.interceptors.response.use(undefined, (err) => {
      return new Promise(() => {
        if (err.response.status === 401) {
          this.$router.push({path: "/login"})
        } else if (err.response.status === 500) {
          document.open();
          document.write(err.response.data);
          document.close();
        }
        throw err;
      });
    });
  },
}
</script>