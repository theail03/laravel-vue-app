import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
  state: {
    user: {
      data: {},
    },
    dashboard: {
      loading: false,
      data: {}
    },
    surveys: {
      loading: false,
      links: [],
      data: []
    },
    currentSurvey: {
      data: {},
      loading: false,
    },
    matrices: {
      loading: false,
      links: [],
      data: []
    },
    currentMatrix: {
      data: {},
      loading: false,
    },
    images: {
      loading: false,
      links: [],
      data: []
    },
    questionTypes: ["text", "select", "radio", "checkbox", "textarea"],
    notification: {
      show: false,
      type: 'success',
      message: ''
    }
  },
  getters: {},
  actions: {

    logout({commit}) {
      return axiosClient.post('/logout')
        .then(response => {
          commit('logout')
          return response;
        })
    },
    getUser({commit}) {
      return axiosClient.get('/user')
      .then(res => {
        commit('setUser', res.data)
      })
    },
    getDashboardData({commit}) {
      commit('dashboardLoading', true)
      return axiosClient.get(`/dashboard`)
      .then((res) => {
        commit('dashboardLoading', false)
        commit('setDashboardData', res.data)
        return res;
      })
      .catch(error => {
        commit('dashboardLoading', false)
        return error;
      })

    },
    getSurveys({ commit }, {url = null} = {}) {
      commit('setSurveysLoading', true)
      url = url || "/survey";
      return axiosClient.get(url).then((res) => {
        commit('setSurveysLoading', false)
        commit("setSurveys", res.data);
        return res;
      });
    },
    getSurvey({ commit }, id) {
      commit("setCurrentSurveyLoading", true);
      return axiosClient
        .get(`/survey/${id}`)
        .then((res) => {
          commit("setCurrentSurvey", res.data);
          commit("setCurrentSurveyLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentSurveyLoading", false);
          throw err;
        });
    },
    getSurveyBySlug({ commit }, slug) {
      commit("setCurrentSurveyLoading", true);
      return axiosClient
        .get(`/survey-by-slug/${slug}`)
        .then((res) => {
          commit("setCurrentSurvey", res.data);
          commit("setCurrentSurveyLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentSurveyLoading", false);
          throw err;
        });
    },
    saveSurvey({ commit, dispatch }, survey) {
      delete survey.image_url;

      let response;
      if (survey.id) {
        response = axiosClient
          .put(`/survey/${survey.id}`, survey)
          .then((res) => {
            commit('setCurrentSurvey', res.data)
            return res;
          });
      } else {
        response = axiosClient.post("/survey", survey).then((res) => {
          commit('setCurrentSurvey', res.data)
          return res;
        });
      }

      return response;
    },
    deleteSurvey({ dispatch }, id) {
      return axiosClient.delete(`/survey/${id}`).then((res) => {
        dispatch('getSurveys')
        return res;
      });
    },
    saveSurveyAnswer({commit}, {surveyId, answers}) {
      return axiosClient.post(`/survey/${surveyId}/answer`, {answers});
    },
    
    getMatrices({ commit }, {url = null} = {}) {
      commit('setMatricesLoading', true)
      url = url || "/matrix";
      return axiosClient.get(url).then((res) => {
        commit('setMatricesLoading', false)
        commit("setMatrices", res.data);
        return res;
      });
    },
    getMatrix({ commit }, id) {
      commit("setCurrentMatrixLoading", true);
      return axiosClient
        .get(`/matrix/${id}`)
        .then((res) => {
          commit("setCurrentMatrix", res.data);
          commit("setCurrentMatrixLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentMatrixLoading", false);
          throw err;
        });
    },
    getMatrixBySlug({ commit }, slug) {
      commit("setCurrentMatrixLoading", true);
      return axiosClient
        .get(`/matrix-by-slug/${slug}`)
        .then((res) => {
          commit("setCurrentMatrix", res.data);
          commit("setCurrentMatrixLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentMatrixLoading", false);
          throw err;
        });
    },
    saveMatrix({ commit, dispatch }, matrix) {
      let response;
      if (matrix.id) {
        response = axiosClient
          .put(`/matrix/${matrix.id}`, matrix)
          .then((res) => {
            commit('setCurrentMatrix', res.data)
            return res;
          });
      } else {
        response = axiosClient.post("/matrix", matrix).then((res) => {
          commit('setCurrentMatrix', res.data)
          return res;
        });
      }

      return response;
    },
    deleteMatrix({ dispatch }, id) {
      return axiosClient.delete(`/matrix/${id}`).then((res) => {
        dispatch('getMatrices')
        return res;
      });
    },

    getImages({ commit }, matrixId) {
      commit('setImagesLoading', true)
      return axiosClient.get(`/matrix/${matrixId}/images`).then((res) => {
        commit('setImagesLoading', false)
        commit("setImages", res.data);
        return res;
      });
    },
    saveImage({ commit, dispatch }, image) {
      return axiosClient
        .put(`/matrix/${image.matrixId}/image/${image.row}/${image.column}`, { data: image.data })
        .then((res) => {
          commit('setCurrentImage', res.data)
          return res;
        });
    },
    deleteImage({ dispatch }, matrixId, row, column) {
      return axiosClient.delete(`/matrix/${matrixId}/image/${row}/${column}`).then((res) => {
        dispatch('getImages')
        return res;
      });
    },
  },
  mutations: {
    logout: (state) => {
      state.user.data = {};
    },

    setUser: (state, user) => {
      state.user.data = user;
    },
    dashboardLoading: (state, loading) => {
      state.dashboard.loading = loading;
    },
    setDashboardData: (state, data) => {
      state.dashboard.data = data
    },
    setSurveysLoading: (state, loading) => {
      state.surveys.loading = loading;
    },
    setSurveys: (state, surveys) => {
      state.surveys.links = surveys.meta.links;
      state.surveys.data = surveys.data;
    },
    setCurrentSurveyLoading: (state, loading) => {
      state.currentSurvey.loading = loading;
    },
    setCurrentSurvey: (state, survey) => {
      state.currentSurvey.data = survey.data;
    },

    setMatricesLoading: (state, loading) => {
      state.matrices.loading = loading;
    },
    setMatrices: (state, matrices) => {
      state.matrices.links = matrices.meta.links;
      state.matrices.data = matrices.data;
    },
    setCurrentMatrixLoading: (state, loading) => {
      state.currentMatrix.loading = loading;
    },
    setCurrentMatrix: (state, matrix) => {
      state.currentMatrix.data = matrix.data;
    },

    setImagesLoading: (state, loading) => {
      state.images.loading = loading;
    },
    setImages: (state, images) => {
      state.images.data = images.data;
    },
    
    notify: (state, {message, type}) => {
      state.notification.show = true;
      state.notification.type = type;
      state.notification.message = message;
      setTimeout(() => {
        state.notification.show = false;
      }, 3000)
    },
  },
  modules: {},
});

export default store;
