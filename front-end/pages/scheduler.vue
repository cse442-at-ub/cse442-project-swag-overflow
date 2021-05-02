<template>
  <v-app id="scheduler">
    <v-row>
      <v-col cols="6" md="4">
        <v-stepper
          v-model="e6"
          vertical
          non-linear
          dark
        >
          <v-stepper-step
            :complete="e6 > 1"
            step="1"
            editable
          >
            Set up meeting
            <small>Select your meeting's date and duration</small>
          </v-stepper-step>

          <v-stepper-content step="1">
            <v-col class="pt-0 mt-0"></v-col>

            <v-menu
              v-model="event_menu"
              :close-on-content-click="false"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="event.start_date"
                  label="Start Date"
                  v-bind="attrs"
                  v-on="on"
                  outlined
                  dense
                  readonly
                  prepend-icon="mdi-calendar-search"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="event.start_date"
                @input="event_menu = false"
              >
              </v-date-picker>
            </v-menu>

            <v-row>
              <v-col
                cols="12"
                sm="4"
                md="4"
              >
                <v-text-field
                  v-model="duration_days"
                  label="Days"
                  outlined
                  dense
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </v-col>

              <v-col
                cols="12"
                sm="4"
                md="4"
              >
                <v-combobox
                  v-model="duration_hours"
                  label="Hour"
                  :items="hours_options"
                  outlined
                  dense
                  v-bind="attrs"
                  v-on="on"
                ></v-combobox>
              </v-col>

              <v-col
                cols="12"
                sm="4"
                md="4"
              >
                <v-combobox
                  v-model="duration_minutes"
                  label="Minutes"
                  :items="min_options"
                  outlined
                  dense
                  v-bind="attrs"
                  v-on="on"
                ></v-combobox>
              </v-col>
            </v-row>
            <v-btn
              color="dialog"
              @click="e6 = 2"
            >
              Continue
            </v-btn>
            <v-btn text>
              Cancel
            </v-btn>
          </v-stepper-content>


          <v-stepper-step
            :complete="e6 > 2"
            step="2"
            editable
          >
            Select attendees
            <small>Select your meeting's attendees</small>
          </v-stepper-step>

          <v-stepper-content step="2">
            <v-col class="pt-0 mt-0"></v-col>
            <v-combobox
              v-model="attendees"
              :items="categories"
              multiple
              outlined
              chips
              prepend-icon="mdi-account-group"
              no-resize
              label="Attendees"
            >
              <template v-slot:selection="data">
                <v-chip
                  :key="JSON.stringify(data.item)"
                  v-bind="data.attrs"
                  :input-value="data.selected"
                  :disabled="data.disabled"
                  close
                  close-icon="mdi-close"
                  @click:close="data.parent.selectItem(data.item)"
                >
                  <v-avatar
                    class="accent white--text"
                    left
                    v-text="data.item.slice(0, 1).toUpperCase()"
                  ></v-avatar>
                  {{ data.item }}
                </v-chip>
              </template>
            </v-combobox>

            <v-btn
              color="dialog"
              @click="e6 = 3"
            >
              Continue
            </v-btn>
            <v-btn text>
              Cancel
            </v-btn>
          </v-stepper-content>

          <v-stepper-step
            :complete="e6 > 3"
            step="3"
            editable
          >
            Meeting details
            <small>Select your meeting's date and duration</small>
          </v-stepper-step>

          <v-stepper-content step="3">
            <v-col class="pt-0 mt-0"></v-col>

            <v-text-field
              prepend-icon="mdi-format-title"
              v-model="event.name"
              label= "Event Name"
              dense
              class="mb-3"
            ></v-text-field>

            <v-textarea
              v-model="event.description"
              prepend-icon="mdi-comment"
              dense
              outlined
              label="Description"
            ></v-textarea>

            <v-btn
              color="dialog"
              @click="e6 = 4"
            >
              Continue
            </v-btn>
            <v-btn text>
              Cancel
            </v-btn>
          </v-stepper-content>

          <v-stepper-step
            step="4"
            editable
          >
            Send meeting invite
            <small>Finalize meeting details</small>
          </v-stepper-step>
          <v-stepper-content step="4">
            <v-col class="pt-0 mt-0"></v-col>
            <v-text-field
              v-model="event.start_date"
              label="Start Date"
              outlined
              dense
              readonly
              prepend-icon="mdi-calendar-search"
            ></v-text-field>

            <v-row>
              <v-col>
                <v-menu
                  ref="menu"
                  v-model="startTime_menu"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  :return-value.sync="startTime_menu"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="event.start_time"
                      label="Start Time"
                      outlined
                      dense
                      readonly
                      prepend-icon="mdi-clock-start"
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                    v-if="startTime_menu"
                    v-model="event.start_time"
                    full-width
                    :max="event.start_time"
                    @click:minute="$refs.menu.save(event.start_time)"
                  ></v-time-picker>
                </v-menu>
              </v-col>

              <v-col>
                <v-menu
                  ref="menu"
                  v-model="endTime_menu"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  :return-value.sync="endTime_menu"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="event.end_time"
                      label="End Time"
                      outlined
                      prepend-icon="mdi-clock-end"
                      dense
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                    v-if="endTime_menu"
                    v-model="event.end_time"
                    :min="event.start_time"
                    full-width
                    @click:minute="$refs.menu.save(event.end_time)"
                  ></v-time-picker>
                </v-menu>
              </v-col>
            </v-row>
            <v-btn
              color="dialog"
              @click="fetchEvents"
            >
              Continue
            </v-btn>
            <v-btn text>
              Cancel
            </v-btn>
          </v-stepper-content>

        </v-stepper>

      </v-col>

      <v-col cols="12" md="8">
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

          </v-toolbar>
        </v-sheet>
        <v-sheet height="800">
          <v-calendar
            ref="calendar"
            v-model="event.start_date"
            color="accent"
            type="category"
            category-show-all
            :categories="attendees"
            :events="events"
            :event-color="getEventColor"
            @change="fetchEvents"
          ></v-calendar>
        </v-sheet>
      </v-col>
    </v-row>
  </v-app>
</template>

<script>
import moment from 'moment'

export default {
    data: () => ({
        e6: 1,
        event_menu: false,
        duration_menu: false,
        duration_days: '',
        duration_hours: '',
        duration_minutes: '',
        hours_options: [...Array(24).keys()],
        min_options: [15,30,45],
        attendees: [],
        startTime_menu: false,
        endTime_menu: false,
        focus: '',
        events: [],
        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
        names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
        categories: ['Combined', 'John Smith', 'Tori Walker', "Michael Clevs", "Mark Rowry"],
        event: {
            name: '',
            start_date: null,
            end_date: null,
            start_time: null,
            end_time: null,
            location: '',
            description: '',
        },
        on: '',
        attrs: '',
    }),
    mounted () {
        this.$refs.calendar.checkChange()
    },
    methods: {
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
        getUserEvents () {
            // Truong, make this function async and pull the event data here
            let data = [
                          {
                              "username": "clevs13",
                              "email": "clevs10@buffalo.edu",
                              "event_name": "Demo event something",
                              "event_start_day": "2021-05-02",
                              "event_start_time": "15:00:00",
                              "event_end_day": "2021-05-02",
                              "event_end_time": "16:00:00",
                              "event_description": "Demo the create route for the new event backend",
                              "event_location": "Home",
                              "event_attendee_username": "clevs13",
                              "event_attendee_email": "clevs13@buffalo.edu"
                          },
                          {
                              "username": "jsmith11",
                              "email": "jsmith11@buffalo.edu",
                              "event_name": "Meeting",
                              "event_start_day": "2021-05-02",
                              "event_start_time": "14:00:00",
                              "event_end_day": "2021-05-02",
                              "event_end_time": "15:00:00",
                              "event_description": "Demo the create route for the new event backend",
                              "event_location": "Home",
                              "event_attendee_username": "clevs13",
                              "event_attendee_email": "clevs13@buffalo.edu"
                          },
                          {
                              "username": "markrowry",
                              "email": "markrowry@buffalo.edu",
                              "event_name": "Meeting",
                              "event_start_day": "2021-05-02",
                              "event_start_time": "13:00:00",
                              "event_end_day": "2021-05-02",
                              "event_end_time": "16:00:00",
                              "event_description": "Demo the create route for the new event backend",
                              "event_location": "Home",
                              "event_attendee_username": "clevs13",
                              "event_attendee_email": "clevs13@buffalo.edu"
                          },
                          {
                              "username": "markrowry",
                              "email": "markrowry@buffalo.edu",
                              "event_name": "Meeting",
                              "event_start_day": "2021-05-02",
                              "event_start_time": "11:00:00",
                              "event_end_day": "2021-05-02",
                              "event_end_time": "14:00:00",
                              "event_description": "Demo the create route for the new event backend",
                              "event_location": "Home",
                              "event_attendee_username": "clevs13",
                              "event_attendee_email": "clevs13@buffalo.edu"
                          },
                          {
                              "username": "towalker",
                              "email": "towalker@buffalo.edu",
                              "event_name": "Meeting",
                              "event_start_day": "2021-05-02",
                              "event_start_time": "16:00:00",
                              "event_end_day": "2021-05-02",
                              "event_end_time": "17:00:00",
                              "event_description": "Demo the create route for the new event backend",
                              "event_location": "Home",
                              "event_attendee_username": "clevs13",
                              "event_attendee_email": "clevs13@buffalo.edu"
                          },

                        ];
            return data;
        },
        getNames () {
            let data = {
                "clevs13": "Michael Clevs",
                "jsmith11": "John Smith",
                "markrowry": "Mark Rowry",
                "towalker": "Tori Walker"
            };
            return data;
        },
        fetchEvents ({ start, end }) {
            const events = [];
            const user_calendars = this.getUserEvents();

            for(let events in user_calendars){

            }

            let user_names = this.getNames();

            const eventCount = user_calendars.length;

            for (let i = 0; i < eventCount; i++) {
                let startTime = '';
                let endTime = '';

                if (user_calendars[i].event_start_day === user_calendars[i].event_end_day || user_calendars[i].event_end_day == null) {
                    startTime = user_calendars[i].event_start_day + ' ' + user_calendars[i].event_start_time;
                    endTime = user_calendars[i].event_start_day + " " + user_calendars[i].event_end_time;
                }
                else {
                    startTime = user_calendars[i].event_start_day + ' ' + user_calendars[i].event_start_time;
                    endTime = user_calendars[i].event_end_day + " " + user_calendars[i].event_start_time;
                }

                startTime = moment(startTime, "YYYY-MM-DD HH:mm:ss");
                endTime = moment(endTime, "YYYY-MM-DD HH:mm:ss");

                // let n = this.attendees.includes(user_names[user_calendars[i].username]);

                events.push({
                    name: user_calendars[i].event_name,
                    start: startTime.format("YYYY-MM-DD HH:mm:ss"),
                    end: endTime.format("YYYY-MM-DD HH:mm:ss"),
                    color: this.colors[this.rnd(0, this.colors.length - 1)],
                    category: user_names[user_calendars[i].username],
                });
            }

            console.log(this.attendees);

            this.events = events
        },
        rnd (a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
    },
}

</script>
