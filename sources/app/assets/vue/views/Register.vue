<template>
  <div>
    <div class="row col">
      <h1 class="m-auto pt-3">
        Register
      </h1>
    </div>
  
    <div class="row col justify-content-center">
      <form>
        <div class="form-row">
          <div class="col-12 mt-3">
            <input
              v-model="login"
              type="text"
              class="form-control"
            >
          </div>
          <div class="col-12 mt-3">
            <input
              v-model="password"
              type="password"
              class="form-control"
            >
          </div>
          <div class="col-12 mt-3">
            <button
              :disabled="login.length === 0 || password.length === 0 || isLoading"
              type="button"
              class="btn btn-primary w-100"
              @click="performRegister()"
            >
              Register
            </button>
          </div>
        </div>
      </form>
    </div>
  
    <div
      v-if="isLoading"
      class="text-center pt-5"
    >
      <p>Loading...</p>
    </div>
    <div
      v-else-if="hasError"
      class="w-50 m-auto text-center pt-4"
    >
      <error-message :error="error" />
    </div>
    <div
      v-else-if="hasSuccess"
      class="w-50 m-auto text-center pt-4"
    >
      <success-message :success="success" />
    </div>
  </div>
</template>
  
  <script>
  import ErrorMessage from "../components/ErrorMessage";
  import SuccessMessage from "../components/SuccessMessage";

  export default {
    name: "Register",
    components: {
      ErrorMessage,
      SuccessMessage
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
      hasSuccess() {
      return this.$store.getters["security/hasSuccess"];
      },
      success() {
        return this.$store.getters["security/success"];
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
      async performRegister() {
        let payload = {login: this.$data.login, password: this.$data.password},
          redirect = this.$route.query.redirect;

        await this.$store.dispatch("security/register", payload);

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