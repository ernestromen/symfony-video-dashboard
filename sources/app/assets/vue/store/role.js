import RoleAPI from "../api/role";

const CREATING_ROLE = "CREATING_ROLE",
  CREATING_ROLE_SUCCESS = "CREATING_ROLE_SUCCESS",
  CREATING_ROLE_ERROR = "CREATING_ROLE_ERROR",
  FETCHING_ROLES = "FETCHING_ROLES",
  FETCHING_ROLES_SUCCESS = "FETCHING_ROLES_SUCCESS",
  FETCHING_ROLES_ERROR = "FETCHING_ROLES_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    roles: []
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
    hasRole(state) {
      return state.roles.length > 0;
    },
    roles(state) {
      return state.roles;
    }
  },
  mutations: {
    [CREATING_ROLE](state) {
      state.isLoading = true;
      state.error = null;
    },
    [CREATING_ROLE_SUCCESS](state, role) {
      state.isLoading = false;
      state.error = null;
      state.roles.unshift(role);
    },
    [CREATING_ROLE_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.roles = [];
    },
    [FETCHING_ROLES](state) {
      state.isLoading = true;
      state.error = null;
      state.roles = [];
    },
    [FETCHING_ROLES_SUCCESS](state, roles) {
      state.isLoading = false;
      state.error = null;
      state.roles = roles;
    },
    [FETCHING_ROLES_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.roles = [];
    }
  },
  actions: {
    async findAll({ commit }) {
      commit(FETCHING_ROLES);
      try {
        let response = await RoleAPI.findAll();
        commit(FETCHING_ROLES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_ROLES_ERROR, error);
        return null;
      }
    }
  }
};
