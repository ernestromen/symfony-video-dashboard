import axios from "axios";

export default {
  create(message) {
    return axios.post("/api/get-categories", {
      message: message
    });
  },
  findAll($entities) {
    return axios.get(`http://app.localhost/api/get-${$entities}`);
  },
  delete(payload){
        return axios
        .delete(
          `http://app.localhost/api/delete-${payload.entityType}/${payload.id}`,
          { data: payload.id },
          { headers: { "content-type": "application/json" } }
        );
  }
};