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

            <v-btn
              color="dialog"
              @click="e6 = 2"
            >
              Continue
            </v-btn>

          </v-stepper-content>

          <v-stepper-step
            :complete="e6 > 2"
            step="2"
            editable
          >
            Meeting details
            <small>Select your meeting's date and duration</small>
          </v-stepper-step>

          <v-stepper-content step="2">
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
              @click="loadCalendar"
            >
              Continue
            </v-btn>
            <v-btn
              text
              @click="cancelEvent"
            >
              Cancel
            </v-btn>
          </v-stepper-content>

          <v-stepper-step
            :complete="e6 > 3"
            step="3"
            editable
          >
            Select attendees
            <small>Select your meeting's attendees</small>
          </v-stepper-step>

          <v-stepper-content step="3">
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
              @click="eventConfirm"
            >
              Continue
            </v-btn>

            <v-btn text>
              Cancel
            </v-btn>
            <v-btn v-if="attendees.length > 2"
                   @click="updateEvents"
                   color="warning"
                   class="mx-1"
                   fab
                   dark
                   small
            >
              <v-icon center>
                mdi-calendar-refresh
              </v-icon>
            </v-btn>

            <v-col class="pt-0 mt-0"></v-col>

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
                <v-text-field
                  v-model="event.start_time"
                  label="Start Time"
                  outlined
                  dense
                  readonly
                  prepend-icon="mdi-clock-start"
                ></v-text-field>
              </v-col>

              <v-col>
                <v-text-field
                  v-model="event.end_time"
                  label="End Time"
                  outlined
                  prepend-icon="mdi-clock-end"
                  dense
                  readonly
                ></v-text-field>

              </v-col>
            </v-row>
            <v-btn @click="buildEvent"
              color="dialog"
            >
              Schedule it!
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
            <v-btn
              @click="fetchEvents">
              REFRESH
            </v-btn>
            <v-toolbar-title v-if="$refs.calendar">
              {{ $refs.calendar.title }}
            </v-toolbar-title>
            <v-spacer></v-spacer>

          </v-toolbar>
        </v-sheet>
        <v-sheet height="800">
          <v-skeleton-loader v-if="!load_calendar"
            class="mx-auto"
            type="table-heading, list-item-three-line, image, table-tfoot"
          ></v-skeleton-loader>

          <v-calendar v-else
            ref="calendar"

            v-model="event.start_date"
            color="accent"
            type="category"
            category-show-all
            :categories="attendees"
            :events="events"
            :event-ripple="false"
            :event-color="getEventColor"
            @mousedown:event="startDrag"
            @mousedown:time="startTime"
            @mousemove:time="mouseMove"
            @mouseup:time="endDrag"
            @mouseleave.native="cancelDrag"
            @change="updateEvents"
          ></v-calendar>
          <template v-slot:event="{ event_x, time, eventSummary }">
            <div
              class="v-event-draggable"
              v-html="eventSummary()"
            ></div>
            <div
              v-if="time"
              class="v-event-drag-bottom"
              @mousedown.stop="extendBottom(event_x)"
            ></div>
          </template>

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
        load_calendar: false,
        duration_menu: false,
        duration_days: '',
        duration_hours: '',
        duration_minutes: '',
        hours_options: [...Array(24).keys()],
        min_options: [15,30,45],
        attendees: ['Combined', "Michael Clevs"],
        startTime_menu: false,
        endTime_menu: false,
        focus: '',
        events: [],
        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange'],
        names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
        categories: ['John Smith', 'Tori Walker', "Michael Clevs", "Mark Rowry"],
        event: {
            name: '',
            start_date: null,
            end_date: null,
            start_time: null,
            end_time: null,
            location: '',
            description: '',
        },
        dragEvent: null,
        dragStart: null,
        createEvent: null,
        createStart: null,
        extendOriginal: null,
        count: 1,
        on: '',
        attrs: '',
    }),
    mounted () {
        if(this.load_calendar){
            this.$refs.calendar.checkChange()
        }
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
        loadCalendar () {
            this.e6 = 3;
            // this.categories.push(this.$store.state.user.username);
            this.load_calendar = true;
        },
        cancelEvent () {
            // console.log(this.events);
            // delete this.events[this.events.length-1];
        },
        startDrag ({ event, timed }) {
            let new_event_index = this.events.findIndex(x => x.name === event.name);
            let events_len = this.events.length;
            if (event && timed && new_event_index === events_len - 1) {
                this.dragEvent = event;
                this.dragTime = null;
                this.extendOriginal = null
            }
        },

        startTime (tms) {
            const mouse = this.toTime(tms);

            if (this.dragEvent && this.dragTime === null) {
                const start = this.dragEvent.start;
                this.dragTime = mouse - start
            } else {
                this.createStart = this.roundTime(mouse);
                this.createEvent = {
                    name: this.event.name,
                    color: this.rndElement(this.colors),
                    start: this.createStart,
                    end: this.createStart,
                    timed: true,
                    category: "Combined",
                };
                this.events.push(this.createEvent)
            }
        },

        extendBottom (event) {
            this.createEvent = event;
            this.createStart = event.start;
            this.extendOriginal = event.end
        },

        mouseMove (tms) {
            const mouse = this.toTime(tms);

            if (this.dragEvent && this.dragTime !== null) {
                const start = this.dragEvent.start;
                const end = this.dragEvent.end;
                const duration = end - start;
                const newStartTime = mouse - this.dragTime;
                const newStart = this.roundTime(newStartTime);
                const newEnd = newStart + duration;

                this.dragEvent.start = newStart;
                this.dragEvent.end = newEnd;
            }
            else if (this.createEvent && this.createStart !== null) {
                const mouseRounded = this.roundTime(mouse, false);
                const min = Math.min(mouseRounded, this.createStart);
                const max = Math.max(mouseRounded, this.createStart);

                this.createEvent.start = min;
                this.createEvent.end = max;
            }
        },
        endDrag () {
            this.dragTime = null;
            this.dragEvent = null;
            this.createEvent = null;
            this.createStart = null;
            this.extendOriginal = null
        },

        cancelDrag () {
            if (this.createEvent) {
                if (this.extendOriginal) {
                    this.createEvent.end = this.extendOriginal
                } else {
                    const i = this.events.indexOf(this.createEvent);
                    if (i !== -1) {
                        this.events.splice(i, 1)
                    }
                }
            }
            this.createEvent = null;
            this.createStart = null;
            this.dragTime = null;
            this.dragEvent = null
        },

        roundTime (time, down = true) {
            const roundTo = 15; // minutes
            const roundDownTime = roundTo * 60 * 1000;

            return down
                ? time - time % roundDownTime
                : time + (roundDownTime - (time % roundDownTime))
        },

        toTime (tms) {
            return new Date(tms.year, tms.month - 1, tms.day, tms.hour, tms.minute).getTime()
        },

        async getUserEvents () {
          console.log(this.$store.state.user.username)
            // Truong, make this function async and pull the event data here
            await this.$axios.post('http://localhost/event/read.php', {
              headers: {
              "Content-Type": "application/json",
              "Access-Control-Allow-Origin": "*",
              "Access-Control-Allow-Methods": "OPTIONS",
              "Access-Control-Allow-Headers": "Content-Type, Authorization",
              "Access-Control-Allow-Credentials": "true",
              "CrossOrigin": "true"
              },
              data: {
                username: this.$store.state.user.username
              }
            })
            .then(function (response) {
                var headers = response.headers
                return headers['data']['records']
            })
            .catch(function (error) {
              console.log(error.response)
            });
            let data = [];
            return data;
        },

        async getNames () {

            // await this.$axios.post('http://localhost/friend/read.php', {
            //   headers: {
            //   "Content-Type": "application/json",
            //   "Access-Control-Allow-Origin": "*",
            //   "Access-Control-Allow-Methods": "OPTIONS",
            //   "Access-Control-Allow-Headers": "Content-Type, Authorization",
            //   "Access-Control-Allow-Credentials": "true",
            //   "CrossOrigin": "true"
            //   },
            //   data: {
            //     username: this.$store.state.user.username,
            //     request: "false",
            //     friend: "true"
            //   }
            // })
            // .then(function (response) {
            //     var headers = response.headers
            //     return headers['data']['records']
            // })
            // .catch(function (error) {
            //   console.log(error.response)
            // });
            // let data = [];
            // return data;

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

            this.categories = this.getNames();

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

                let user = user_calendars[i].username;

                if(this.attendees.indexOf(user) !== -1){
                    events.push({
                        name: user_calendars[i].event_name,
                        start: startTime.format("YYYY-MM-DD HH:mm:ss"),
                        end: endTime.format("YYYY-MM-DD HH:mm:ss"),
                        color: "inactive",
                        category: user,
                    });
                }

            }
            this.events = events;

            return events;

        },

        eventConfirm () {
            this.e6 = 4;
            let newEvent = this.events[this.events.length-1];

            let start  = moment.unix(newEvent.start).format("HH:mm");
            let end = moment.unix(newEvent.end).format("HH:mm");

            this.event.end_date = this.event.start_date;
            this.event.type = "Conference Meeting";
            this.event.start_time = start;
            this.event.end_time = end;
            this.event.location = "Conference"

        },

        async buildEvent () {
            const attendees = [...this.attendees];

            console.log(attendees);

            var self = this;

            const comb = attendees.indexOf("Combined");
            if (comb > -1){
                attendees.splice(comb, 1);
            }
            await this.$axios.post('http://localhost/event/create.php', {
                headers: {
                    "Content-Type": "application/json",
                    "Access-Control-Allow-Origin": "*",
                    "Access-Control-Allow-Methods": "OPTIONS",
                    "Access-Control-Allow-Headers": "Content-Type, Authorization",
                    "Access-Control-Allow-Credentials": "true",
                    "CrossOrigin": "true"
                },
                data: {
                    username: this.$store.state.user.username,
                    email: "todo",
                    event_name: self.event.name,
                    event_type: self.event.type,
                    event_start_day: self.event.start_date,
                    event_start_time: self.event.start_time,
                    event_end_day: self.event.end_date,
                    event_end_time: self.event.end_time,
                    event_description: self.event.description,
                    event_location: self.event.location,
                    event_attendee_username: "todo",
                    event_attendee_email: "todo"
                }
            })
                .then(function (response) {
                    console.log(response)
                    return response.headers['message']
                })
                .catch(function (error) {
                    console.log(error)
                    console.log(error.response)
                    return response.headers['message']
                });


            // await this.$axios.post('http://localhost/event/create.php', {
            //   headers: {
            //   "Content-Type": "application/json",
            //   "Access-Control-Allow-Origin": "*",
            //   "Access-Control-Allow-Methods": "OPTIONS",
            //   "Access-Control-Allow-Headers": "Content-Type, Authorization",
            //   "Access-Control-Allow-Credentials": "true",
            //   "CrossOrigin": "true"
            //   },
            //   data: {
            //     username: username,
            //     email: email,
            //     event_name: this.event.name,
            //     event_type: this.event.type,
            //     event_start_day: this.event.start_date,
            //     event_start_time: this.event.start_time,
            //     event_end_day: this.event.end_time,
            //     event_end_time: this.event.end_time,
            //     event_description: this.event.description,
            //     event_location: this.event.location,
            //     event_attendee_username: "",
            //     event_attendee_email: ""
            //   }
            // })
            // .then(function (response) {
            //     return response.headers['message']
            // })
            // .catch(function (error) {
            //   console.log(error.response)
            //   return response.headers['message']
            // });
            // return data;

        },
        updateEvents ({ start, end }){
            let individual_events = this.fetchEvents( { start, end} );
            let combined = [];

            for(let element of individual_events){
                combined.push({
                    name: element.name,
                    start: element.start,
                    end: element.end,
                    color: "inactive",
                    category: "Combined",
                });
            }

            // individual_events = individual_events.concat(combined);
            this.events = this.events.concat(combined)

        },

        rnd (a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
        rndElement (arr) {
            return arr[this.rnd(0, arr.length - 1)]
        },
    },
}

</script>

<style lang="scss">
  @import "./assets/variables.scss";
</style>
