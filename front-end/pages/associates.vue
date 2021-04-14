<template>
  <v-app id="associates">
    <v-main>
      <v-container>
        <v-row>
          <v-col
            cols="4"
          >
            <v-card class="mx-auto"
            >
              <v-card-text>
                <div>Team: Swag Overflow</div>
                <p class="display-1 text--primary">
                  Adarsh Sivadas
                </p>
                <p>Last worked with: 04/14/21</p>
                <div class="text--primary">
                  About:<br>
                  -- Vue master <br>
                  -- Hates PHP <br>
                  -- Big fan of orange and black color scheme <br>
                </div>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  text
                  color="accent"
                >
                  Befriend me
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <v-col
            cols="4"
          >
            <v-card class="mx-auto"
            >
              <v-card-text>
                <div>Team: Swag Overflow</div>
                <p class="display-1 text--primary">
                  Michael Cleversley
                </p>
                <p>Last worked with: 04/14/21</p>
                <div class="text--primary">
                  About:<br>
                  -- PHP master <br>
                  -- Hates Vue <br>
                  -- Likes to gucci <br>
                </div>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  text
                  color="accent"
                >
                  Befriend me
                </v-btn>
              </v-card-actions>

            </v-card>
          </v-col>

          <v-col
            cols="4"
          >
            <v-card class="mx-auto">
              <v-card-text>
                <div>Team: Swag Overflow</div>
                <p class="display-1 text--primary">
                  Truong Pham
                </p>
                <p>Last worked with: 04/14/21</p>
                <div class="text--primary">
                  About: <br>
                  -- Absolute Swag King <br>
                  -- First person to coin the term "Swag Overflow" <br>
                  -- Port number changer <br>
                </div>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  text
                  color="accent"
                >
                  Befriend me
                </v-btn>
              </v-card-actions>

            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
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
export default {
//
}
</script>
