import axios from "axios";

export default {
  create(message) {
    return axios.post("/api/get-users", {
      message: message
    });
  },
  findAll() {
    return axios.get('/api/users');
  },

  findUserById(id){
    return axios.get(`/api/edit-user/${id}`);
  },

  updateUser(payload){
    console.log(payload,'this is the payload');
   return axios.post(`http://app.localhost/api/update-user/${payload.userId}`,{userName: payload.userName,role:payload.role});

  }
};