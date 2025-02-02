<template>
  <div class="w-50 m-auto">
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <input
          v-model="user.login"
          type="text"
          class="form-control"
          placeholder="Enter user name"
        >
      </div>
      <div class="form-group">
        <select
          v-model="roleEntity"
          class="form-control custom-select"
          name="roleId"
        >
          <option
            value=""
            disabled
          >
            Select an option
          </option>
          <option
            v-for="role in roles"
            :key="role.id"
            :value="role.id"
          >
            {{ role.name }}
          </option>
        </select>
      </div>
      <button
        type="submit"
        class="btn btn-primary w-100"
      >
        Submit
      </button>
    </form>
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
  name: "Editcategory",
  components: {
    ErrorMessage,
    SuccessMessage
  },
  props: {
    id: {
      type: [String, Number],
      required: true,
    }
  },
  data: function () {
    return {
      roleEntity:'',
    };
  },
  computed: {

    roles() {
      return this.$store.getters["role/roles"];
    },
    user() {
      return this.$store.getters["user/user"];
    },
    isLoading() {
      return this.$store.getters["video/isLoading"];
    },
    hasError() {
      return this.$store.getters["video/hasError"];
    },
    hasSuccess() {
      return this.$store.getters["video/hasSuccess"];
    },
    error() {
      return this.$store.getters["video/error"];
    },
    success(){
      return this.$store.getters["video/success"];
    }
  },

  created() {
    this.$store.dispatch("role/findAll");
    this.$store.dispatch("user/findUserById",this.id);
  },

  methods: {
    handleSubmit(e) {
      e.preventDefault();
      this.$store.dispatch("user/updateUser",{userId: this.user.id,userName: this.user.login,role:this.roleEntity});
            },
        }
    };
    </script>