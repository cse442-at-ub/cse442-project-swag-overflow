<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <v-img
        src="1ad.png"
        center
        max-height="200px"
      ></v-img>
      <div class="text-center">
      </div>
      <v-card>
        <v-card-title class="headline">
          Welcome to SwagOverflow!
        </v-card-title>
        <v-card-text>
          <p>SwagOverflow is a simple calendar applications, capable of many powerful things! It was designed to help you manage your time and make you more productive.</p>
          <p>
            This is an open source project! We are building our documentation. Feel free to chime in @  <a
              href="mailto: adarshsi@buffalo.edu"
              target="_blank"
              rel="noopener noreferrer"
            >
              adarshsi@buffalo.edu
            </a>.
          </p>

          <p>
            [PROFILE] == Your personal hub for all things calendar
            <br>
            [SCHEDULER] == You can schedule meetings, discuss plans and projects!
            <br>
            [UPDATES] == TBD: Stay tuned!
          </p>

          <p>
            Find a bug? Report it on the github <a
              href="https://github.com/cse442-spring-2020-offering/cse442-project-swag-overflow/issues"
              target="_blank"
              rel="noopener noreferrer"
              title="contribute"
            >
              issue board
            </a>.
          </p>
          <p>Want to maximize your lifestyle? Click on the Sign up button now!</p>
          <div class="text-xs-right">
            <em><small>&mdash; Michael Cleversley</small></em>
            <em><small>, Adarsh Sivadas</small></em>
            <em><small>, Truong Pham</small></em>
          </div>
          <hr class="my-3">
          <p
          >
            Made with ❤️ from SwagOverflow
          </p>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import Logo from '~/components/Logo.vue'
import VuetifyLogo from '~/components/VuetifyLogo.vue'

export default {
  components: {
    Logo,
    VuetifyLogo
  }
}
</script>
