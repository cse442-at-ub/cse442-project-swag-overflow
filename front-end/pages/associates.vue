<template>
  <v-app id="associates">
    <v-main>
      <v-container>
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
              hint="Try an email address!"
            ></v-text-field>
            
            <v-btn
              color="success"
              @click="sendRequest"
              block
            >Send</v-btn>
          </v-card>
        </v-row>
        <v-row
          align-content="center"
          align="center"
          justify="center">


          <v-card-title>Friend Requests From:</v-card-title>

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
                depressed
                rounded
                @click="acceptRequest(friend)"
              >
                {{ friend }}
              </v-btn>
            </v-slide-item>
          </v-slide-group>

        </v-row>
        <v-row
          align-content="center"
          align="center"
          justify="center">


          <v-card-title>Friends:</v-card-title>

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
                depressed
                rounded
              >
                {{ friend }}
              </v-btn>
            </v-slide-item>
          </v-slide-group>

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
      friendsList: ['Truong', 'Mike', 'Adarsh'],
      friendsRequests: ['Pham', 'Clev', 'Siva']
    }
  },
  methods: {
    async sendRequest() {
      var self = this;
      const PATH_API = 'friends/wantfriend.php'
      // DEPLOYED_URL = '/CSE442-542/2021-Spring/cse-442m/back-end/user/signin.php'
      // LOCAL_URL = 'localhost/user/signin.php'
      await this.$axios.post('/CSE442-542/2021-Spring/cse-442m/back-end/friends/wantfriend.php', {
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
      const PATH_API = 'friends/newfriend.php'
      // DEPLOYED_URL = '/CSE442-542/2021-Spring/cse-442m/back-end/user/signin.php'
      // LOCAL_URL = 'localhost/user/signin.php'
      await this.$axios.post('/CSE442-542/2021-Spring/cse-442m/back-end/friends/newfriend.php', {
        headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "OPTIONS",
        "Access-Control-Allow-Headers": "Content-Type, Authorization",
        "Access-Control-Allow-Credentials": "true",
        "CrossOrigin": "true"
        },
        data: {
          username: text
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
    }
  },
  computed: {
    friends () {
      // return this.$store.state.user.friends
    }
  }
}
</script>
