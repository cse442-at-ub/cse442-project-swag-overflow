<template>
  <v-app id="scheduler">

      <v-row class="fill-height">
        <v-col>
            <v-date-picker></v-date-picker>
            <v-time-picker></v-time-picker>
        </v-col>
        <v-col>
            <v-text-field label="Description"></v-text-field>
            <v-autocomplete
            label="Attendees"
            v-model="selectedFriends"
            :items="friendsList"
            multiple>
            </v-autocomplete>
            <v-btn> Submit </v-btn>
        </v-col>
        <v-col>

        </v-col>
      </v-row>
  </v-app>

</template>

<script>

export default {
  data: () => ({
    friendsList: [],
    selectedFriends: []
  }),
  mounted () {
    this.friendsList = this.$store.state.user.friends
    // Remove this soon
    // this.friendsList.push("Truong")
    // this.friendsList.push("Adarsh")
    // this.friendsList.push("Mike")
  }
}

</script>