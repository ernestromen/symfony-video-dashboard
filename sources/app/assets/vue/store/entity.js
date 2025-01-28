import EntityAPI from "../api/entity";

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
    entities2: []
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
    hasEntities(state) {
      return state.entities2.length > 0;
    },
    entities2(state) {
      return state.entities2;
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
      state.entities2.unshift(category);
    },
    [CREATING_CATEGORY_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.entities2 = [];
    },
    [FETCHING_CATEGORIES](state) {
      state.isLoading = true;
      state.error = null;
      state.entities2 = [];
    },
    [FETCHING_CATEGORIES_SUCCESS](state, entities2) {
      state.isLoading = false;
      state.error = null;
      state.entities2 = entities2;
    },
    [FETCHING_CATEGORIES_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.entities2 = [];
    }
  },
  actions: {
    async create({ commit }, message) {
      commit(CREATING_CATEGORY);
      try {
        let response = await EntityAPI.create(message);
        commit(CREATING_CATEGORY_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_CATEGORY_ERROR, error);
        return null;
      }
    },
    async findAll({ commit },payload) {
      console.log('find all in entity');
      commit(FETCHING_CATEGORIES);
      try {
        let response = await EntityAPI.findAll(payload);
        commit(FETCHING_CATEGORIES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_CATEGORIES_ERROR, error);
        return null;
      }
    }
  }
};
