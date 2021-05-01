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
                  outlined
                  dense
                  readonly
                  prepend-icon="mdi-calendar-search"
                  v-bind="attrs"
                  v-on="on"
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





<!--            <v-menu-->
<!--              ref="menu"-->
<!--              v-model="startTime_menu"-->
<!--              :close-on-content-click="false"-->
<!--              :nudge-right="40"-->
<!--              :return-value.sync="startTime_menu"-->
<!--              transition="scale-transition"-->
<!--              offset-y-->
<!--              max-width="290px"-->
<!--              min-width="290px"-->
<!--            >-->
<!--              <template v-slot:activator="{ on, attrs }">-->
<!--                <v-text-field-->
<!--                  v-model="event.start_time"-->
<!--                  label="Start Time"-->
<!--                  outlined-->
<!--                  dense-->
<!--                  readonly-->
<!--                  prepend-icon="mdi-clock-start"-->
<!--                  v-bind="attrs"-->
<!--                  v-on="on"-->
<!--                ></v-text-field>-->
<!--              </template>-->
<!--              <v-time-picker-->
<!--                v-if="startTime_menu"-->
<!--                v-model="event.start_time"-->
<!--                full-width-->
<!--                :max="event.start_time"-->
<!--                @click:minute="$refs.menu.save(event.start_time)"-->
<!--              ></v-time-picker>-->
<!--            </v-menu>-->

<!--            <v-menu-->
<!--              ref="menu"-->
<!--              v-model="endTime_menu"-->
<!--              :close-on-content-click="false"-->
<!--              :nudge-right="40"-->
<!--              :return-value.sync="endTime_menu"-->
<!--              transition="scale-transition"-->
<!--              offset-y-->
<!--              max-width="290px"-->
<!--              min-width="290px"-->
<!--            >-->
<!--              <template v-slot:activator="{ on, attrs }">-->
<!--                <v-text-field-->
<!--                  v-model="event.end_time"-->
<!--                  label="End Time"-->
<!--                  outlined-->
<!--                  prepend-icon="mdi-clock-end"-->
<!--                  dense-->
<!--                  readonly-->
<!--                  v-bind="attrs"-->
<!--                  v-on="on"-->
<!--                ></v-text-field>-->
<!--              </template>-->
<!--              <v-time-picker-->
<!--                v-if="endTime_menu"-->
<!--                v-model="event.end_time"-->
<!--                :min="event.start_time"-->
<!--                full-width-->
<!--                @click:minute="$refs.menu.save(event.end_time)"-->
<!--              ></v-time-picker>-->
<!--            </v-menu>-->

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
              v-model="event.attendees"
              :items="categories"
              multiple
              outlined
              dense
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

          <!-- Step 3 !-->
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
            :categories="event.attendees"
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

            startTime_menu: false,
            endTime_menu: false,
            focus: '',
            events: [],
            colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
            names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
            categories: ['John Smith', 'Tori Walker', "Ronnie Dangerfield", "Mark Rowry", "Patrick Stevenson"],
            event: {
                name: '',
                start_date: null,
                end_date: null,
                start_time: null,
                end_time: null,
                location: '',
                description: '',
                attendees: []
            },
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
            fetchEvents ({ start, end }) {
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
                        category: this.event.attendees,
                    })
                }
                console.log(this.event.start_date);

                this.events = events
            },
            rnd (a, b) {
                return Math.floor((b - a + 1) * Math.random()) + a
            },
        },
    }

</script>
