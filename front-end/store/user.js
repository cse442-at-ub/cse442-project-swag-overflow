
export const state = () => ({
    username: '',
    token: '',
    friends: []
  })
  
export const mutations = {
    addUser(state, text, tokenReceived, friendsList) {
        state.username = text
        state.token = tokenReceived
        state.friends = friendsList
    }
  }