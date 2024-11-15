import axios from "axios";
// the issue is here!
export default {
  create(message) {
    return axios.post("/api/get-categories", {
      message: message
    });
  },
  findAll() {
    console.log('from findall!');
    return axios.get("/api/get-categories");
  }
};