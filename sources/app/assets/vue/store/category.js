import CategoryAPI from "../api/category";

const CREATING_CATEGORY = "CREATING_CATEGORY",
  CREATING_CATEGORY_SUCCESS = "CREATING_CATEGORY_SUCCESS",
  CREATING_CATEGORY_ERROR = "CREATING_CATEGORY_ERROR",
  FETCHING_CATEGORIES = "FETCHING_CATEGORIES",
  FETCHING_CATEGORIES_SUCCESS = "FETCHING_CATEGORIES_SUCCESS",
  FETCHING_CATEGORIES_ERROR = "FETCHING_CATEGORIES_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    categories: []
  },
  getters: {
    isLoading(state) {
      return state.isLoading;
    },
    hasError(state) {
      return state.error !== null;
    },
    error(state) {
      return state.error;
    },
    hasCategories(state) {
      return state.categories.length > 0;
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
      state.categories.unshift(category);
    },
    [CREATING_CATEGORY_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.categories = [];
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
    }
  },
  actions: {
    async create({ commit }, message) {
      commit(CREATING_CATEGORY);
      try {
        let response = await CategoryAPI.create(message);
        commit(CREATING_CATEGORY_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_CATEGORY_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_CATEGORIES);
      try {
        let response = await CategoryAPI.findAll();
        commit(FETCHING_CATEGORIES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_CATEGORIES_ERROR, error);
        return null;
      }
    }
  }
};
