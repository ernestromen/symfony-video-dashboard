import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store";
import Home from "../views/Home";
import Login from "../views/Login";
import Posts from "../views/Posts";
import Dashboard from "../views/Dashboard";
import Editvideo from "../views/Edit_video";
import Editcategory from "../views/Edit_category";
import Edituser from "../views/Edit_user";
import VideoDisplay from "../views/Video_display";
import AddVideo from "../views/AddVideo";
import CategoryPage from "../views/CategoryPage";


Vue.use(VueRouter);

let router = new VueRouter({
  mode: "history",
  routes: [
    { path: "/", component: VideoDisplay },

    { path: "/dashboard", component: Dashboard },
    { path: "/home", component: Home },
    { path: "/login", component: Login },
    { path: "/posts", component: Posts, meta: { requiresAuth: true } },
        {
          path: "/edit-video/:id",
          component: Editvideo,
          props: true    
        },
        {
          path: "/edit-category/:id",
          component: Editcategory,
          props: true
        },
        {
          path: "/edit-user/:id",
          component: Edituser,
          props: true            
        },
        {
          path: "/add-video",
          component: AddVideo,
          props: true          
        },
        {
          path: "/category/:id",
          component: CategoryPage,
          props: true          
        },
    // { path: "*", redirect: "/home" }
  ],
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (store.getters["security/isAuthenticated"]) {
      next();
    } else {
      next({
        path: "/login",
        query: { redirect: to.fullPath }
      });
    }
  } else {
    next(); // make sure to always call next()!
  }
});

export default router;