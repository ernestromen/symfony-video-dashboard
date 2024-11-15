<template>
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
          {{ category.categoryName }}
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
</template>
<script>
import axios from "axios";

export default {

  data: function () {
    return {
      csrfToken: ''
    };
  },
  computed: {
    categories() {
      return this.$store.getters["category/categories"];
    },
    
  },
  created() {
    this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // console.log(this.$store.getters["security/getUser"].id);
   this.$store.dispatch("category/findAll");

  },
  mounted() {

  },
  methods: {

    handleSubmit(e) {

      let FileResult = e.target.querySelector('input[type="file"]').files[0];
      let categoryId = e.target.querySelector('select[name="categoryId"]').value;

      const formData = new FormData();
      
      formData.append('userId', this.$store.getters["security/getUser"].id);
      formData.append('categoryId',categoryId );

      formData.append('video', FileResult,'file');

      axios
        .post('http://app.localhost/api/upload-video',formData
     
      )
        .then((res) => {
          console.log(res.data, 'res is here2');
        })
        .catch((error) => {
          console.log(error, 'this is my error');
        });
    }







  },
};
</script>