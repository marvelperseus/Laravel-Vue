<template>
    <v-layout align-start justify-center>
        <template v-if="loading">
            <v-layout
                    align-center
                    justify-center
                    fill-height
            >
                <v-progress-circular
                        :size="80"
                        :width="8"
                        color="primary"
                        indeterminate
                ></v-progress-circular>
            </v-layout>
        </template>
        <template v-else>
            <v-flex xs12 class="appointment">
                <v-card class="py-3">
                    <v-card-text>
                        <full-calendar
                                ref="appointment_table"
                                :events="appointments"
                                :config="config"
                                @view-render="viewRender"
                        >
                        </full-calendar>
                    </v-card-text>
                </v-card>
            </v-flex>
        </template>
    </v-layout>
</template>

<script>
    import {FullCalendar} from 'vue-full-calendar'
    import 'fullcalendar/dist/fullcalendar.css'
    import 'fullcalendar-scheduler/dist/scheduler.css'

    export default {
        name: "Appointment",
        components: {FullCalendar},
        data: () => ({
            loading: true,
            config: {
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                locale: 'ja',
                defaultView: 'agendaDay',
                titleFormat: 'MM/DD',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                views: {
                    month: {
                        titleFormat: 'M月 - YYYY'
                    },
                    agendaWeek: {
                        titleFormat: 'MM/DD',
                        columnHeaderFormat: 'M/DD（ddd）'
                    },
                    agendaDay: {
                        titleFormat: 'MM/DD（ddd）'
                    }
                },
                buttonText: {
                    today: '今日',
                    month: '月',
                    week: '週',
                    day: '日'
                },
                allDaySlot: false,
                nowIndicator: true,
                slotDuration: '00:30:00',
                slotLabelFormat: 'H:mm',
                businessHours: [],
                minTime: '08:00:00',
                maxTime: '20:00:00',
                resources: [],


                // selectable: true,
                // editable: true,
            },
            appointments: [],
            holidaysForLunchbreak: [],
            timesLunchbreak: []
        }),
        created() {
            this.initialize()
        },
        methods: {
            async initialize() {
                let data = null
                await axios
                    .get('/api/appointments')
                    .then(response => (
                        data = response.data
                    ))
                    .catch(error => (
                        console.log(error.response)
                    ))

                this.config.slotDuration = data.slotDuration
                this.config.businessHours = data.businessHours
                this.config.minTime = data.minTime
                this.config.maxTime = data.maxTime
                this.config.resources = data.resources

                this.appointments = data.appointments

                this.holidaysForLunchbreak = data.holidaysForLunchbreak;
                this.timesLunchbreak = data.timesLunchbreak

                this.loading = false
            },
            viewRender(view, element) {
                if (view.name === 'agendaDay') {
                    if (this.holidaysForLunchbreak.indexOf(view.start.format('YYYY-MM-DD')) < 0) {
                        if (this.timesLunchbreak.length > 0) {
                            let start = $(".fc-slats").find("[data-time='" + this.timesLunchbreak[view.start.format('d')]['start'] + ":00']")
                            let end = $(".fc-slats").find("[data-time='" + this.timesLunchbreak[view.start.format('d')]['end'] + ":00']")
                            $(start).addClass('is-bg-lunchbreak')
                            $(start).nextUntil(end, 'tr').addClass('is-bg-lunchbreak')
                        }
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>

<style>
    .appointment .fc-now-indicator {
        border: 0 solid #45ad7e;
    }

    .appointment .fc-now-indicator-line {
        border-top-width: 1px;
    }

    .appointment .is-bg-lunchbreak .fc-widget-content:not(.fc-time) {
        background-color: #fefad2;
    }

    .appointment th.fc-day-header {
        background-color: #ededed;
    }

    .appointment th.fc-day-header.fc-sun {
        background-color: #FCE4E6;
        color: #C6374A;
    }

    .appointment th.fc-day-header.fc-sat {
        background-color: #D1E9F0;
        color: #48A2BC;
    }

    .appointment td.fc-day-top {
        text-align: center;
    }

    .appointment td.fc-day-top .fc-day-number {
        float: none !important;
    }

    .appointment .fc-event {
        border-radius: 0;
        border-width: 0;
    }

    .appointment .fc-event .fc-time {
        font-weight: normal;
    }
</style>
