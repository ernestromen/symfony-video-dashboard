<template>
  <div class="w-50 m-auto">
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <input
          v-model="user.login"
          type="text"
          class="form-control"
          placeholder="Enter user name"
        >
      </div>
      <div class="form-group">
        <select
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
  name: "Editcategory",
  props: {
    id: {
      type: [String, Number],
      required: true,
    }
  },
  data: function () {
    return {
      user: [],
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
    this.getUser();
  },
  methods: {
    getUser() {
      axios
        .get(`http://app.localhost/api/edit-user/${this.id}`)
        .then((res) => {
          this.user = res.data;
        })
        .catch(() => {
        });
    },

    handleSubmit(e) {
      e.preventDefault();

                axios
                    .post(`http://app.localhost/api/update-user/${this.id}`,{userName: this.user.login}
    
                    )
                    .then((res) => {
                        console.log(res.data, 'res is here2');
                        // this.videos.push({ 'videoName': res.data, 'videoFilePath': res.data })
    
                        // console.log(this.videos);
                    })
                    .catch((error) => {
                        console.log(error, 'this is my error');
                    });
            }
        }
    };
    </script>