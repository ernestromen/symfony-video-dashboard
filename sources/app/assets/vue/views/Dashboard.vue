<template>
  <div id="app">
    <nav class="sidebar">
      <ul>
        <li class="px-0">
          <div class="pos-f-t">
            <nav
              class="navbar navbar-dark navbar-toggler bg-dark active_nav"
              data-toggle="collapse"
              @click="check($event)"
            >
              <span
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                aria-controls="navbarToggleExternalContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
                style="pointer-events: none;"
              >
                <span
                  class="tab_name"
                  style="pointer-events: none;"
                >Users</span>
              </span>
            </nav>
            <div
              id="navbarToggleExternalContent"
              class="collapse"
            />
          </div>
        </li>
        <li class="px-0">
          <div class="pos-f-t">
            <nav
              class="navbar navbar-dark navbar-toggler bg-dark"
              data-toggle="collapse"
              @click="check($event)"
            >
              <span
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
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
            />
          </div>
        </li>
        <li class="px-0">
          <div class="pos-f-t">
            <nav
              class="navbar navbar-dark navbar-toggler bg-dark"
              data-toggle="collapse"
              @click="check($event)"
            >
              <span
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
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
            />
          </div>
        </li>
      </ul>
    </nav>

    <main class="content text-center">
      <h1 class="page_h1">
        {{ entities }}
      </h1>

      <button
        v-if="entities === 'videos'"
        class="btn btn-success"
        @click="filter"
      >
        Filter
      </button>
      <!-- experimental table -->
      <table class="table table-striped border mt-5">
        <thead>
          <tr>
            <th class="border-bottom-0">
              {{ first_th }}
            </th>
            <th class="border-bottom-0">
              {{ second_th }}
            </th>
            <th v-if="entities === 'videos'">
              <a
                class="btn btn-primary"
                title="add video"
                :href="`add-video`"
              ><font-awesome-icon :icon="['fas', 'plus']" /></a>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="el in list"
            :key="el.id"
          >
            <td>
              {{ el[first_td] }}
            </td>
            <td>
              {{ el[second_td] }}
            </td>
            <td>
              <a
                class="btn btn-success"
                style="cursor: pointer;"
                :href="`edit-${entity_type}/` + el.id"
              >
                edit
              </a>
            </td>
            <td>
              <button
                class="btn btn-danger"
                @click="deleteEntity(el.id)"
              >
                delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <!--  -->
    </main>


    <div class="row mt-5">
      <div class="col-12" />
    </div>
  </div>
</template>


<script>
import axios from "axios";

export default {
  data: function () {
    return {

      list:[],
      first_th:'User ID',
      second_th:'User login',
      first_td: 'id',
      second_td:'login',
      entity_type:'user',
      entities:'users',
      delete_route:'',
      videoFile: null,
      inputData: '',
      csrfToken: '',
      neededEntities:'',

    };
  },
  computed: {

  },
  created() {
    this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // console.log(this.$store.getters["security/getUser"].id);

  },
  mounted() {
    this.getAllEntities();

  },
  methods: {

    filter(){
      if(this.entities == 'videos'){
        this.list = this.list.filter(entity => entity.categoryId == 27);
      }
    },
    check(event){

      const spanText = event.target.querySelector("span").textContent;
      // active_nav

    if(spanText == 'Categories'){
      this.first_th = 'Category ID';
      this.second_th = 'Category Name';

      this.first_td = 'id';
      this.second_td = 'name';

      this.entity_type = 'category';

      this.entities = 'categories';

    }else if(spanText == 'Videos'){

      this.first_th = 'Video Name';
      this.second_th = 'Category ID';

      this.first_td = 'video_name';
      this.second_td = 'category_id';

      this.entity_type = 'video';

      this.entities = 'videos';


    }else if(spanText == 'Users'){

      this.first_th = 'User ID';
      this.second_th = 'User login';

      this.first_td = 'id';
      this.second_td = 'login';

      this.entity_type = 'user';

      this.entities = 'users';
    }
    this.getAllEntities();

    },

    handleSubmit(e) {

      let FileResult = e.target.querySelector('input[type="file"]').files[0];

      const formData = new FormData();
      
      formData.append('userId', this.$store.getters["security/getUser"].id);
      formData.append('video', FileResult,'file');
      
      axios
        .post('http://app.localhost/api/upload-video',formData
     
      )
        .then((res) => {
          this.videos.push({'videoName':res.data,'videoFilePath':res.data})
        })
        .catch((error) => {
          console.log(error, 'this is my error');
        });
    },

    deleteEntity(id){

      if(this.entity_type == 'category'){

        let str = this.entity_type;
        str = str.slice(0, -1);
        this.neededEntities = str + 's';

      }else{
        this.neededEntities = this.entity_type + 's';
      }
      
      this.list = this.list.filter(entity => entity.id !== id);

      axios
        .delete(
          `http://app.localhost/api/delete-${this.entity_type}/${id}`,
          { data: id },
          { headers: { "content-type": "application/json" } }
        )
        .then(res => {
console.log(res);
        })
        .catch((error) => {
          console.log(error);
        });   
 },


        getAllEntities(){
          axios.get(`http://app.localhost/api/get-${this.entities}`)
        .then((res) => {
         this.list = res.data;
         console.log(res.data);
        })
        .catch(() => {
        });
        },
  },
};
</script>