import EntityAPI from "../api/entity";

const CREATING_CATEGORY = "CREATING_CATEGORY",
  CREATING_CATEGORY_SUCCESS = "CREATING_CATEGORY_SUCCESS",
  CREATING_CATEGORY_ERROR = "CREATING_CATEGORY_ERROR",
  DELETING_ENTITY = 'DELETING_ENTITY',
  DELETING_ENTITY_SUCCESS='DELETING_ENTITY_SUCCESS',
  DELETING_ENTITY_ERROR = 'DELETING_ENTITY_ERROR',
  FETCHING_CATEGORIES = "FETCHING_CATEGORIES",
  FETCHING_CATEGORIES_SUCCESS = "FETCHING_CATEGORIES_SUCCESS",
  FETCHING_CATEGORIES_ERROR = "FETCHING_CATEGORIES_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    success:null,
    entities: []
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
    hasSuccess(state) {
      return state.success !== null;
    },
    success(state) {
      return state.success;
    },
    hasEntities(state) {
      return state.entities.length > 0;
    },
    entities(state) {
      return state.entities;
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
      state.entities.unshift(category);
    },
    [CREATING_CATEGORY_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.entities = [];
    },
    [FETCHING_CATEGORIES](state) {
      state.isLoading = true;
      state.error = null;
      state.entities = [];
    },
    [FETCHING_CATEGORIES_SUCCESS](state, entities) {
      state.isLoading = false;
      state.error = null;
      state.entities = entities;
    },
    [FETCHING_CATEGORIES_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.entities = [];
    },
    [DELETING_ENTITY](state) {
      state.isLoading = true;
      state.error = null;
    },
    [DELETING_ENTITY_SUCCESS](state,payload) {
      state.entities =  state.entities.filter(entity => entity.id !== payload.id);
      state.isLoading = false;
      state.error = null;
      state.success = payload.response.message;
      setTimeout(() => {
        state.success = null;
      }, "3000");
    },
    [DELETING_ENTITY_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      setTimeout(() => {
        state.error = null;
      }, "3000");
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
      commit(FETCHING_CATEGORIES);
      try {
        let response = await EntityAPI.findAll(payload);
        commit(FETCHING_CATEGORIES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_CATEGORIES_ERROR, error);
        return null;
      }
    },
    async delete({ commit },payload){
      commit(DELETING_ENTITY);
      try {

        let response = await EntityAPI.delete(payload);
        commit(DELETING_ENTITY_SUCCESS, {response: response.data,id:payload.id});

        return response.data;
      } catch (error) {
        commit(DELETING_ENTITY_ERROR, error);
        return null;
      }
    }
  }
};
