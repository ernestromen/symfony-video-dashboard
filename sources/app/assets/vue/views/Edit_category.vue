<template>
  <div class="w-50 m-auto">
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <input
          v-model="category.name"
          type="text"
          class="form-control"
          placeholder="Enter category name"
        >
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
    return {};
  },
  computed: {
    category() {
      return this.$store.getters["category/category"];
    },
    isLoading() {
      return this.$store.getters["category/isLoading"];
    },
    hasError() {
      return this.$store.getters["category/hasError"];
    },
    hasSuccess() {
      return this.$store.getters["category/hasSuccess"];
    },
    error() {
      return this.$store.getters["category/error"];
    },
    success() {
      return this.$store.getters["category/success"];
    },
  },
  created() {
    this.$store.dispatch("category/findCategoryById", this.id);
  },
  methods: {
    handleSubmit(e) {
      e.preventDefault();
      this.$store.dispatch("category/updateCategory", { id: this.id, categoryName: this.category.name })
    }
  }
};
</script>