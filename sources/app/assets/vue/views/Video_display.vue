<template>
  <div id="app">
    <nav class="sidebar">
      <ul>
        <li class="px-0">
          <div class="pos-f-t">
            <nav
              class="navbar navbar-dark navbar-toggler bg-dark"
              data-toggle="collapse"
              data-target="#navbarToggleExternalContent2"
              @click="check($event)"
            >
              <span
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarToggleExternalContent2"
                aria-controls="navbarToggleExternalContent2"
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
              id="navbarToggleExternalContent2"
              class="collapse"
            >
              <div
                v-for="video in videos"
                :key="video.id"
                class="bg-dark"
              >
                <!-- start here -->
  
                <div class="border pl-3 py-2">
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
              data-target="#navbarToggleExternalContent3"
              @click="check($event)"
            >
              <span
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarToggleExternalContent3"
                aria-controls="navbarToggleExternalContent3"
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
              id="navbarToggleExternalContent3"
              class="collapse"
            >
              <div
                v-for="category in categories"
                :key="category.id"
                class="bg-dark"
              >
                <!-- start here -->
  
                <div class="border pl-3 py-2">
                  <a :href="`category/${category.id}`">
                    {{ category.categoryName }}
                  </a>
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
            <iframe
              width="560"
              height="315" 
              src="/uploads/videos/file-67330f7711aa0.mp4"
              title="YouTube video player" 
              frameborder="0" 
              allow="accelerometer; autoplay; clipboard-write; 
               encrypted-media; gyroscope; 
               picture-in-picture; web-share" 
              referrerpolicy="strict-origin-when-cross-origin"
              allowfullscreen
            />
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
        list:[],
        entities:'users',
        videoFile: null,
        inputData: '',
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
      check(event){
        const spanText = event.target.querySelector("span").textContent;
  
  
      if(spanText == 'Categories'){
        this.first_th = 'Category ID';
        this.second_th = 'Category Name';
  
        this.first_td = 'id';
        this.second_td = 'categoryName';
  
        this.entity_type = 'category';
  
        this.entities = 'categories';
  
      }else if(spanText == 'Videos'){
  
        this.first_th = 'Video Name';
        this.second_th = 'Video Path';
  
        this.first_td = 'videoName';
        this.second_td = 'videoFilePath';
  
        this.entity_type = 'video';
  
        this.entities = 'videos';
  
  
      }

      this.getAllEntities();
  
      },
    
          getAllEntities(){
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
          })
          .catch(() => {
          });
      },
      getAllCategories(){
        axios
        .get("http://app.localhost/api/get-categories")
          .then((res) => {
  
            this.categories = res.data;
  
          })
          .catch(() => {
          });
      },
    },
  };
  </script>