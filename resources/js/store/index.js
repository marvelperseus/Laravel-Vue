import Vue from 'vue'
import Vuex from 'vuex'

import shared from './shared'
import auth from './auth'

Vue.use(Vuex)

export const store = new Vuex.Store({
  modules: {
    auth: auth,
    shared: shared
  }
});
