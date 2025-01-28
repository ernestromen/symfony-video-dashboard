import Vue from "vue";
import Vuex from "vuex";
import SecurityModule from "./security";
import PostModule from "./post";
import EntityModule from "./entity";
import CategoryAPIModule from "./category";


Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    security: SecurityModule,
    post: PostModule,
    entity: EntityModule,
    category: CategoryAPIModule
  }
});
