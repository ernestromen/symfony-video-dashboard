import axios from "axios";

export default {
  createCategory(payload) {
    return axios.post("/api/create-category",{"categoryName":payload});
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