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

    <div class="row mt-5">
      <div class="col-12" />
    </div>
    <div
      v-if="isLoading"
      class="m-auto pt-5"
    >
      <p>Loading...</p>
    </div>

    <div
      v-else-if="hasError"
      class="row col"
    >
      <error-message :error="error" />
    </div>

    <div
      v-else-if="!hasEntities"
      class="m-auto"
    >
      No entities!
    </div>


    <main
      v-else
      class="content text-center"
    >
      <h1 class="page_h1">
        {{ entitiesName }}
      </h1>

      <button
        v-if="entitiesName === 'videos'"
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
            <th v-if="entitiesName === 'videos'">
              <a
                class="btn btn-primary"
                title="add video"
                :href="`add-video`"
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
  </div>
</template>


<script>
import ErrorMessage from "../components/ErrorMessage";

export default {
  name: "Dashboard",
  components: {
    ErrorMessage,
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
    error() {
      return this.$store.getters["entity/error"];
    },
    hasEntities() {
      return this.$store.getters["entity/hasEntities"];
    },
    entities() {
      return this.$store.getters["entity/entities"];
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