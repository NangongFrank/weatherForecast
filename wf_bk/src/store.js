import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    progressPage: 0,
    progressStatus: false,
    page: 1,
    pageSize: 10,
    rowsCount: 1,
  },
  mutations: {
    setProgressPage(state, value) {
        state.progressPage = value
    },
    setProgressStatus(state, value) {
        state.progressStatus = value
    },
    setPage(state, value) {
        state.page = parseInt(value)
    },
    setPageCount(state, value) {
        state.pageCount = parseInt(value)
    },
    setRowsCount(state, value) {
        state.rowsCount = parseInt(value)
    },
  },
  actions: {
    forProgressPage({commit}, value) {
      setTimeout(() => {
        commit("setProgressPage", (value / 2) ^ 0)
      }, 100)
      setTimeout(() => {
          commit("setProgressPage", value)
      }, 200)
    }
  },
  getters: {
    getProgressPage(state) {
        return state.progressPage
    },
    getpProgressStatus(state) {
        return state.progressStatus
    },
    getRowsCount(state) {
      return state.rowsCount
    },
    getPageSize(state) {
      return state.pageSize
    },
    getPage(state) {
      return state.page
    },
  },
})
