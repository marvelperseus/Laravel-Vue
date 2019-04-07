const LOGIN_URL = `/api/patients/login`;

export default {
  state: {
    user: null
  },
  mutations: {
    setUser(state, payload) {
      state.user = payload
    }
  },
  actions: {
    signUserIn({commit}, payload) {
      commit('setLoading', true)
      commit('clearError')
      return new Promise((resolve, reject) => {
        window.axios.post(LOGIN_URL, payload)
          .then(
            response => {
              commit('setLoading', false)
              const user = response['data']
              localStorage.setItem('user', JSON.stringify(user))
              commit('setUser', user)
              resolve(response)
            }
          )
          .catch(
            error => {
              commit('setLoading', false)
              commit('setUser', null)
              if (typeof (error.response.data) !== 'string') {
                commit('setError', error.response.data)
              }
              reject(error)
            }
          )
      })
    },
    updateUser({commit}, payload) {
      return new Promise((resolve, reject) => {
        window.axios
          .post('/api/patients/' + payload.id, payload)
          .then(response => {
            commit('setLoading', false);
            const user = response['data'];
            localStorage.setItem('user', JSON.stringify(user));
            commit('setUser', user);
            resolve(response);
          })
          .catch(error => {
              commit('setLoading', false)
              commit('setUser', null)
              if (typeof (error.response.data) !== 'string') {
                commit('setError', error.response.data)
              }
              reject(error)
            }
          )
      })
    }
  },
  getters: {
    user(state) {
      return state.user
    }
  }
}
