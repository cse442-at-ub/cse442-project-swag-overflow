<template>
  <v-app id="scheduler">
    <v-row class="fill-height">
      <v-col cols="3">
        <v-sheet rounded="lg">
          <v-card-title class="text-h6">
            Select Date and Attendees
          </v-card-title>

          <v-divider class="my-1"></v-divider>
          <v-col
            cols="12"
          >
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
                  label="Date"
                  prepend-icon="mdi-clock-outline"
                  dense
                  outlined
                  readonly
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
          </v-col>

          <v-col cols="12">
            <v-combobox
              v-model="event.attendees"
              :items="categories"
              multiple
              outlined
              chips
              prepend-icon="mdi-account-group"
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
          </v-col>
        </v-sheet>
      </v-col>

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
          </v-toolbar>
        </v-sheet>
        <v-sheet height="800">
          <v-calendar
            ref="calendar"
            v-model="focus"
            color="primary"
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
            event_menu: false,
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
                const events = []

                const min = new Date(`${start.date}T00:00:00`)
                const max = new Date(`${end.date}T23:59:59`)
                const days = (max.getTime() - min.getTime()) / 86400000
                const eventCount = this.rnd(days, days + 20)

                for (let i = 0; i < eventCount; i++) {
                    const allDay = this.rnd(0, 3) === 0
                    const firstTimestamp = this.rnd(min.getTime(), max.getTime())
                    const first = new Date(firstTimestamp - (firstTimestamp % 900000))
                    const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
                    const second = new Date(first.getTime() + secondTimestamp)

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
