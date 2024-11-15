import axios from "axios";

export default {
  create(message) {
    return axios.post("/api/posts", {
      message: message
    });
  },
  findAll() {
    console.log('findall posts');
    return axios.get("/api/posts");
  }
};