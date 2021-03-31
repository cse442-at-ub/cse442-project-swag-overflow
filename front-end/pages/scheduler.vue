<template>
  <v-app id="scheduler">

      <v-row class="fill-height">
        <v-col>
            <v-date-picker></v-date-picker>
            <v-time-picker></v-time-picker>
        </v-col>
        <v-col>
            <v-text-field label="Description"></v-text-field>
            <v-text-field label="Attendees"></v-text-field>
            <v-btn> Submit </v-btn>
        </v-col>
        <v-col>

        </v-col>
      </v-row>
  </v-app>

</template>

<script>
export default {
}

</script>
