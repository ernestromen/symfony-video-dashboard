<template>
  <div>
    <div class="row col justify-content-center">
      <h1 class="text-light mt-3">
        Login
      </h1>
    </div>

    <div class="row col justify-content-center">
      <form>
        <div class="form-row justify-content-center">
          <div class="col-12 mt-2">
            <input
              v-model="login"
              type="text"
              class="form-control"
            >
          </div>
          <div class="col-12 mt-2">
            <input
              v-model="password"
              type="password"
              class="form-control"
            >
          </div>
          <div class="col-12 mt-2">
            <button
              :disabled="login.length === 0 || password.length === 0 || isLoading"
              type="button"
              class="btn btn-primary w-100"
              @click="performLogin()"
            >
              Login
            </button>
          </div>
          <div class="pl-2">
            <a
              class="text-light"
              href="/register"
            >Register here!</a>
          </div>
        </div>
      </form>
    </div>

    <div
      v-if="isLoading"
      class="row col"
    >
      <p>Loading...</p>
    </div>

    <div
      v-else-if="hasError"
      class="row col"
    >
      <error-message :error="error" />
    </div>
  </div>
</template>

<script>
import ErrorMessage from "../components/ErrorMessage";

export default {
  name: "Login",
  components: {
    ErrorMessage,
  },
  data() {
    return {
      login: "",
      password: ""
    };
  },
  computed: {
    isLoading() {
      return this.$store.getters["security/isLoading"];
    },
    hasError() {
      return this.$store.getters["security/hasError"];
    },
    error() {
      return this.$store.getters["security/error"];
    },
    getState() {
      return this.$store.getters["security/getState"];
    }
        
  },
  created() {
    let redirect = this.$route.query.redirect;

    if (this.$store.getters["security/isAuthenticated"]) {
      if (typeof redirect !== "undefined") {
        this.$router.push({path: redirect});
      } else {
        this.$router.push({path: "/"});
      }
    }
  },
  methods: {
    async performLogin() {
      let payload = {login: this.$data.login, password: this.$data.password},
        redirect = this.$route.query.redirect;

      await this.$store.dispatch("security/login", payload);
      if (!this.$store.getters["security/hasError"]) {
        if (typeof redirect !== "undefined") {
          this.$router.push({path: redirect});
        } else {
          this.$router.push({path: "/"});
        }
      }
    }
  }
}
</script>