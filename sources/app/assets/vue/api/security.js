import axios from "axios";

export default {
  login(login, password) {
    return axios.post("/api/security/login", {
      username: login,
      password: password
    });
  },
  register(login, password) {
    console.log("this reaches the register method");
    console.log('this is the login:',login);
    console.log('this is the password:',password);

    return axios.post("/api/register", {
      username: login,
      password: password
    });
  }
}