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
                .post(`http://app.localhost/api/update-video/${this.id}`,{videoFilePath: this.video.videoFilePath}

                )
                .then((res) => {
                    console.log(res.data, 'res is here2');
                    this.videos.push({ 'videoName': res.data, 'videoFilePath': res.data })

                })
                .catch((error) => {
                    console.log(error, 'this is my error');
                });
        }
    }
};
</script>