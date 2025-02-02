<template>
  <div class="w-50 m-auto">
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <input
          v-model="video.video_file_path"
          type="text"
          class="form-control"
          placeholder="Enter file path"
        >
      </div>

      <div class="form-group">
        <select
          v-model="categoryType"
          class="form-control custom-select"
          name="categoryId"
        >
          <option
            value=""
            disabled
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
  name: "Editvideo",
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
      categoryType: '',
      videoFilePath:'',
    };
  },
  computed: {
    categories() {
      return this.$store.getters["category/categories"];
    },
    video() {
      return this.$store.getters["video/video"];
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

    this.$store.dispatch("category/findAll");
    this.$store.dispatch("video/findVideoById",this.id);


  },
  mounted() {
  },
  methods: {

    handleSubmit(e) {
      e.preventDefault();
      this.$store.dispatch("video/updateVideo",{ id:this.id,videoFilePath: this.video.video_file_path,categoryId: this.categoryType })
       }
  }
};
</script>