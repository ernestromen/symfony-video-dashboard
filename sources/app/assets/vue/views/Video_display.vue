<template>
  <div id="app">
    <nav class="sidebar">
      <ul>
        <li class="px-0">
          <div class="pos-f-t">
            <nav
              class="navbar navbar-dark navbar-toggler bg-dark"
              data-toggle="collapse"
              data-target="#navbarToggleExternalContentForAllVideos"
            >
              <span
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarToggleExternalContentForAllVideos"
                aria-controls="navbarToggleExternalContentForAllVideos"
                aria-expanded="false"
                aria-label="Toggle navigation"
                style="pointer-events: none;"
              >
                <span
                  class="tab_name"
                  style="pointer-events: none;"
                >Videos</span>
              </span>
            </nav>
            <div
              id="navbarToggleExternalContentForAllVideos"
              class="collapse"
            >
              <div
                v-for="video in videos"
                :key="video.id"
                class="bg-dark"
              >
                <!-- start here -->

                <div
                  class="border pl-3 py-2"
                  @click="changeVideoFilePath(video.videoFilePath)"
                >
                  {{ video.videoFilePath }}
                </div>

                <!-- end here -->
              </div>
            </div>
          </div>
        </li>
        <li class="px-0">
          <div class="pos-f-t">
            <nav
              class="navbar navbar-dark navbar-toggler bg-dark"
              data-toggle="collapse"
              data-target="#navbarToggleExternalContentForCategories"
            >
              <span
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarToggleExternalContentForCategories"
                aria-controls="navbarToggleExternalContentForCategories"
                aria-expanded="false"
                aria-label="Toggle navigation"
                style="pointer-events: none;"
              >
                <span
                  class="tab_name"
                  style="pointer-events: none;"
                >Categories</span>
              </span>
            </nav>
            <div
              id="navbarToggleExternalContentForCategories"
              class="collapse"
            >
              <div
                v-for="category in categories"
                :key="category.id"
                class="bg-dark"
              >
                <!-- start here -->

                <div class="border pl-3 py-2">
                  <!-- experiment -->
                  <nav
                    class="navbar navbar-dark navbar-toggler bg-dark"
                    data-toggle="collapse"
                    :data-target="`#navbarToggleExternalContent${category.id}`"
                  >
                    <span
                      class="navbar-toggler"
                      type="button"
                      data-toggle="collapse"
                      :data-target="`#navbarToggleExternalContent${category.id}`"
                      :aria-controls="`navbarToggleExternalContent${category.id}`"
                      aria-expanded="false"
                      aria-label="Toggle navigation"
                      style="pointer-events: none;"
                    >
                      <span
                        class="tab_name"
                        style="pointer-events: none;"
                      >{{ category.name }}</span>
                    </span>
                  </nav>
                  <!-- end experiment -->
                  <div
                    id="navbarToggleExternalContentForCategories"
                    class="collapse"
                  >
                    <div
                      v-for="categoryVideo in category.videos"
                      
                      :id="`navbarToggleExternalContent${category.id}`"
                      :key="categoryVideo.id"
                      class="collapse ID HERE!"
                    >
                      <div
                        class="border pl-3 py-2"
                        @click="changeVideoFilePath(categoryVideo.videoFilePath)"
                      >
                        {{ categoryVideo.videoFilePath }}
                      </div>
                    </div>
                  </div>
                  <!-- <div
                    id="navbarToggleExternalContent4"
                    class="collapse"
                  >
                    testing2
                  </div>
                  <div
                    id="navbarToggleExternalContent4"
                    class="collapse"
                  >
                    testing3
                  </div> -->
                </div>

                <!-- end here -->
              </div>
            </div>
          </div>
        </li>
      </ul>
    </nav>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h1 class="m-auto text-center">
            display of video here
          </h1>
        </div>
        <div class="col-12">
          <p class="text-center">
            <template v-if="mainVideoPath">
              <video
                :key="mainVideoPath"
                controls="true"
                class="embed-responsive-item"
                width="560"
                height="315"
              >
                <source
                  :src="`/uploads/videos/${mainVideoPath}`"
                  type="video/mp4"
                >
              </video>
            </template>
            <template v-else>
              <p>No video available</p>
            </template>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import axios from "axios";

export default {
  data: function () {
    return {
      videos: [],
      categories: [],
      users: [],
      list: [],
      entities: 'users',
      videoFile: null,
      inputData: '',
      mainVideoPath: '',
      csrfToken: ''
    };
  },
  computed: {

  },
  created() {
    this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // console.log(this.$store.getters["security/getUser"].id);

  },
  mounted() {
    this.getAllvideos();
    this.getAllCategories();
  },
  methods: {
    changeVideoFilePath(path) {
      this.mainVideoPath = path;

    },


    getAllEntities() {
      axios.get(`http://app.localhost/api/get-${this.entities}`)
        .then((res) => {
          this.list = res.data;
        })
        .catch(() => {
        });
    },

    getAllvideos() {
      axios
        .get("http://app.localhost/api/get-videos")
        .then((res) => {

          this.videos = res.data;

          this.mainVideoPath = res.data[0].videoFilePath;

        })
        .catch(() => {
        });
    },
    getAllCategories() {
      axios
        .get("http://app.localhost/api/get-categories")
        .then((res) => {

          this.categories = res.data;
          console.log(this.categories[0].videos[0].videoFilePath, 'this is the categories!')

        })
        .catch(() => {
        });
    },
  },
};
</script>