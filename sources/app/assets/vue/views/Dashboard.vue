<template>
  <div id="app">
    <nav class="sidebar">
      <ul>
        <li class="px-0">
          <div class="pos-f-t">
            <nav
              ref="usersNav"
              class="navbar navbar-dark navbar-toggler bg-dark active_nav"
              data-toggle="collapse"
              @click="check($event)"
            >
              <span
                class="navbar-toggler border-0"
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
              ref="videosNav"
              class="navbar navbar-dark navbar-toggler bg-dark"
              data-toggle="collapse"
              @click="check($event)"
            >
              <span
                class="navbar-toggler border-0"
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
              ref="categoriesNav"
              class="navbar navbar-dark navbar-toggler bg-dark"
              data-toggle="collapse"
              @click="check($event)"
            >
              <span
                class="navbar-toggler border-0"
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

    <main
      class="content text-center"
    >
      <h1 class="page_h1">
        {{ entitiesName }}
      </h1>
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
            <th v-if="entitiesName === 'videos'">
              <a
                class="btn btn-primary"
                title="add video"
                :href="`add-video`"
              ><font-awesome-icon
                :icon="['fas', 'plus']"
              /></a>
            </th>
            <th v-if="entitiesName === 'categories'">
              <a
                class="btn btn-primary"
                title="add category"
                :href="`add-category`"
              ><font-awesome-icon
                :icon="['fas', 'plus']"
              /></a>
            </th>
            <th v-if="entitiesName === 'users'">
              <a
                class="btn btn-primary"
                title="add user"
                :href="`add-user`"
              ><font-awesome-icon
                :icon="['fas', 'plus']"
              /></a>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="el in entities"
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
                :disabled="el.login === 'admin'"
              >
                edit
              </a>
            </td>
            <td>
              <button
                class="btn btn-danger"
                :disabled="el.login === 'admin'"
                @click="deleteEntity(el.id)"
              >
                delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
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
    </main>
  </div>
</template>


<script>
import ErrorMessage from "../components/ErrorMessage";
import SuccessMessage from "../components/SuccessMessage";

export default {
  name: "Dashboard",
  components: {
    ErrorMessage,
    SuccessMessage
  },
  data: function () {
    return {

      list: [],
      first_th: 'User ID',
      second_th: 'User login',
      first_td: 'id',
      second_td: 'login',
      entity_type: 'user',
      entitiesName: 'users',
      delete_route: '',
      videoFile: null,
      csrfToken: '',
      neededEntities: '',
    };
  },
  computed: {
    isLoading() {
      return this.$store.getters["entity/isLoading"];
    },
    hasError() {
      return this.$store.getters["entity/hasError"];
    },
    hasSuccess() {
      return this.$store.getters["entity/hasSuccess"];
    },
    error() {
      return this.$store.getters["entity/error"];
    },
    success() {
      return this.$store.getters["entity/success"];
    },
    hasEntities() {
      return this.$store.getters["entity/hasEntities"];
    },
    entities() {
      return this.$store.getters["entity/entities"];
    },
    currentUser(){
      return this.$store.getters["security/getUser"];
    }
  },
  created() {
    this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    this.$store.dispatch("entity/findAll", this.entitiesName);
  },

  mounted() {
  },
  methods: {

    // filter() {
    //   if (this.entities == 'videos') {
    //     this.list = this.list.filter(entity => entity.categoryId == 27);
    //   }
    // },
    check(event) {

      const spanText = event.target.querySelector("span").textContent;
      // active_nav

      this.$refs.usersNav.classList.remove('active_nav');
      this.$refs.videosNav.classList.remove('active_nav');
      this.$refs.categoriesNav.classList.remove('active_nav');

      // Add the 'active_nav' class to the clicked nav
      if (spanText === 'Users') {
        this.$refs.usersNav.classList.add('active_nav');
      } else if (spanText === 'Videos') {
        this.$refs.videosNav.classList.add('active_nav');
      } else if (spanText === 'Categories') {
        this.$refs.categoriesNav.classList.add('active_nav');
      }

      if (spanText == 'Categories') {
        this.first_th = 'Category ID';
        this.second_th = 'Category Name';

        this.first_td = 'id';
        this.second_td = 'name';

        this.entity_type = 'category';

        this.entitiesName = 'categories';

      } else if (spanText == 'Videos') {

        this.first_th = 'Video Path';
        this.second_th = 'Category ID';

        this.first_td = 'video_file_path';
        this.second_td = 'category_id';

        this.entity_type = 'video';

        this.entitiesName = 'videos';


      } else if (spanText == 'Users') {

        this.first_th = 'User ID';
        this.second_th = 'User login';

        this.first_td = 'id';
        this.second_td = 'login';

        this.entity_type = 'user';

        this.entitiesName = 'users';
      }

      this.$store.dispatch("entity/findAll", spanText.toLowerCase());

    },

    deleteEntity(id) {
      if (this.entity_type == 'category') {

        let str = this.entity_type;
        str = str.slice(0, -1);
        this.neededEntities = str + 's';

      } else {
        this.neededEntities = this.entity_type + 's';
      }

      this.$store.dispatch("entity/delete",{entityType:this.entity_type,id:id});
 },
  },
};
</script>