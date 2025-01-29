import axios from "axios";

export default {
  create(message) {
    return axios.post("/api/get-categories", {
      message: message
    });
  },
  findAll() {
    return axios.get("/api/get-all-roles");
  }
};