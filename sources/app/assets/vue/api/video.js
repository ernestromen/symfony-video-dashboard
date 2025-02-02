import axios from "axios";

export default {
  createVideo(payload) {
    return  axios.post('http://app.localhost/api/upload-video',payload);
  },

  findVideoById(id){
    return axios.get(`http://app.localhost/api/edit-video/${id}`)
  },

  updateVideo(payload){
   return axios.post(`http://app.localhost/api/update-video/${payload.id}`, { videoFilePath: payload.videoFilePath,categoryId: payload.categoryId })

  }
};