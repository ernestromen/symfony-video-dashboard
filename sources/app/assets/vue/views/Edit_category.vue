<template>
  <div class="w-50 m-auto">
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <input
          v-model="category.categoryName"
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
              category: []
          };
      },
  
      mounted() {
          this.getCategory();
  
      },
      methods: {
        getCategory() {
              axios
                  .get(`http://app.localhost/api/edit-category/${this.id}`)
                  .then((res) => {
                      this.category = res.data;
                  })
                  .catch(() => {
                  });
          },
  
          handleSubmit(e) {
              
              e.preventDefault();

              axios
                  .post(`http://app.localhost/api/update-category/${this.id}`,{categoryName: this.category.categoryName}
  
                  )
                  .then((res) => {
                      console.log(res.data, 'res is here2');
                      // this.videos.push({ 'videoName': res.data, 'videoFilePath': res.data })
                  })
                  .catch((error) => {
                      console.log(error, 'this is my error');
                  });
          }
      }
  };
  </script>