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
              max-width="650px"
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
                          <v-icon>
                            mdi-folder-outline
                          </v-icon>

                          <v-btn-toggle
                            v-model="selected_event_type"
                            tile
                            group
                            color="accent"
                            mandatory
                          >
                            <v-btn
                              v-for="type in event_type"
                              :key="type"
                              class="ma-2"
                            >
                              {{ type }}
                            </v-btn>
                          </v-btn-toggle>
                        </v-col>

                        <v-tabs-items v-model="selected_event_type">
                          <v-tab-item>
                            <v-col cols="12">
                              <v-text-field
                                prepend-icon="mdi-format-title"
                                v-model="event.name"
                                label= "Lecture Name"
                                dense
                                class="mb-3"
                              ></v-text-field>
                            </v-col>

                            <v-row>
                              <v-col
                                cols="12"
                                sm="4"
                                md="4"
                                class="ml-3"
                              >
                                <v-text-field
                                  dense
                                  prepend-icon="mdi-clock-outline"
                                  outlined
                                  v-model="event.start_date"
                                  label= "Date"
                                ></v-text-field>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="3"
                                md="3"
                              >
                                <v-text-field
                                  dense
                                  v-model="event.start_time"
                                  label= "Start Time"
                                ></v-text-field>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="3"
                                md="3"
                              >
                                <v-text-field
                                  dense
                                  v-model="event.end_time"
                                  label= "End Time"
                                ></v-text-field>
                              </v-col>
                            </v-row>

                            <v-col cols="12">
                              <v-autocomplete
                                v-model="event.hall"
                                prepend-icon="mdi-map-marker"
                                dense
                                outlined
                                :items="location"
                                color="white"
                                item-text="name"
                                label="Lecture Hall"
                              ></v-autocomplete>
                            </v-col>

                            <v-col cols="12">
                              <v-textarea
                                v-model="event.description"
                                prepend-icon="mdi-comment"
                                dense
                                outlined
                                label="Description"
                              ></v-textarea>
                            </v-col>

                            <v-col cols="12">
                              <v-autocomplete
                                v-model="event.attendees"
                                prepend-icon="mdi-email-outline"
                                dense
                                multiple
                                outlined
                                :items="friendsList"
                                color="white"
                                item-text="name"
                                label="Attendees"
                              ></v-autocomplete>
                            </v-col>
                          </v-tab-item>

                          <v-tab-item>
                            <v-col>
                              <v-text-field
                                prepend-icon="mdi-format-title"
                                v-model="event.name"
                                label= "Recitation Name"
                                dense
                                class="mb-3"
                              ></v-text-field>
                            </v-col>

                            <v-row>
                              <v-col
                                cols="12"
                                sm="4"
                                md="4"
                                class="ml-3"
                              >
                                <v-text-field
                                  dense
                                  prepend-icon="mdi-clock-outline"
                                  outlined
                                  v-model="event.start_date"
                                  label= "Date"
                                ></v-text-field>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="3"
                                md="3"
                              >
                                <v-text-field
                                  dense
                                  v-model="event.start_time"
                                  label= "Start Time"
                                ></v-text-field>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="3"
                                md="3"
                              >
                                <v-text-field
                                  dense
                                  v-model="event.end_time"
                                  label= "End Time"
                                ></v-text-field>
                              </v-col>
                            </v-row>

                            <v-col cols="12">
                              <v-autocomplete
                                v-model="event.hall"
                                prepend-icon="mdi-map-marker"
                                dense
                                outlined
                                :items="location"
                                color="white"
                                item-text="name"
                                label="Recitation Hall"
                              ></v-autocomplete>
                            </v-col>

                            <v-col cols="12">
                              <v-autocomplete
                                v-model="event.attendees"
                                prepend-icon="mdi-email-outline"
                                dense
                                multiple
                                outlined
                                :items="friendsList"
                                color="white"
                                item-text="name"
                                label="Attendees"
                              ></v-autocomplete>
                            </v-col>
                          </v-tab-item>

                          <v-tab-item>
                            <v-col>
                              <v-text-field
                                prepend-icon="mdi-format-title"
                                v-model="event.name"
                                label= "Project / HW / Essay Name"
                                dense
                                class="mb-3"
                              ></v-text-field>
                            </v-col>

                            <v-row>
                              <v-col
                                cols="12"
                                sm="4"
                                md="4"
                                class="ml-3"
                              >
                                <v-text-field
                                  dense
                                  prepend-icon="mdi-clock-outline"
                                  outlined
                                  v-model="event.start_date"
                                  label= "Start Date"
                                ></v-text-field>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="4"
                                md="4"
                              >
                                <v-text-field
                                  dense
                                  outlined
                                  v-model="event.end_date"
                                  label= "Due Date"
                                ></v-text-field>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="3"
                                md="3"
                              >
                                <v-text-field
                                  dense
                                  v-model="event.end_time"
                                  label= "Due Time"
                                ></v-text-field>
                              </v-col>
                            </v-row>

                            <v-col cols="12">
                              <v-textarea
                                v-model="event.description"
                                prepend-icon="mdi-comment"
                                dense
                                outlined
                                label="Description"
                              ></v-textarea>
                            </v-col>

                            <v-col cols="12">
                              <v-autocomplete
                                v-model="event.attendees"
                                prepend-icon="mdi-email-outline"
                                dense
                                multiple
                                outlined
                                :items="friendsList"
                                color="white"
                                item-text="name"
                                label="Project Partners"
                              ></v-autocomplete>
                            </v-col>

                          </v-tab-item>

                          <v-tab-item>
                            <v-col cols="12">
                              <v-text-field
                                prepend-icon="mdi-format-title"
                                v-model="event.name"
                                label= "Task Name"
                                dense
                                class="mb-3"
                              ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                              <v-textarea
                                v-model="event.description"
                                prepend-icon="mdi-comment"
                                dense
                                outlined
                                label="Description"
                              ></v-textarea>
                            </v-col>
                          </v-tab-item>

                          <v-tab-item>
                            <v-card flat>
                              <v-card-text>Personal</v-card-text>
                            </v-card>
                          </v-tab-item>

                        </v-tabs-items>

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
                      @click="submit"
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
    <v-alert
        :value="success"
        outlined
        type="success"
        transition="scale-transition"
      >
        Event Created!
      </v-alert>
      <v-alert
        :value="failure"
        outlined
        type="failure"
        transition="scale-transition"
      >
        Uh oh. Something went wrong.
      </v-alert>
  </v-app>

</template>

<script>
import moment from 'moment'

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
      event_type: ['Lecture', 'Recitation', 'Deadline', 'Task'],
      selected_event_type: 'Lecture',
      event: {
          name: '',
          start_date: null,
          end_date: null,
          start_time: null,
          end_time: null,
          hall: '',
          description: '',
          attendees: []
      },
      location: [
          { name: 'Davis Hall', abbr: 'DV', id: 1 },
          { name: 'Baldy Hall', abbr: 'BL', id: 2 },
          { name: 'Cooke Hall', abbr: 'CK', id: 3 },
          { name: 'Talbert Hall', abbr: 'TB', id: 4 },
          { name: 'Norton Hall', abbr: 'NT', id: 5 },
      ],
      friendsList: [
        {name: 'Mike', abbr: 'MC', id: 1},
        {name: 'Adarsh', abbr: 'AS', id: 2},
        {name: 'Truong', abbr: 'TP', id: 3},
      ],

      // alert variables
      success: false,
      failure: false
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
          /*
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
           */
          this.start = start;
          this.end = end
      },

      rnd (a, b) {
          return Math.floor((b - a + 1) * Math.random()) + a
      },


      addLecture(){
          const lecture = [];

          let startTime = this.event.date + ' '  + this.event.start_time;
          let endTime = this.event.date + " " + this.event.end_time;
          const allDay = this.rnd(0, 3) === 0;

          startTime = moment(startTime, "YYYY-MM-DD HH:mm:ss");
          endTime = moment(endTime, "YYYY-MM-DD HH:mm:ss");

          lecture.push({
              name: this.event.name,
              start: startTime.format("YYYY-MM-DD HH:mm:ss"),
              end: endTime.format("YYYY-MM-DD HH:mm:ss"),
              color: 'indigo',
              timed: !allDay,
          });

          this.events = lecture
      },
      async submit() {
        let data = this.event
        data.event_type = this.selected_event_type

        // This post request is subject to change as the back-end is developed
        try {
          await $axios.post("/users/signup", data).then(this.success = true);
        } catch (error) {
          this.failure = true
        }
      }
    }
}

</script>
