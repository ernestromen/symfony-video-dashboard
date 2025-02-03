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
        <label for="video">Add Video File:</label>
        <input
          id="video"
          ref="videoInput"
          type="file"
          class="form-control-file"
          name="video"
          accept="video/*"
          required
        >
      </div>
      <div class="form-group">
        <select
          class="form-control custom-select"
          name="categoryId" 
        >
          <option
            value=""
            disabled
            selected
          >
            Select an option
          </option>
          <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
          >
            {{ category.name }}
          </option>
        </select>
      </div>
      <div class="form-group">
        <select
          class="form-control custom-select"
          name="roleId" 
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
        Upload Video
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
  name: "AddVideo",
  components: {
    ErrorMessage,
    SuccessMessage
  },
  data: function () {
    return {
      csrfToken: '',
    };
  },
  computed: {
    categories() {
      return this.$store.getters["category/categories"];
    },
    roles() {
      return this.$store.getters["role/roles"];
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
    this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
   this.$store.dispatch("category/findAll");
   this.$store.dispatch("role/findAll");
  },

  methods: {

    handleSubmit(e) {
      let FileResult = e.target.querySelector('input[type="file"]').files[0];
      let categoryId = e.target.querySelector('select[name="categoryId"]').value;
      let roleId = e.target.querySelector('select[name="roleId"]').value;

      const formData = new FormData();
      
      formData.append('categoryId',categoryId );
      formData.append('roleId',roleId );


      formData.append('video', FileResult,'file');
      formData.append('csrfToken',this.csrfToken);

      this.$store.dispatch("video/createVideo",formData);
    },
  },
};
</script>