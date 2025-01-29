import Vue from "vue";
import Vuex from "vuex";
import SecurityModule from "./security";
import PostModule from "./post";
import EntityModule from "./entity";
import CategoryAPIModule from "./category";
import RoleAPIModule from "./role";
import UserAPIModule from "./user";




Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    security: SecurityModule,
    post: PostModule,
    entity: EntityModule,
    category: CategoryAPIModule,
    role: RoleAPIModule,
    user:UserAPIModule
  }
});
