<template>
    <v-row justify="center" align="center">
        <v-col cols="3">
            <v-card>
                <v-card-title class="headline">
                Associate #1 
                </v-card-title>
                <v-card-text>
                    <p>
                        <b>Name: </b>Adarsh Sivadas<br>
                        <b>Last worked with: </b>Today<br>
                        <b>Notes: </b><br>
                    <ul>
                        <li>Vue master</li>
                        <li>Despiser of PHP</li>
                        <li>Big fan of orange and black color scheme</li>
                    </ul>
                    </p>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="3">
            <v-card>
                <v-card-title class="headline">
                Associate #2
                </v-card-title>
                <v-card-text>
                    <p>
                        <b>Name: </b>Michael Cleversley<br>
                        <b>Last worked with: </b>Yesterday<br>
                        <b>Notes: </b><br>
                        <ul>
                            <li>Average Bootstrap enjoyer</li>
                            <li>Hates Vue with a burning passion</li>
                            <li>Loves a good bowl of cereal</li>
                        </ul>
                    </p>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="3">
            <v-card>
                <v-card-title class="headline">
                Associate #3
                </v-card-title>
                <v-card-text>
                    <b>Name: </b>Truong Pham<br>
                    <b>Last worked with: </b>4/1/2021<br>
                    <b>Notes: </b><br>
                    <ul>
                        <li>Absolute swag king</li>
                        <li>Port number changer</li>
                        <li>Dips from meetings</li>
                    </ul>
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