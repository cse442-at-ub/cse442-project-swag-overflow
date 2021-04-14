<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="notification_panel"
      absolute
      temporary
      right
      :width="500"
    >
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title>Your Notification Panel</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list two-line>
        <v-list-item-group
          v-model="notification_panel"
          active-class="pink--text"
          multiple
        >
          <template v-for="(item, index) in notifications">
            <v-list-item :key="item.id">
              <template v-slot:default="{ active }">
                <v-list-item-content>
                  <v-list-item-title v-text="item.title"></v-list-item-title>

                  <v-list-item-subtitle
                    class="text--primary"
                    v-text="item.headline"
                  ></v-list-item-subtitle>

                  <v-list-item-subtitle v-text="item.subtitle"></v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-action>
                  <v-list-item-action-text v-text="item.action"></v-list-item-action-text>

                  <v-icon
                    color="error"
                    @click="removeNotification(item.id)"
                  >
                    mdi-delete
                  </v-icon>
                </v-list-item-action>
              </template>
            </v-list-item>

            <v-divider
              v-if="index < notifications.length - 1"
              :key="index"
            ></v-divider>
          </template>
        </v-list-item-group>
      </v-list>

    </v-navigation-drawer>

    <v-app-bar
      app
    >
      <v-container class="py-0 fill-height">
        <v-avatar
          class="mr-10"
          size="40"
        >
          <img
            src="logo.png"
            alt="John"
          >
        </v-avatar>

        <v-btn
          v-for="(link, i) in links"
          :key="i"
          :to="link.to"
          text
        >
          {{ link.title }}
        </v-btn>

        <v-spacer></v-spacer>
        <!-- Notification Panel !-->
        <v-btn
          color="warning"
          dark
          v-bind="attrs"
          v-on="on"
          class="ma-2"
          @click.stop = "notification_panel = !notification_panel"
        >
          <v-icon dark>
            mdi-bell
          </v-icon>
        </v-btn>


        <!-- Sign up form !-->
        <v-dialog
          v-model="signup_dialog"
          max-width="550px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              v-bind="attrs"
              v-on="on"
              class="ma-2"
              outlined
            >
              Sign-up
            </v-btn>
          </template>
          <v-form v-model="form" @submit.prevent>
          <v-card>
            <v-card-title>
              <span class="headline">Welcome new user!</span>
            </v-card-title>
            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <v-text-field
                      v-model="signup.first_name"
                      label= "First Name"
                      :rules = "[rules.required]"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <v-text-field
                      v-model="signup.last_name"
                      label= "Last Name"
                      :rules = "[rules.required]"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12">
                    <v-menu
                      v-model="menu"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="auto"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="signup.DoB"
                          label="Date of birth"
                          :rules = "[rules.required]"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="signup.DoB"
                        @input="menu = false"
                      >
                      </v-date-picker>
                    </v-menu>
                  </v-col>

                  <v-col cols="12">
                    <v-text-field
                      v-model="signup.user_name"
                      label="Username"
                      :rules = "[rules.required, rules.length(6)]"
                      counter
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      v-model="signup.email"
                      label="Email"
                      :rules = "[rules.required, rules.email]"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      v-model="signup.password"
                      label="Password"
                      :append-icon="sneak ? 'mdi-eye' : 'mdi-eye-off'"
                      :type="sneak ? 'text' : 'password'"
                      :rules = "[rules.required, rules.password, rules.length(8)]"
                      counter
                      @click:append="sneak = !sneak"
                    ></v-text-field>
                  </v-col>

                </v-row>
              </v-container>

            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                text
                @click="signup_dialog = false"
              >
                Close
              </v-btn>
              <v-btn
                :disabled = "!form"
                :loading="isLoading"
                color="success"
                text
                @click="signUp"
                depressed
              >
                Submit
              </v-btn>
            </v-card-actions>
          </v-card>
          </v-form>
        </v-dialog>

        <!-- Log in form !-->
        <v-dialog
          v-model="login_dialog"
          max-width="550px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="accent"
              dark
              v-bind="attrs"
              v-on="on"
              class="ma-2"
            >
              Log in
            </v-btn>
          </template>
          <v-form v-model="form">
            <v-card>
              <v-card-title>
                <span class="headline">Welcome back!</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>

                    <v-col cols="12">
                      <v-text-field
                        v-model="login.user_name"
                        outlined
                        label="Username"
                        :rules = "[rules.required]"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12">
                      <v-text-field
                        v-model="login.password"
                        outlined
                        label="Password"
                        :rules = "[rules.required]"
                        :append-icon="sneak ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="sneak ? 'text' : 'password'"
                        @click:append="sneak = !sneak"
                      ></v-text-field>
                    </v-col>

                  </v-row>
                </v-container>

              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="blue darken-1"
                  text
                  @click="login_dialog = false"
                >
                  Close
                </v-btn>
                <v-btn
                  :disabled = "!form"
                  :loading="isLoading"
                  color="success"
                  text
                  @click="logIN"
                  depressed
                >
                  Log in
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-form>
        </v-dialog>
      </v-container>

    </v-app-bar>
    <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>


    <v-footer
      :absolute="!fixed"
      app
    >
      <span>&copy; {{ new Date().getFullYear() }} - A Swag Overflow Project</span>
    </v-footer>
    <notifications group="login"/>
  </v-app>
</template>

<script>

export default {
  data () {
    return {
      notification_panel: false,
      show: true,
      sneak: false,
      form: false,
      menu: false,
      clipped: false,
      drawer: false,
      signup_dialog: false,
      login_dialog: false,
      fixed: false,
      isLoading: false,
      links: [
          {
              title: 'Dashboard',
              to: '/'
          },
          {
              title: 'Profile',
              to: '/profile'
          },
          {
              title: 'Scheduler',
              to: '/scheduler'
          },
          {
              title: 'Updates',
              to: '/updates'
          },
          {
            title: 'Associates',
            to: '/associates'
          },
      ],
      signup: {
          first_name: '',
          last_name: '',
          user_name: '',
          password: '',
          email: '',
          DoB: null
      },
      default_signup: {
          first_name: '',
          last_name: '',
          user_name: '',
          password: '',
          email: '',
          DoB: null,
      },
      login: {
          user_name: '',
          password: '',
      },
      default_login: {
          user_name: '',
          password: '',
      },
      notifications: [
            {
                id: 0,
                action: '15 min',
                headline: '20% of the grade!',
                subtitle: `Make sure the meeting goes well.`,
                title: 'CSE 442 Sprint #3',
            },
            {
                id: 1,
                action: '3 hr',
                headline: 'Zoom proctored exam',
                subtitle: `Review section 4.2 and section 4.3`,
                title: 'MTH 309 Exam #2',
            },
            {
                id: 2,
                action: '6 hr',
                headline: 'Log into adco',
                subtitle: `Ask for receipt this time`,
                title: 'Pay rent',
            },
            {
                id: 3,
                action: '12 hr',
                headline: 'LA Fitness',
                subtitle: 'All chest no legs',
                title: 'Gym time',
            },
            {
                id: 4,
                action: '18hr',
                headline: 'Recipe to try',
                subtitle: 'We should eat this: Cheese, Chicken, Sour cream, and tomatoes.',
                title: 'Lunch',
            },
          ],
      rules: {
          email: v => !!(v || '').match(/@/) || 'Please enter a valid email',
          length: len => v => (v || '').length >= len || `Invalid character length, required ${len}`,
          password: v => !!(v || '').match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/) ||
              'Password must contain an upper case letter, a numeric character, and a special character',
          required: v => !!v || 'This field is required'
      },
      miniVariant: false,
      title: 'SwagOverflow'
    }
  },

  watch: {
  },

  methods: {
      removeNotification(id) {
          this.notifications = this.notifications.filter(function( obj ) {
              return obj.id !== id;
          });
      },
      loginNotification(name, success) {
        if (success) {
          var str1 = "Hello " + name + ".";
          this.$notify({
            'group': 'login',
            'title': str1,
            'text': 'Welcome to Swag Overflow!'
            })
        }
        else {
          if (name == 'error-dnm') {
            this.$notify({
            'group': 'login',
            'title': 'Uh oh. Something went wrong!',
            'text': 'Password does not match. Please try again.'
            })
          } else if (name == 'error-nm') {
            this.$notify({
            'group': 'login',
            'title': 'Uh oh. Something went wrong!',
            'text': 'User does not exist. Please try again.'
            })
          } else {
            this.$notify({
            'group': 'login',
            'title': 'Uh oh. Something went wrong!',
            'text': 'Please try again.'
            })
          }
        }
      },
      async signUp() {
          this.signup_dialog = false;
          const PATH_API = 'user/register.php'
          var self = this;
          await this.$axios.post(`/CSE442-542/2021-Spring/cse-442m/back-end/register.php`, {
            headers: {
            "Content-Type": "application/json",
            "Accept": "*/*",
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "OPTIONS",
            "Access-Control-Allow-Headers": "Content-Type, Authorization",
            "Access-Control-Allow-Credentials": "true",
            "CrossOrigin": "true"
            },
            data: {
              firstname: this.signup.first_name,
              lastname: this.signup.last_name,
              dob: this.signup.DoB,
              username: this.signup.user_name,
              email: this.signup.email,
              password: this.signup.password,
            }

          })
          // console.log(data)
          .then(function (response) {
              console.log(response)
              var headers = response.headers
              self.loginNotification(headers['firstname'], true)
          })
          .catch(function (error) {
              console.log(error)
              self.loginNotification('error', false)
          });
          console.log(this.signup);
      },
    async logIN() {
          this.login_dialog = false;
          var self = this;
          const PATH_API = 'user/signin.php'
          await this.$axios.post(`/CSE442-542/2021-Spring/cse-442m/back-end/signin.php`, {
            headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "OPTIONS",
            "Access-Control-Allow-Headers": "Content-Type, Authorization",
            "Access-Control-Allow-Credentials": "true",
            "CrossOrigin": "true"
            },
            data: {
              username: this.login.user_name,
              password: this.login.password
            }
          })
          .then(function (response) {
              var headers = response.headers
              self.loginNotification(headers['firstname'], true)
          })
          .catch(function (error) {
            console.log(error.response)
            var headers = error.response.headers
            console.log(headers)
            if (headers.hasOwnProperty('message')) {
              if (headers['message'] === 'error-dnm') {
                self.loginNotification('error-dnm', false)
              } else {
                self.loginNotification('error-nm', false)
              }
            } else {
              self.loginNotification('error', false)
            }
          });
      }
  }
}
</script>
