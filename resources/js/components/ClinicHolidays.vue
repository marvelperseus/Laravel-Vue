<template>
    <v-layout align-start justify-center>
        <v-flex xs12 md8 class="clinic-holidays">

            <v-tabs
                    v-model="activeTab"
                    color="transparent"
                    slider-color="secondary"
                    grow
                    class="mb-3"
            >
                <v-tab key="defaultHoliday">休診日</v-tab>
                <v-tab key="webHoliday">休診日（ウェブ予約）</v-tab>
            </v-tabs>

            <v-tabs-items
                    v-model="activeTab"
            >
                <v-tab-item key="defaultHoliday">
                    <v-card class="py-3">
                        <v-toolbar flat color="white">
                            <v-toolbar-title class="grey--text">休診日の設定</v-toolbar-title>
                            <v-divider
                                    class="ml-4"
                                    inset
                                    vertical
                            ></v-divider>
                        </v-toolbar>
                        <v-card-text>
                            <full-calendar
                                    ref="holiday_calendar"
                                    :events="events"
                                    :config="config"
                                    @day-click="dayClick"
                                    @event-selected="eventSelected"
                            >
                            </full-calendar>

                            <v-btn
                                    v-show="false"
                                    ref="setting_dialog"
                                    @click="openDialog"
                            >
                                setting dialog
                            </v-btn>
                            <v-menu
                                    v-model="isOpen"
                                    :position-x="dialogX"
                                    :position-y="dialogY"
                                    absolute
                                    offset-y
                            >
                                <v-card>
                                    <v-card-title>
                                        <span class="title primary--text text-xs-center">{{ editedItem.start }}</span>
                                    </v-card-title>
                                    <v-card-text class="py-0">
                                        <v-radio-group
                                                v-model="editedItem.type"
                                        >
                                            <v-radio label="なし" value="none"></v-radio>
                                            <v-radio label="休診日" value="full"></v-radio>
                                            <v-radio label="午前休診日" value="am"></v-radio>
                                            <v-radio label="午後休診日" value="pm"></v-radio>
                                        </v-radio-group>
                                    </v-card-text>
                                </v-card>
                            </v-menu>
                        </v-card-text>
                    </v-card>
                </v-tab-item>

                <v-tab-item key="webHoliday">
                    <v-card class="py-3">
                        <v-toolbar flat color="white">
                            <v-toolbar-title class="grey--text">休診日（ウェブ予約）の設定</v-toolbar-title>
                            <v-divider
                                    class="ml-4"
                                    inset
                                    vertical
                            ></v-divider>
                        </v-toolbar>
                        <v-card-text>

                            <v-btn
                                    color="primary"
                                    outline
                                    class="mb-3"
                                    v-on:click="copyClinicHolidays"
                            >
                                医院の休診日をコピー
                                <v-icon right>save_alt</v-icon>
                            </v-btn>


                            <full-calendar
                                    ref="web_holiday_calendar"
                                    :events="webEvents"
                                    :config="webConfig"
                                    @day-click="webDayClick"
                                    @event-selected="webEventSelected"
                            >
                            </full-calendar>

                            <v-btn
                                    v-show="false"
                                    ref="web_setting_dialog"
                                    @click="webOpenDialog"
                            >
                                setting dialog
                            </v-btn>
                            <v-menu
                                    v-model="webIsOpen"
                                    :position-x="webDialogX"
                                    :position-y="webDialogY"
                                    absolute
                                    offset-y
                            >
                                <v-card>
                                    <v-card-title>
                                        <span class="title primary--text text-xs-center">{{ webEditedItem.start }}</span>
                                    </v-card-title>
                                    <v-card-text class="py-0">
                                        <v-radio-group
                                                v-model="webEditedItem.type"
                                        >
                                            <v-radio label="なし" value="none"></v-radio>
                                            <v-radio label="休診日" value="full"></v-radio>
                                            <v-radio label="午前休診日" value="am"></v-radio>
                                            <v-radio label="午後休診日" value="pm"></v-radio>
                                        </v-radio-group>
                                    </v-card-text>
                                </v-card>
                            </v-menu>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-flex>
    </v-layout>
</template>

<script>
    import {FullCalendar} from 'vue-full-calendar'
    import 'fullcalendar/dist/fullcalendar.css'

    export default {
        name: "ClinicHolidays",
        components: {FullCalendar},
        data: () => ({
            activeTab: null,
            defaultConfig: {
                locale: 'ja',
                defaultView: 'month',
                titleFormat: 'M月 - YYYY',
                header: {
                    left: 'title',
                    center: '',
                    right: 'prev,next'
                },
                selectable: false,
                editable: false,
                validRange: function (nowDate) {
                    return {
                        start: nowDate.startOf('month')
                    }
                },
            },

            config: {},
            events: [],
            editedItem: {
                id: '',
                start: '',
                type: ''
            },
            defaultItem: {
                id: '',
                start: '',
                type: 'none'
            },
            isOpen: false,
            dialogX: 0,
            dialogY: 0,

            webConfig: {},
            webEvents: [],
            webEditedItem: {
                id: '',
                start: '',
                type: ''
            },
            webDefaultItem: {
                id: '',
                start: '',
                type: 'none'
            },
            webIsOpen: false,
            webDialogX: 0,
            webDialogY: 0,

        }),
        watch: {
            'editedItem.type'(val) {
                if (this.isOpen) {
                    if (this.editedItem.id !== '' || val !== 'none') {
                        this.save()
                    }
                }
            },
            'webEditedItem.type'(val) {
                if (this.webIsOpen) {
                    if (this.webEditedItem.id !== '' || val !== 'none') {
                        this.webSave()
                    }
                }
            }
        },
        created() {
            this.config = Object.assign({}, this.defaultConfig)
            this.webConfig = Object.assign({}, this.defaultConfig)
            this.loadHolidays()
            this.loadWebHolidays()
        },
        methods: {
            async loadHolidays() {
                await axios
                    .get('/api/clinic-holidays')
                    .then(response => (
                        this.events = response.data
                    ))

                // add each week events.
                // this.$refs.holiday_calendar.fireMethod('renderEvents', this.additionalEvents, true)

            },
            dayClick(date, jsEvent, view) {
                if (!this.isOpen) {
                    const eventExist = this.events.find(function (item) {
                        return date.format() === item.start
                    })

                    if (!eventExist) {
                        this.dialogX = jsEvent.clientX
                        this.dialogY = jsEvent.clientY

                        const item = {
                            id: '',
                            start: date.format(),
                            type: 'none'
                        }
                        this.editedItem = Object.assign({}, item)

                        this.$nextTick(() => {
                            this.$refs.setting_dialog.$el.click()
                        })
                    }
                }
            },
            eventSelected(event, jsEvent, view) {
                if (!this.isOpen) {
                    this.dialogX = jsEvent.clientX
                    this.dialogY = jsEvent.clientY

                    const item = {
                        id: event.id,
                        start: event.start,
                        type: event.type
                    }
                    this.editedItem = Object.assign({}, item)

                    this.$nextTick(() => {
                        this.$refs.setting_dialog.$el.click()
                    })
                }
            },
            async save() {
                await axios
                    .post('/api/clinic-holidays', this.editedItem)
                    .then(response => (
                        console.log(response.status)
                    ))
                    .catch(error => (
                        console.log(error.response)
                    ))

                this.loadHolidays()
            },
            openDialog(event) {
                event.preventDefault()
                this.isOpen = false
                this.$nextTick(() => {
                    this.isOpen = true
                })
            },

            async loadWebHolidays() {
                await axios
                    .get('/api/clinic-web-holidays')
                    .then(response => (
                        this.webEvents = response.data
                    ))

                // add each week events.
                // this.$refs.holiday_calendar.fireMethod('renderEvents', this.additionalEvents, true)

            },
            webDayClick(date, jsEvent, view) {
                if (!this.webIsOpen) {
                    const eventExist = this.webEvents.find(function (item) {
                        return date.format() === item.start
                    })

                    if (!eventExist) {
                        this.webDialogX = jsEvent.clientX
                        this.webDialogY = jsEvent.clientY

                        const item = {
                            id: '',
                            start: date.format(),
                            type: 'none'
                        }
                        this.webEditedItem = Object.assign({}, item)

                        this.$nextTick(() => {
                            this.$refs.web_setting_dialog.$el.click()
                        })
                    }
                }
            },
            webEventSelected(event, jsEvent, view) {
                if (!this.webIsOpen) {
                    this.webDialogX = jsEvent.clientX
                    this.webDialogY = jsEvent.clientY

                    const item = {
                        id: event.id,
                        start: event.start,
                        type: event.type
                    }
                    this.webEditedItem = Object.assign({}, item)

                    this.$nextTick(() => {
                        this.$refs.web_setting_dialog.$el.click()
                    })
                }
            },
            async webSave() {
                await axios
                    .post('/api/clinic-web-holidays', this.webEditedItem)
                    .then(response => (
                        console.log(response.status)
                    ))
                    .catch(error => (
                        console.log(error.response)
                    ))

                this.loadWebHolidays()
            },
            webOpenDialog(event) {
                event.preventDefault()
                this.webIsOpen = false
                this.$nextTick(() => {
                    this.webIsOpen = true
                })
            },
            async copyClinicHolidays() {
                await axios
                    .get('/api/clinic-web-holidays/copy')
                    .then(response => (
                        console.log(response.status)
                    ))
                    .catch(error => (
                        console.log(error.response)
                    ))

                this.loadWebHolidays()
            }
        }
    }
</script>

<style scoped>
    .v-input--selection-controls {
        margin-top: 0;
    }
</style>

<style>
    .clinic-holidays .fc-header-toolbar h2 {
        padding-left: 8px;
        font-size: 2.5rem;
        font-weight: normal;
        color: #aab2bd;
        line-height: 1;
    }

    .clinic-holidays .fc-unthemed td.fc-today {
        background: transparent;
    }
</style>
