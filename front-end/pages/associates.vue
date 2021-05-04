<template>
  <v-app id="associates">
    <v-main>
      <v-container>
        
        <v-row><v-card-title></v-card-title></v-row>
        <v-row
          align-content="center"
          align="center"
          justify="center">
          <v-card
            elevation="10"
            max-width="40%"
            >

            <v-card-title class="justify-center">Friend Requests</v-card-title>

            <v-slide-group
                show-arrows
              >
              <v-slide-item
                v-for="friend in friendsRequests"
                :key="friend.id"
                v-slot="{ active }"
              >
                <v-btn
                  class="mx-2"
                  :input-value="active"
                  active-class="purple white--text"
                  color="#E5E5E5"
                  depressed
                  rounded
                  light
                  @click="acceptRequest(friend)"
                >
                  {{ friend }}
                </v-btn>
              </v-slide-item>
            </v-slide-group>
            <v-card-title></v-card-title>
          </v-card>
          <v-card-title></v-card-title>
          <v-card
            elevation="10"
            max-width="40%"
            >

            <v-card-title class="justify-center">Your Friends</v-card-title>

            <v-slide-group
                show-arrows
              >
              <v-slide-item
                v-for="friend in friendsList"
                :key="'A' + friend.id"
              >
                <v-btn
                  class="mx-2"
                  active-class="purple white--text"
                  color="#E5E5E5"
                  depressed
                  rounded
                  light
                >
                  {{ friend }}
                </v-btn>
              </v-slide-item>
            </v-slide-group>
            <v-card-title></v-card-title>
          </v-card>
        </v-row>
        <v-row><v-card-title></v-card-title></v-row>
        <v-row
          align-content="center"
          align="center"
          justify="center">
          <v-card
            elevation="10"
            width="40%"
            >
            <v-btn
              color="#E5E5E5"
              @click="sendRequest"
              block
              light
            >REFRESH</v-btn>
          </v-card>
        </v-row>
        <v-row><v-card-title></v-card-title></v-row>
        <v-row><v-card-title></v-card-title></v-row>
        <v-row><v-divider></v-divider></v-row>
        <v-row><v-card-title></v-card-title></v-row>
        <v-row><v-card-title></v-card-title></v-row>

        <v-row
          align-content="center"
          align="center"
          justify="center"
        >
          <v-card
            elevation="10"
            width="40%"
            >
            <v-text-field
              v-model="friendName"
              label="Who would you like to befriend?"
              dense
              clearable
              filled
              hint="Try a username!"
            ></v-text-field>
            
            <v-btn
              color="#FCA311"
              @click="sendRequest"
              block
            >Send</v-btn>
          </v-card>
        </v-row>

      </v-container>
      <notifications group="befriend"/>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data () {
    return {
      friendName: '',
      friendsList: ['Mike', 'Truong', 'Adarsh', 'Random'],
      friendsRequests: ['Mike', 'Truong', 'Adarsh', 'Random']
    }
  },
  methods: {
    async sendRequest() {
      var self = this;
      await this.$axios.post('/friend/request.php', {
        headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "OPTIONS",
        "Access-Control-Allow-Headers": "Content-Type, Authorization",
        "Access-Control-Allow-Credentials": "true",
        "CrossOrigin": "true"
        },
        data: {
          username: this.friendName,
          source: this.$store.state.user.username
        }
      })
      .then(function (response) {
          self.befriendMessage(true, text)
      })
      .catch(function (error) {
        console.log(error.response)
        var headers = error.response.headers
        console.log(headers)
        if (headers.hasOwnProperty('message')) {
          if (headers['message'] === 'error-dnm') {
            self.befriendMessage(false, 'error-dnm')
          } else {
            self.befriendMessage(false, 'error-nm')
          }
        } else {
          self.befriendMessage(false, 'error')
        }
      });
    },
    async acceptRequest( text ) {
      var self = this;
      await this.$axios.post('/friend/accept.php', {
        headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "OPTIONS",
        "Access-Control-Allow-Headers": "Content-Type, Authorization",
        "Access-Control-Allow-Credentials": "true",
        "CrossOrigin": "true"
        },
        data: {
          username: text,
          source: this.$store.state.user.username
        }
      })
      .then(function (response) {
          self.newFriendMessage(true, text)
          this.friendsRequests = this.friendsRequests.filter(item => item !== text)
          this.friendsList.push(text)
      })
      .catch(function (error) {
        console.log(error.response)
        var headers = error.response.headers
        console.log(headers)
        if (headers.hasOwnProperty('message')) {
          if (headers['message'] === 'error-dnm') {
            self.newFriendMessage(false, 'error-dnm')
          } else {
            self.newFriendMessage(false, 'error-nm')
          }
        } else {
          self.newFriendMessage(false, 'error')
        }
      });
    },
    befriendMessage(success, name) {
      if (success) {
        var str1 = "You've sent " + name + " a friend request!";
        this.$notify({
          'group': 'befriend',
          'title': str1,
          'text': 'Haha now that is swag overflow!'
          })
      }
      else {
        if (name == 'error-dnm') {
          this.$notify({
          'group': 'befriend',
          'title': 'Uh oh. Something went wrong!',
          'text': 'That user does not exist.'
          })
        } else {
          this.$notify({
          'group': 'befriend',
          'title': 'Uh oh. Something went wrong!',
          'text': 'Please try again.'
          })
        }
      }
    },
    newFriendMessage(success, name) {
      if (success) {
        var str1 = "You're now friends with " + name + "!";
        this.$notify({
          'group': 'befriend',
          'title': str1,
          'text': 'Haha now that is swag overflow!'
          })
      }
      else {
        this.$notify({
          'group': 'befriend',
          'title': 'Uh oh. Something went wrong!',
          'text': 'Please try again.'
          })
      }
    },
    async refresh() {
      var self = this;

      // Get all friend requests
      await this.$axios.post('/friend/read.php', {
        headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "OPTIONS",
        "Access-Control-Allow-Headers": "Content-Type, Authorization",
        "Access-Control-Allow-Credentials": "true",
        "CrossOrigin": "true"
        },
        data: {
          username: this.$store.state.user.username,
          request: true,
          friend: false
        }
      })
      .then(function (response) {
          self.friendsRequests = response.body
      })
      .catch(function (error) {
        console.log(error.response)
      });

      // Get all friends
      await this.$axios.post('/friend/read.php', {
        headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "OPTIONS",
        "Access-Control-Allow-Headers": "Content-Type, Authorization",
        "Access-Control-Allow-Credentials": "true",
        "CrossOrigin": "true"
        },
        data: {
          username: this.$store.state.user.username,
          request: false,
          friend: true
        }
      })
      .then(function (response) {
          self.friendsList = response.body
      })
      .catch(function (error) {
        console.log(error.response)
      });
    }
  },
}
</script>
