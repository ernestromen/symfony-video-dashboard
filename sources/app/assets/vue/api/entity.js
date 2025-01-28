import axios from "axios";

export default {
  create(message) {
    return axios.post("/api/get-categories", {
      message: message
    });
  },
  findAll($entities) {
    return axios.get(`http://app.localhost/api/get-${$entities}`);
  }
};