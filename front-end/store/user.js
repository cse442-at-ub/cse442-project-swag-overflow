
export const state = () => ({
    username: ''
  })
  
export const mutations = {
    addUser(state, text) {
        state.username = text
    }
  }