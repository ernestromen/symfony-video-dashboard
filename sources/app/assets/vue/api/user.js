import axios from "axios";

export default {
  createUser(payload) {
    return axios.post("/api/create-user", {userName:payload.userName, roleId: payload.roleId,password: payload.password});
  },
  findAll() {
    return axios.get('/api/users');
  },

  findUserById(id){
    return axios.get(`/api/edit-user/${id}`);
  },

  updateUser(payload){
   return axios.post(`http://app.localhost/api/update-user/${payload.userId}`,{userName: payload.userName,role:payload.role});

  }
};