<template>
  <v-app id="profile">

    <v-row class="fill-height">
      <v-col>
        <v-sheet height="64">
          <v-toolbar
            flat
          >
            <v-btn
              outlined
              class="mr-4"
              color="accent"
              @click="setToday"
            >
              Today
            </v-btn>
            <v-btn
              fab
              text
              small
              color="info"
              @click="prev"
            >
              <v-icon small>
                mdi-chevron-left
              </v-icon>
            </v-btn>
            <v-btn
              fab
              text
              small
              color="info"
              @click="next"
            >
              <v-icon small>
                mdi-chevron-right
              </v-icon>
            </v-btn>
            <v-toolbar-title v-if="$refs.calendar">
              {{ $refs.calendar.title }}
            </v-toolbar-title>
            <v-spacer></v-spacer>

            <!-- New event form !-->
            <v-dialog
              v-model="new_event"
              max-width="550px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="dialog"
                  dark
                  v-bind="attrs"
                  v-on="on"
                  class="ma-2"
                >
                  + New Event
                </v-btn>
              </template>
              <v-form v-model="form">
                <v-card>
                  <v-card-title>
                    <span class="headline">Add a new event</span>
                  </v-card-title>
                  <v-card-text>
                    <v-container>
                      <v-row>

                        <v-col cols="12">
                          <v-text-field
                            v-model="new_event.title"
                            label= "Add Title"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12">
                          <v-btn-toggle
                            v-model="event_type"
                            tile
                            color="deep-purple accent-3"
                            group
                            v-for="names in event_type" :key="names"
                          >
                            <v-btn value=names>
                              {{names}}
                            </v-btn>
                          </v-btn-toggle>
                        </v-col>

                      </v-row>
                    </v-container>
                  </v-card-text>

                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                      color="blue darken-1"
                      text
                      @click="new_event = false"
                    >
                      Close
                    </v-btn>
                    <v-btn
                      color="success"
                      text
                      @click="new_event = false"
                      depressed
                    >
                      Save
                    </v-btn>
                  </v-card-actions>

                </v-card>
              </v-form>
            </v-dialog>



            <v-menu
              bottom
              right
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  outlined
                  color="accent"
                  v-bind="attrs"
                  v-on="on"
                  class="ma-2"
                >
                  <span>{{ typeToLabel[type] }}</span>
                  <v-icon right>
                    mdi-menu-down
                  </v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="type = 'day'">
                  <v-list-item-title>Day</v-list-item-title>
                </v-list-item>
                <v-list-item @click="type = 'week'">
                  <v-list-item-title>Week</v-list-item-title>
                </v-list-item>
                <v-list-item @click="type = 'month'">
                  <v-list-item-title>Month</v-list-item-title>
                </v-list-item>
                <v-list-item @click="type = '4day'">
                  <v-list-item-title>4 days</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>

          </v-toolbar>
        </v-sheet>
        <v-sheet height="600">
          <v-calendar
            ref="calendar"
            v-model="focus"
            color="accent"
            :events="events"
            :event-color="getEventColor"
            :type="type"
            @click:event="showEvent"
            @click:more="viewDay"
            @click:date="viewDay"
            @change="updateRange"
          ></v-calendar>
          <v-menu
            v-model="selectedOpen"
            :close-on-content-click="false"
            :activator="selectedElement"
            offset-x
          >
            <v-card
              color="grey lighten-4"
              min-width="350px"
              flat
            >
              <v-toolbar
                :color="selectedEvent.color"
                dark
              >
                <v-btn icon>
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon>
                  <v-icon>mdi-heart</v-icon>
                </v-btn>
                <v-btn icon>
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </v-toolbar>
              <v-card-text>
                <span v-html="selectedEvent.details"></span>
              </v-card-text>
              <v-card-actions>
                <v-btn
                  text
                  color="secondary"
                  @click="selectedOpen = false"
                >
                  Cancel
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-menu>
        </v-sheet>
      </v-col>
    </v-row>
  </v-app>

</template>

<script>
export default {
    data: () => ({
      new_event: false,
      form: false,
      focus: '',
      type: 'month',
      typeToLabel: {
          month: 'Month',
          week: 'Week',
          day: 'Day',
          '4day': '4 Days',
      },
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
      events: [],
      colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
      names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
      event_type: ['Event', 'Out of office', 'Task', 'Appointment slots', 'Holiday', 'Party'],
      event: {
          title: '',
          type: '',
          date: null,
          guests: '',
          location: '',
          description: ''
      },
    }),

    mounted () {
      this.$refs.calendar.checkChange()
    },

    methods: {
      viewDay ({ date }) {
          this.focus = date;
          this.type = 'day'
      },
      getEventColor (event) {
          return event.color
      },
      setToday () {
          this.focus = ''
      },
      prev () {
          this.$refs.calendar.prev()
      },
      next () {
          this.$refs.calendar.next()
      },
      showEvent ({ nativeEvent, event }) {
          const open = () => {
              this.selectedEvent = event;
              this.selectedElement = nativeEvent.target;
              setTimeout(() => {
                  this.selectedOpen = true
              }, 10)
          };

          if (this.selectedOpen) {
              this.selectedOpen = false;
              setTimeout(open, 10)
          } else {
              open()
          }

          nativeEvent.stopPropagation()
      },
      updateRange ({ start, end }) {
          const events = [];

          const min = new Date(`${start.date}T00:00:00`);
          const max = new Date(`${end.date}T23:59:59`);
          const days = (max.getTime() - min.getTime()) / 86400000;
          const eventCount = this.rnd(days, days + 20);

          for (let i = 0; i < eventCount; i++) {
              const allDay = this.rnd(0, 3) === 0;
              const firstTimestamp = this.rnd(min.getTime(), max.getTime());
              const first = new Date(firstTimestamp - (firstTimestamp % 900000));
              const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000;
              const second = new Date(first.getTime() + secondTimestamp);

              events.push({
                  name: this.names[this.rnd(0, this.names.length - 1)],
                  start: first,
                  end: second,
                  color: this.colors[this.rnd(0, this.colors.length - 1)],
                  timed: !allDay,
              })
          }

          this.events = events
      },
      rnd (a, b) {
          return Math.floor((b - a + 1) * Math.random()) + a
      },
    }
}

</script>
