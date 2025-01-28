import axios from "axios";
// the issue is here!
export default {
  create(message) {
    return axios.post("/api/get-users", {
      message: message
    });
  },
  findAll() {
    return axios.get('/api/users');
  }
};