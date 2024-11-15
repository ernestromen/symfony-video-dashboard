import Vue from "vue";
import App from "./App";
import router from "./router";
import store from "./store";
import './styles.css'; 
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faPlus } from '@fortawesome/free-solid-svg-icons';
import { library } from '@fortawesome/fontawesome-svg-core';

library.add(faPlus);
Vue.component("font-awesome-icon", FontAwesomeIcon);

new Vue({
  components: { App },
  template: "<App/>",
  router,
  store
}).$mount("#app");
