import UserAPI from "../api/user";

const FETCHING_USERS = 'FETCHING_USERS',
  FETCHING_USERS_SUCCESS = 'FETCHING_USERS_SUCCESS',
  FETCHING_USERS_ERROR = 'FETCHING_USERS_ERROR',
  FETCHING_USER = 'FETCHING_USER',
  FETCHING_USER_SUCCESS = 'FETCHING_USER_SUCCESS',
  FETCHING_USER_ERROR = 'FETCHING_USER_ERROR',
  UPDATING_USER = 'UPDATING_USER',
  UPDATING_USER_SUCCESS = 'UPDATING_USER_SUCCESS',
  UPDATING_USER_ERROR = 'UPDATING_USER_ERROR',
  CREATING_USER = 'CREATING_USER',
  CREATING_USER_SUCCESS = 'CREATING_USER_SUCCESS',
  CREATING_USER_ERROR = 'CREATING_USER_ERROR';


export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    success:null,
    users: [],
    user:[]
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
    hasUsers(state) {
      return state.users.length > 0;
    },
    users(state) {
      return state.users;
    },
    user(state) {
      return state.user;
    }
  },
  mutations: {
    [FETCHING_USERS](state) {
      state.isLoading = true;
      state.error = null;
      state.users = [];
    },
    [FETCHING_USERS_SUCCESS](state, users) {
      state.isLoading = false;
      state.error = null;
      state.users = users;
    },
    [FETCHING_USERS_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.users = [];
    },
    [FETCHING_USER](state) {
      state.isLoading = true;
      state.error = null;
      state.user = [];
    },
    [FETCHING_USER_SUCCESS](state, user) {
      state.isLoading = false;
      state.error = null;
      state.user = user;
    },
    [FETCHING_USER_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.user = [];
    },
    [UPDATING_USER](state) {
      state.isLoading = true;
      state.error = null;
      state.user = [];
    },
    [UPDATING_USER_SUCCESS](state, user) {
      state.isLoading = false;
      state.error = null;
      state.user = user;
    },
    [UPDATING_USER_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.user = [];
    },
    [CREATING_USER](state) {
      state.isLoading = true;
      state.error = null;
      state.user = [];
    },
    [CREATING_USER_SUCCESS](state, response) {
      state.isLoading = false;
      state.error = null;
      state.success = response.message;
      setTimeout(() => {
        state.success = null;
      }, "3000");
    },
    [CREATING_USER_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.user = [];
    },
  },
  actions: {
    async createUser({ commit }, message) {
      commit(CREATING_USER);
      try {
        let response = await UserAPI.createUser(message);
        commit(CREATING_USER_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_USER_ERROR, error);
        return null;
      }
    },
    async findAll({ commit },payload) {
      commit(FETCHING_USERS);
      try {
        let response = await UserAPI.findAll(payload);
        commit(FETCHING_USERS_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_USERS_ERROR, error);
        return null;
      }
    },
    async findUserById({ commit },id) {
      commit(FETCHING_USER);
      try {
        let response = await UserAPI.findUserById(id);
        commit(FETCHING_USER_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_USER_ERROR, error);
        return null;
      }
    },
    async updateUser({ commit },payload){
      commit(UPDATING_USER);
      try {
        let response = await UserAPI.updateUser(payload);
        commit(UPDATING_USER_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATING_USER_ERROR, error);
        return null;
      }
    }
  }
};
