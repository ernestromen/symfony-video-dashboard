
<template>
  <div id="app">
    <nav class="sidebar">
      <ul>
        <li class="active">
          Categories
        </li>
        <li>Videos</li>
        <li>Users</li>
      </ul>
    </nav>

    <main class="content">
      <h1>Welcome to the Categories Page</h1>
      <p>Here you can manage categories.</p>
      <div
        v-for="video in videos"
        :key="video.id"
        class="video-item"
      >
        <h2>{{ video.userId }}</h2> <!-- Assuming video has a title property -->
        <p>{{ video.videoName }}</p> <!-- Assuming video has a description property -->
      <!-- Add more video properties as needed -->
      </div>
    </main>
  </div>
</template>


<script>
import axios from "axios";

export default {
  data: function () {
    return {
      videos: [],
    };
  },
  computed: {

  },
  mounted() {
    this.getAllvideos();
  },
  methods: {
    getAllvideos() {
      axios
        .get("http://app.localhost/api/get-videos")
        .then((res) => {

            this.videos = res.data;
            console.log(this.videos);
            // console.log(res);

        })
        .catch(() => {
        });
    },
  },
};
</script>