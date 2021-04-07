<template>
  <v-app dark>
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
        <v-menu
          v-model="notification_panel"
          :close-on-content-click="false"
          :nudge-width="200"
          offset-y
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="warning"
              dark
              v-bind="attrs"
              v-on="on"
              class="ma-2"
            >
              Notification
            </v-btn>
          </template>


          <v-col cols="12"
            v-for="item in notifications"
            :key="item">
            <v-card
              color="warning"
            >

                <v-card-title v-text="item"></v-card-title>
            </v-card>
          </v-col>

        </v-menu>


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
      <v-alert
        :value="alert"
        type="success"
        border="top"
        dense
        dismissible
        transition="scale-transition"
        >
        Success! Please continue onto the profile page.
      </v-alert>
    </v-footer>
  </v-app>
</template>

<script>

export default {
  data () {
    return {
      notification_panel: false,
      alert: false,
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
      notifications: [...Array(4)].map((_, i) => `Item ${i}`),
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
      async signUp() {
          this.signup_dialog = false;
          this.alert = true;
          const PATH_API = '/users/register'
          await this.$axios.post(`/api/${PATH_API}`, {
            headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "OPTIONS",
            "Access-Control-Allow-Headers": "Content-Type, Authorization",
            "Access-Control-Allow-Credentials": "true",
            "CrossOrigin": "true"
            },
            data: {
              first_name: this.signup.first_name,
              last_name: this.signup.last_name,
              dob: this.signup.DoB,
              username: this.signup.user_name,
              email: this.signup.email,
              password: this.signup.password,
            }
          })
          .then(function (response) {
              this.alert = true;
              console.log(response);
          })
          .catch(function (error) {
              console.log(error);
          });
          console.log(this.signup);
      },
    async logIN() {
          this.login_dialog = false;
          this.alert = true;
          const PATH_API = '/users/signin'
          await this.$axios.post(`/api/${PATH_API}`, {
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
              console.log(response);
          })
          .catch(function (error) {
              console.log(error);
          });
          console.log(this.signup);
      }
  }
}
</script>
