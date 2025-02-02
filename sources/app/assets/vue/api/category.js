import axios from "axios";
// the issue is here!
export default {
  create(message) {
    return axios.post("/api/get-categories", {
      message: message
    });
  },
  findCategoryById(id){
    return axios.get(`http://app.localhost/api/edit-category/${id}`);
  },
  updateCategory(payload){
   return axios.post(`http://app.localhost/api/update-category/${payload.id}`,{categoryName: payload.categoryName});
  },
  findAll() {
    return axios.get("/api/get-categories");
  }
};