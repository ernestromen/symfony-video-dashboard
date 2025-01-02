<template>
  <div class="w-50 m-auto">
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <input
          v-model="video.videoFilePath"
          type="text"
          class="form-control"
          placeholder="Enter file path"
        >
      </div>

      <div class="form-group">
        <select
          v-model="video.categoryId"
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
import axios from "axios";

export default {
  name: "Editvideo",
  props: {
    id: {
      type: [String, Number],
      required: true,
    }
  },
  data: function () {
    return {
      video: []
    };
  },
  computed: {
    categories() {
      return this.$store.getters["category/categories"];
    },

  },
  created() {

    this.$store.dispatch("category/findAll");

  },
  mounted() {
    this.getVideo();

  },
  methods: {
    getVideo() {
      axios
        .get(`http://app.localhost/api/edit-video/${this.id}`)
        .then((res) => {
          this.video = res.data;
        })
        .catch(() => {
        });
    },

    handleSubmit(e) {

      e.preventDefault();

      axios
        .post(`http://app.localhost/api/update-video/${this.id}`, { videoFilePath: this.video.videoFilePath,categoryId: this.video.categoryId }

        )
        .then((res) => {
          this.videos.push({ 'videoName': res.data, 'videoFilePath': res.data })

        })
        .catch((error) => {
          console.log(error, 'this is my error');
        });
    }
  }
};
</script>