<template>
  <div>
    <form
      id="videoUploadForm"
      method="POST"
      enctype="multipart/form-data"
      class="w-50  m-auto"
      @submit.prevent="handleSubmit"
    >
      <div class="form-group">
        <input
          v-model="userName"
          class="form-control"
          type="text"
          placeholder="input user name"
        >
      </div>
      <div class="form-group">
        <input
          v-model="userPassword"
          placeholder="input password"
          class="form-control"
          type="password"
        >
      </div>
      <div class="form-group">
        <select
          v-model="roleId"
          class="form-control custom-select" 
        >
          <option
            value=""
            disabled
            selected
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
        class="btn btn-primary btn-block mb-5 "
      >
        Upload User
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
    name: "AddUser",
    components: {
      ErrorMessage,
      SuccessMessage
    },
    data: function () {
      return {
        csrfToken: '',
        userName:'',
        userPassword:'',
        roleId:''
      };
    },
    computed: {
      roles() {
        return this.$store.getters["role/roles"];
      },
      isLoading() {
        return this.$store.getters["user/isLoading"];
      },
      hasError() {
        return this.$store.getters["user/hasError"];
      },
      hasSuccess() {
        return this.$store.getters["user/hasSuccess"];
      },
      error() {
        return this.$store.getters["user/error"];
      },
      success(){
        return this.$store.getters["user/success"];
      }
      
    },
    created() {
      this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
     this.$store.dispatch("role/findAll");
    },
  
    methods: {
  
      handleSubmit(e) {

        e.preventDefault();

        let formData ={
          userName: this.userName,
          roleId:this.roleId,
          password: this.userPassword
        };

        this.$store.dispatch("user/createUser",formData);
        this.userName = '';
        this.userPassword = '';
        this.roleId = '';

      },
    },
  };
  </script>