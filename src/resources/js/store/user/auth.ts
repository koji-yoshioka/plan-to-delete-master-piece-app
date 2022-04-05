import { InjectionKey } from 'vue';
import { createStore, useStore as baseUseStore, Store } from 'vuex';
import axios from 'axios';

interface User {
  name: string;
  email: string;
  created_at: string;
  updated_at: string;
}

interface State {
  user: User | null;
}

export const key: InjectionKey<Store<State>> = Symbol();

export const auth = createStore<State>({
  state: {
    user: null,
  },
  getters: {
    getLoginUser: (state) => {
      return state.user ? state.user : null;
    }
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    }
  },
  actions: {
    async signUp({ commit }, data) {
      const response = await axios.post('/api/user/sign-up', data);
      commit('setUser', response.data);
    },
    async login({ commit }, data) {
      const response = await axios.post('/api/user/login', data);
      commit('setUser', response.data);
    }
  }
})

export const useStore = () => {
  return baseUseStore(key);
}
