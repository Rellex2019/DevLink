import { createStore } from 'vuex'

import authStore from './store'

export default createStore({
  modules: {
    authStore: authStore
  }
})
