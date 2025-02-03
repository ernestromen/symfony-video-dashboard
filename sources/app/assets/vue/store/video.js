import VideoAPI from "../api/video";

const FETCHING_VIDEO = "FETCHING_USER",
  FETCHING_VIDEO_SUCCESS = "FETCHING_USER_SUCCESS",
  FETCHING_VIDEO_ERROR = "FETCHING_USER_ERROR",
  CREATING_VIDEO = "CREATING_VIDEO",
  CREATING_VIDEO_SUCCESS = "CREATING_VIDEO_SUCCESS",
  CREATING_VIDEO_ERROR = "CREATING_VIDEO_ERROR",
  UPDATING_VIDEO = 'UPDATING_USER',
  UPDATING_VIDEO_SUCCESS = 'UPDATING_USER_SUCCESS',
  UPDATING_VIDEO_ERROR = 'UPDATING_USER_ERROR';

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    success:null,
    video:[],
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
    video(state) {
      return state.video;
    }
  },
  mutations: {
    [FETCHING_VIDEO](state) {
      state.isLoading = true;
      state.error = null;
      state.video = [];
    },
    [FETCHING_VIDEO_SUCCESS](state, video) {
      state.isLoading = false;
      state.error = null;
      state.video = video;
    },
    [FETCHING_VIDEO_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.video = [];
    },
    [CREATING_VIDEO](state){
      state.isLoading = true;
      state.error = null;
    },
    [CREATING_VIDEO_SUCCESS](state, video) {
      state.isLoading = false;
      state.error = null;
      state.video = video,video;
      state.success = video.message;
      setTimeout(() => {
        state.success = null;
      }, "3000");
    },
    [CREATING_VIDEO_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.video = [];
    },
    [UPDATING_VIDEO](state) {
      state.isLoading = true;
      state.error = null;
      state.video = [];
    },
    [UPDATING_VIDEO_SUCCESS](state, video) {
      state.isLoading = false;
      state.error = null;
      state.video = video.video;
      state.success = video.message;
      setTimeout(() => {
        state.success = null;
      }, "3000");
      

    },
    [UPDATING_VIDEO_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.video = [];
    },
  },
  actions: {
    async createVideo({ commit }, payload) {
      commit(CREATING_VIDEO);
      try {
        let response = await VideoAPI.createVideo(payload);
        commit(CREATING_VIDEO_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_VIDEO_ERROR, error);
        return null;
      }
    },
    async findVideoById({ commit },id) {
      commit(FETCHING_VIDEO);
      try {
        let response = await VideoAPI.findVideoById(id);
        commit(FETCHING_VIDEO_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_VIDEO_ERROR, error);
        return null;
      }
    },

    async updateVideo({ commit },payload){
      commit(UPDATING_VIDEO);
      try {
        let response = await VideoAPI.updateVideo(payload);
        commit(UPDATING_VIDEO_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATING_VIDEO_ERROR, error);
        return null;
      }
    }
  }
};
