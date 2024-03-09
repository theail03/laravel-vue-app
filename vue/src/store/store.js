import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
  state: {
    user: {
      data: {},
    },
    matricesDashboard: {
      loading: false,
      data: {}
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
  getters: {
    isAuthenticated(state) {
      return Object.keys(state.user.data).length > 0;
    },
  },
  actions: {

    logout({commit}) {
      return axiosClient.post('/logout')
        .then(response => {
          commit('logout');
          return response;
        })
    },
    getUser({commit}) {
      return axiosClient.get('/user')
      .then(res => {
        commit('setUser', res.data);
      })
    },
    getMatricesDashboardData({commit}) {
      commit('setMatricesDashboardLoading', true)
      return axiosClient.get(`/matrices/dashboard`)
      .then((res) => {
        commit('setMatricesDashboardLoading', false);
        commit('setMatricesDashboardData', res.data);
        return res;
      })
      .catch(error => {
        commit('setMatricesDashboardLoading', false);
        return error;
      })
    },
    getMatrices({ commit }, options = {}) {
      commit('setMatricesLoading', true);
    
      // Initialize the base URL
      let baseUrl = options.url || "/matrix";
      let queryParams = new URLSearchParams();
    
      // Check if the base URL already contains query parameters
      const hasExistingParams = baseUrl.includes('?');
      if (hasExistingParams) {
        // Extract existing query parameters
        const [path, existingParams] = baseUrl.split('?');
        baseUrl = path;
        // Add existing parameters to queryParams
        new URLSearchParams(existingParams).forEach((value, key) => {
          queryParams.set(key, value);
        });
      }
    
      // Append additional options as query parameters, except 'url'
      Object.entries(options).forEach(([key, value]) => {
        if (key !== 'url') {
          queryParams.set(key, value); // Use set to update or append parameters
        }
      });
    
      // Construct the full URL with all query parameters
      let finalUrl = baseUrl + '?' + queryParams.toString();
    
      // Remove the last character if it is a '?' or '&'
      finalUrl = finalUrl.replace(/[?&]$/, '');
    
      // Make the API call using the constructed URL
      return axiosClient.get(finalUrl).then((res) => {
        commit('setMatrices', res.data);
        commit('setMatricesLoading', false);
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
            commit('setCurrentMatrix', res.data);
            return res;
          });
      } else {
        response = axiosClient.post("/matrix", matrix).then((res) => {
          commit('setCurrentMatrix', res.data);
          return res;
        });
      }

      return response;
    },
    deleteMatrix({ dispatch }, id) {
      return axiosClient.delete(`/matrix/${id}`).then((res) => {
        dispatch('getMatrices');
        return res;
      });
    },

    getImages({ commit }, matrixId) {
      commit('setImagesLoading', true)
      return axiosClient.get(`/matrix/${matrixId}/images`).then((res) => {
        commit('setImagesLoading', false);
        commit("setImages", res.data);
        return res;
      });
    },
    saveImage({ commit, dispatch }, { matrixId, row, column, data }) {
      return axiosClient
        .put(`/matrix/${matrixId}/image/${row}/${column}`, { data: data })
        .then((res) => {
          dispatch('getImages', matrixId);
          return res;
        });
    },
    deleteImage({ dispatch }, { matrixId, row, column }) {
      return axiosClient.delete(`/matrix/${matrixId}/image/${row}/${column}`).then((res) => {
        dispatch('getImages', matrixId);
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
    setMatricesDashboardLoading: (state, loading) => {
      state.matricesDashboard.loading = loading;
    },
    setMatricesDashboardData: (state, data) => {
      state.matricesDashboard.data = data
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
