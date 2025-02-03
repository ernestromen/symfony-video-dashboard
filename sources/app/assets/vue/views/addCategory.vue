<template>
  <div class="w-50 m-auto">
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <input
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
  </div>
</template>
<script>
export default {

    data: function () {
        return {
            csrfToken: '',
        };
    },
    created() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    },
    methods: {

        handleSubmit(e) {
            e.preventDefault();
            
        },
    },
};
</script>