import CategoryAPI from "../api/category";

const CREATING_CATEGORY = "CREATING_CATEGORY",
  CREATING_CATEGORY_SUCCESS = "CREATING_CATEGORY_SUCCESS",
  CREATING_CATEGORY_ERROR = "CREATING_CATEGORY_ERROR",
  FETCHING_CATEGORY = "FETCHING_CATEGORY",
  FETCHING_CATEGORY_SUCCESS = "FETCHING_CATEGORY_SUCCESS",
  FETCHING_CATEGORY_ERROR = "FETCHING_CATEGORY_ERROR",
  FETCHING_CATEGORIES = "FETCHING_CATEGORIES",
  FETCHING_CATEGORIES_SUCCESS = "FETCHING_CATEGORIES_SUCCESS",
  FETCHING_CATEGORIES_ERROR = "FETCHING_CATEGORIES_ERROR",
  UPDATING_CATEGORY = "UPDATING_CATEGORY",
  UPDATING_CATEGORY_SUCCESS= "UPDATING_CATEGORY_SUCCESS",
  UPDATING_CATEGORY_ERROR= "UPDATING_CATEGORY_ERROR";


export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    success:null,
    category:[],
    categories: []
  },
  getters: {
    isLoading(state) {
      return state.isLoading;
    },
    hasError(state) {
      return state.error !== null;
    },
    hasSuccess(state) {
      return state.success !== null;
    },
    error(state) {
      return state.error;
    },
    success(state) {
      return state.success;
    },
    hasCategories(state) {
      return state.categories.length > 0;
    },
    category(state){
      return state.category;
    },
    categories(state) {
      return state.categories;
    }
  },
  mutations: {
    [CREATING_CATEGORY](state) {
      state.isLoading = true;
      state.error = null;
    },
    [CREATING_CATEGORY_SUCCESS](state, category) {
      state.isLoading = false;
      state.error = null;
      state.success = category.message;
      setTimeout(() => {
        state.success = null;
      }, "3000");
    },
    [CREATING_CATEGORY_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
    },
    [FETCHING_CATEGORY](state) {
      state.isLoading = true;
      state.error = null;
      state.cateogry = [];
    },
    [FETCHING_CATEGORY_SUCCESS](state, category) {
      state.isLoading = false;
      state.error = null;
      state.category = category;
    },
    [FETCHING_CATEGORY_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.category = [];
    },
    [FETCHING_CATEGORIES](state) {
      state.isLoading = true;
      state.error = null;
      state.categories = [];
    },
    [FETCHING_CATEGORIES_SUCCESS](state, categories) {
      state.isLoading = false;
      state.error = null;
      state.categories = categories;
    },
    [FETCHING_CATEGORIES_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.categories = [];
    },
    [UPDATING_CATEGORY](state) {
      state.isLoading = true;
      state.error = null;
      state.category = [];
    },
    [UPDATING_CATEGORY_SUCCESS](state, category) {
      state.isLoading = false;
      state.error = null;
      state.category = category.category;
      state.success = category.message;
      setTimeout(() => {
        state.success = null;
      }, "3000");
    },
    [UPDATING_CATEGORY_ERROR](state, error) {

      state.isLoading = false;
      state.error = error;
      state.category = [];
    },
  },
  
  actions: {
    async createCategory({ commit },payload) {
      commit(CREATING_CATEGORY);
      try {
        let response = await CategoryAPI.createCategory(payload);
        commit(CREATING_CATEGORY_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_CATEGORY_ERROR, error);
        return null;
      }
    },

    async findCategoryById({ commit },id) {
      commit(FETCHING_CATEGORY);
      try {
        let response = await CategoryAPI.findCategoryById(id);
        commit(FETCHING_CATEGORY_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_CATEGORY_ERROR, error);
        return null;
      }
    },

    async updateCategory({ commit },payload){

      commit(UPDATING_CATEGORY);
      try {

        let response = await CategoryAPI.updateCategory(payload);
        commit(UPDATING_CATEGORY_SUCCESS, response.data);
        return response.data;

      } catch (error) {
        commit(UPDATING_CATEGORY_ERROR, error);
        return null;
      }
    },

    async findAll({ commit },payload) {
      commit(FETCHING_CATEGORIES);
      try {
        let response = await CategoryAPI.findAll(payload);
        commit(FETCHING_CATEGORIES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_CATEGORIES_ERROR, error);
        return null;
      }
    }
  }
};
