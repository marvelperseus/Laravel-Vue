<template>
    <v-app id="appointment">
        <v-content class="v-content-main-tag">
            <template v-if="loading">
                <v-layout
                        align-center
                        justify-center
                        style="height: 70vh;"
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
                <header-component v-bind:clinic="clinic"></header-component>
                <v-container fluid>
                    <v-layout align-start justify-center>
                        <template v-if="isLogin">

                            <v-flex xs12 md6>
                                <v-card class="pa-5">
                                    <v-card-text>
                                        <p>登録メールアドレス + 生年月日</p>

                                        <v-btn color="accent" @click="logIn">ログイン</v-btn>

                                    </v-card-text>
                                </v-card>
                            </v-flex>

                        </template>
                        <template v-else>
                            <v-flex xs12 md8>
                                <v-stepper v-model="stepEl">
                                    <v-stepper-header>
                                        <v-stepper-step
                                                :complete="stepEl > 1"
                                                step="1"
                                                :edit-icon="$vuetify.icons.complete"
                                        >
                                            メニュー
                                        </v-stepper-step>
                                        <v-divider></v-divider>
                                        <v-stepper-step
                                                :complete="stepEl > 2"
                                                step="2"
                                                :edit-icon="$vuetify.icons.complete"
                                        >
                                            日時を選ぶ
                                        </v-stepper-step>
                                        <v-divider></v-divider>
                                        <v-stepper-step
                                                :complete="stepEl > 3"
                                                step="3"
                                                :edit-icon="$vuetify.icons.complete"
                                        >
                                            患者さま情報
                                        </v-stepper-step>
                                        <v-divider></v-divider>
                                        <v-stepper-step
                                                :complete="stepEl > 4"
                                                step="4"
                                                :edit-icon="$vuetify.icons.complete"
                                        >
                                            完了！
                                        </v-stepper-step>
                                    </v-stepper-header>
                                    <v-stepper-items>

                                        <v-stepper-content step="1">
                                            <v-card class="pa-5">
                                                <v-card-title class="justify-center title stepper-content-title">
                                                    <v-icon class="mr-2">event_note</v-icon>
                                                    受診されたい診療メニューを選択してください。
                                                </v-card-title>
                                                <v-card-title class="justify-left title stepper-content-title">
                                                    | 当院をはじめてご利用される方
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-btn
                                                            v-for="item in clinic.treatments"
                                                            :key="item.id"
                                                            @click="selectedTreatment(item)"
                                                            color="accent"
                                                            large
                                                            block
                                                    >
                                                        {{ item.treatment }}（{{ item.time }}分）
                                                        <v-icon right>chevron_right</v-icon>
                                                    </v-btn>
                                                </v-card-text>
                                                <v-card-actions v-if="isLogin" class="justify-center action-buttons">
                                                    <v-btn
                                                            large
                                                            color="primary"
                                                            class="mx-3"
                                                            @click="onList()"
                                                    >
                                                        治療中の方
                                                        <v-icon right>chevron_right</v-icon>
                                                    </v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-stepper-content>

                                        <v-stepper-content step="2" class="calendar-content">
                                            <v-card class="pa-5 pt-0">
                                                <v-card-text>
                                                    <v-icon small class="mr-2">event_note</v-icon>
                                                    診療メニュー：{{ selectedItem.treatment }}（{{ selectedItem.treatment_time
                                                    }}分）
                                                </v-card-text>
                                                <v-divider></v-divider>
                                                <v-card-title class="justify-center title stepper-content-title">
                                                    <v-icon class="mr-2">calendar_today</v-icon>
                                                    予約希望日時を選択してください。
                                                </v-card-title>
                                                <v-card-text class="web-appointment-steps">
                                                    <template v-if="calendarLoading">
                                                        <v-progress-linear :indeterminate="true"
                                                                           color="accent"></v-progress-linear>
                                                    </template>
                                                    <template v-else>
                                                        <full-calendar
                                                                ref="appointment_table"
                                                                :events="availability"
                                                                :config="config"
                                                                @event-selected="eventSelected"
                                                                @event-after-render="eventAfterRender"
                                                                @view-render="viewRender"
                                                        >
                                                        </full-calendar>
                                                    </template>
                                                </v-card-text>
                                            </v-card>
                                        </v-stepper-content>

                                        <v-stepper-content step="3">
                                            <v-card class="pa-5">
                                                <v-card-title class="justify-center title stepper-content-title">
                                                    <v-icon class="mr-2">edit</v-icon>
                                                    患者さま情報を入力してください。
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-text-field
                                                            prepend-icon="event_note"
                                                            label="診療メニュー"
                                                            :value="selectedItem.treatment+'（'+selectedItem.treatment_time+'分）'"
                                                            readonly
                                                    >
                                                    </v-text-field>

                                                    <v-text-field
                                                            prepend-icon="access_time"
                                                            label="ご予約日時"
                                                            :value="selectedItem.time_label"
                                                            readonly
                                                    >
                                                    </v-text-field>

                                                    <v-layout wrap class="mt-5">
                                                        <v-flex xs12 md5>
                                                            <v-text-field
                                                                    v-model="selectedItem.patient_name"
                                                                    label="お名前"
                                                                    :error-messages="validationErrors.patient_name"
                                                                    class="pt-0"
                                                            >
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs6 offset-xs1>
                                                            <template v-if="displayForm.patient_name_kana">
                                                                <v-text-field
                                                                        v-model="selectedItem.patient_name_kana"
                                                                        label="お名前（フリガナ）"
                                                                        :error-messages="validationErrors.patient_name_kana"
                                                                        class="pt-0"
                                                                >
                                                                </v-text-field>
                                                            </template>
                                                        </v-flex>
                                                    </v-layout>

                                                    <v-text-field
                                                            v-model="selectedItem.patient_tel"
                                                            prepend-icon="call"
                                                            label="電話番号"
                                                            type="tel"
                                                            :error-messages="validationErrors.patient_tel"
                                                    >
                                                    </v-text-field>

                                                    <v-text-field
                                                            v-model="selectedItem.patient_email"
                                                            prepend-icon="mail_outline"
                                                            label="E-mail"
                                                            type="email"
                                                            :error-messages="validationErrors.patient_email"
                                                    >
                                                    </v-text-field>

                                                    <template v-if="displayForm.patient_gender">
                                                        <v-subheader class="pl-0 mt-3">
                                                            <v-icon class="mr-1">supervisor_account</v-icon>
                                                            性別
                                                        </v-subheader>
                                                        <v-radio-group
                                                                v-model="selectedItem.patient_gender"
                                                                row
                                                        >
                                                            <v-radio label="女" value="female"></v-radio>
                                                            <v-radio label="男" value="male"></v-radio>
                                                        </v-radio-group>
                                                    </template>

                                                    <v-text-field
                                                            v-model="selectedItem.patient_birthday"
                                                            prepend-icon="cake"
                                                            label="生年月日"
                                                            placeholder="2000-01-01"
                                                            :error-messages="validationErrors.patient_birthday"
                                                    >
                                                    </v-text-field>

                                                    <template v-if="displayForm.patient_zip_address">
                                                        <v-layout
                                                                wrap
                                                                align-center
                                                                class="my-3"
                                                        >
                                                            <v-flex xs2>
                                                                <v-subheader class="pl-0">
                                                                    <v-icon class="mr-1">location_on</v-icon>
                                                                    ご住所
                                                                </v-subheader>
                                                            </v-flex>
                                                            <v-flex xs10>
                                                                <v-text-field
                                                                        v-model="selectedItem.patient_zip"
                                                                        label="郵便番号"
                                                                        :error-messages="validationErrors.patient_zip"
                                                                >
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12>
                                                                <v-text-field
                                                                        v-model="selectedItem.patient_address"
                                                                        label="住所"
                                                                        :error-messages="validationErrors.patient_address"
                                                                        class="pt-0"
                                                                >
                                                                </v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                    </template>

                                                    <template v-if="displayForm.note">
                                                        <v-textarea
                                                                v-model="selectedItem.note"
                                                                prepend-icon="comment"
                                                                label="ご要望・ご質問など"
                                                                :error-messages="validationErrors.note"
                                                                class="mt-3"
                                                        ></v-textarea>
                                                    </template>
                                                </v-card-text>

                                                <v-card-text class="mt-3">
                                                    <blockquote class="body-1">
                                                        <span class="subheading accent--text">ご予約には受信可能なメールアドレスが必要です</span><br>
                                                        当システムからのメールが「迷惑メール」に入ってしまう可能性があります。<br>
                                                        万が一メールが届かない場合は迷惑メール内をご確認ください。
                                                    </blockquote>
                                                </v-card-text>

                                                <v-card-actions class="justify-center">
                                                    <v-btn large color="accent" @click="send">予約する</v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-stepper-content>

                                        <v-stepper-content step="4">
                                            <v-card class="pa-5">
                                                <v-card-title class="justify-center title stepper-content-title">
                                                    <v-icon class="mr-2">send</v-icon>
                                                    予約内容を送信しました
                                                </v-card-title>
                                                <v-card-text>
                                                    <p>入力されたメールアドレス宛てに予約内容の確認メールを送信しました。</p>
                                                    <p>{{ clinic.web_appointment_page.end_message }}</p>
                                                </v-card-text>
                                            </v-card>
                                        </v-stepper-content>
                                    </v-stepper-items>
                                </v-stepper>
                            </v-flex>
                        </template>
                    </v-layout>
                </v-container>
                <footer-component v-bind:clinic="clinic"></footer-component>
            </template>
        </v-content>
    </v-app>
</template>

<script>
  import Header from './Header.vue'
  import Footer from './Footer.vue'
  import {FullCalendar} from 'vue-full-calendar'
  import 'fullcalendar/dist/fullcalendar.css'
  import 'fullcalendar-scheduler/dist/scheduler.css'

  export default {
    name: "StepsComponent",
    components: {
      'header-component': Header,
      'footer-component': Footer,
      FullCalendar
    },
    props: ['appkey', 'login'],
    data: () => ({
      loading: true,
      isLogin: false,
      clinic: [],
      displayForm: {
        patient_name_kana: false,
        patient_gender: false,
        patient_zip_address: false,
        note: false
      },

      stepEl: 0,
      selectedItem: {
        treatment_id: null,
        treatment: '',
        treatment_time: 0,
        start: '',
        end: '',
        time_label: '',
        unit: '',
        patient_name: '',
        patient_name_kana: '',
        patient_tel: '',
        patient_email: '',
        patient_gender: 'female',
        patient_birthday: '',
        patient_zip: '',
        patient_address: '',
        note: ''
      },
      validationErrors: [],

      calendarLoading: true,
      config: {
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        locale: 'ja',
        defaultView: 'month',
        titleFormat: 'MM/DD',
        header: {
          left: 'month',
          center: 'title',
          right: 'prev,next'
        },
        views: {
          month: {
            titleFormat: 'M月 - YYYY'
          },
          listDay: {
            titleFormat: 'MM/DD（ddd）',
            columnHeader: false
          }
        },
        buttonText: {
          today: '今日',
          month: '←',
          week: '週',
          day: '日'
        },
        noEventsMessage: '予約可能な時間帯はありません',
        allDaySlot: false,
        nowIndicator: false,
        slotDuration: '00:30:00',
        slotLabelFormat: 'H:mm',
        businessHours: [],
        minTime: '08:00:00',
        maxTime: '20:00:00',
        selectable: false,
        editable: false,
        validRange: function (nowDate) {
          return {
            start: nowDate,
            end: nowDate.clone().add(2, 'months')
          }
        },
        height: 'auto'
      },
      availability: [],
      availabilityMonth: [],
      availabilityAgenda: [],
      autoRegist: ''
    }),
    created() {
      this.initialize()
      this.isLogin = this.login > 0 ? true : false
    },
    methods: {
      async initialize() {
        await axios
          .get('/api/web/appointment/clinic/' + this.appkey)
          .then(response => (
            this.clinic = response.data
          ))

        const formObjects = this.clinic.web_appointment_page.form_objects
        this.displayForm.patient_name_kana = formObjects.some(value => value === 'patient_name_kana')
        this.displayForm.patient_gender = formObjects.some(value => value === 'patient_gender')
        this.displayForm.patient_zip_address = formObjects.some(value => value === 'patient_zip_address')
        this.displayForm.note = formObjects.some(value => value === 'note')

        this.loading = false
      },
      logIn() {

        this.isLogin = false

      },
      async selectedTreatment(item) {

        this.stepEl = 2;

        this.selectedItem.treatment_id = item.id
        this.selectedItem.treatment = item.treatment
        this.selectedItem.treatment_time = item.time

        let data = null
        await axios
          .post('/api/web/appointment/calendar/' + this.appkey, this.selectedItem)
          .then(response => (
            data = response.data
          ))

        this.config.slotDuration = data.slot_duration
        this.config.minTime = data.min_time
        this.config.maxTime = data.max_time
        this.config.validRange = data.valid_range
        this.availability = this.availabilityMonth = data.availability_month
        this.availabilityAgenda = data.availability_agenda
        this.autoRegist = data.auto_regist

        this.calendarLoading = false;
      },
      viewRender(view, element) {
        if (view.calendar.el.fullCalendar('getView').type === 'month') {
          if (this.availabilityMonth.length > 0) {
            this.availability = this.availabilityMonth
          }
          $("button.fc-month-button").hide()
        } else {
          $("button.fc-month-button").show()
        }
      },
      eventSelected(event, jsEvent, view) {
        if (view.calendar.el.fullCalendar('getView').type === 'month') {
          // Event Reset.
          view.calendar.el.fullCalendar('removeEvents')
          this.availability = this.availabilityAgenda
          view.calendar.el.fullCalendar('refetchEvents')
          // Change List View.
          view.calendar.el.fullCalendar('changeView', 'listDay', event.start.format('YYYY-MM-DD'))
        } else {

          if (event.title === 'no') {
            return false
          }

          this.selectedItem.start = event.start.format('YYYY-MM-DD H:mm')
          this.selectedItem.end = event.end.format('YYYY-MM-DD H:mm')
          this.selectedItem.unit = event.unit
          this.selectedItem.time_label = event.start.format('YYYY年M月D日（dd）H:mm〜') + event.real_end

          this.stepEl = 3;
        }
      },
      eventAfterRender(event, element, view) {
        const viewType = view.calendar.el.fullCalendar('getView').type
        if (viewType === 'month') {
          if (event.title === 'yes') {
            element.html('<div class="fc-content"><span class="fc-title"><i aria-hidden="true" class="v-icon material-icons">radio_button_unchecked</i></span></div>')
          } else if (event.title === 'no') {
            // element.parent().html('<div class="fc-content" style="color: ' + event.textColor + ';"><span class="fc-title"><i aria-hidden="true" class="v-icon material-icons">remove</i></span></div>')
            element.parent().html('<div class="fc-content is-no-event" style="color: ' + event.textColor + ';"><span class="fc-title">-</span></div>')
          }
        } else {
          if (event.title === 'yes') {
            element.html('<td class="fc-list-item-title fc-widget-content" style="color: ' + event.textColor + ';"><div class="fc-list-item-title-time">' + event.start.format('H:mm') + '</div><a class="fc-list-item-title-icon"><i aria-hidden="true" class="v-icon material-icons">radio_button_unchecked</i></a></td>')
          } else if (event.title === 'no') {
            element.html('<td class="fc-list-item-title fc-widget-content is-no-event" style="color: ' + event.textColor + ';"><div class="fc-list-item-title-time">' + event.start.format('H:mm') + '</div><span class="fc-list-item-title-icon">-</span></td>')
          }
        }
      },

      async send() {
        this.validationErrors = []

        let apiUrl = '/api/web/appointment/'
        if (this.autoRegist === 'OFF') {
          apiUrl = '/api/web/appointment/temporary/'
        }

        await axios
          .post(apiUrl + this.appkey, this.selectedItem)
          .then(response => (
            console.log(response.status)
          ))
          .catch(error => (
            this.validationErrors = error.response.data.errors
          ))

        if (this.validationErrors.length === 0) {
          this.stepEl = 4;
        }
      },

      onList() {
        const values = location.pathname.split('/');
        values.pop();
        location.pathname = values.join('/') + '/list';
      }
    }
  }
</script>

<style scoped>
    .is-pre-wrap {
        white-space: pre-wrap;
    }

    .v-input--selection-controls {
        margin-top: 0;
    }

    .stepper-content-title {
        line-height: 1.4 !important;
    }

    .pa-5 {
        padding: 0 24px 48px 24px !important;
    }

    @media only screen and (max-width: 768px) {
        .v-content-main-tag {
            padding-top: 56px !important;
        }

        .stepper-content-title {
            font-size: 16px !important;
        }

        .pa-5 {
            padding: 0 0 48px 0 !important;
        }

        .v-stepper__header {
            height: auto;
        }

        .v-stepper__header .v-stepper__step {
            padding: 12px 24px;
        }

        .v-stepper__content {
            padding: 24px 0 16px;
        }

        .v-stepper__content.calendar-content {
            padding: 3px 0 16px;
        }

        .v-stepper__content.calendar-content .stepper-content-title {
            padding-bottom: 0;
        }
    }
</style>

<style>
    .web-appointment-steps th.fc-day-header {
        background-color: #ededed;
    }

    .web-appointment-steps th.fc-day-header.fc-sun {
        background-color: #FCE4E6;
        color: #C6374A;
    }

    .web-appointment-steps th.fc-day-header.fc-sat {
        background-color: #D1E9F0;
        color: #48A2BC;
    }

    .web-appointment-steps .fc-list-heading {
        display: none;
    }

    .web-appointment-steps td.fc-day-top {
        text-align: center;
    }

    .web-appointment-steps td.fc-day-top .fc-day-number {
        float: none !important;
    }

    .web-appointment-steps .fc-content {
        text-align: center;
    }

    .web-appointment-steps .fc-content .fc-title i,
    .web-appointment-steps .fc-list-item-title a.fc-list-item-title-icon i {
        font-size: 2.5rem;
    }

    .web-appointment-steps .fc-content.is-no-event .fc-title,
    .web-appointment-steps .fc-list-item-title.is-no-event .fc-list-item-title-icon {
        font-size: 2.5rem;
        line-height: 1;
    }

    .web-appointment-steps .fc-list-view {
        border-width: 1px 0 0 1px;
    }

    .web-appointment-steps .fc-list-table tbody {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }

    .web-appointment-steps .fc-list-table tbody tr.fc-list-item {
        flex-basis: 20%;
    }

    .web-appointment-steps .fc-list-item-title {
        display: block;
        text-align: center;
        height: 100%;
        box-sizing: border-box;
        border-width: 0 1px 1px 0;
    }

    .web-appointment-steps .fc-list-item-title .fc-list-item-title-time {
        margin-bottom: 4px;
        color: #212121;
    }

    .web-appointment-steps .fc-list-empty-wrap2 {
        position: static;
        border: 1px solid #ddd;
        border-width: 0 1px 1px 0;
    }

    .web-appointment-steps .fc-list-empty {
        padding: 3rem 0;
        background-color: #fff;
    }

    @media only screen and (max-width: 767px) {
        .web-appointment-steps .fc-toolbar.fc-header-toolbar {
            margin-bottom: .1em;
        }

        .web-appointment-steps .fc-basic-view .fc-body .fc-row {
            min-height: 3.6em;
        }

        .web-appointment-steps .fc-content .fc-title i,
        .web-appointment-steps .fc-content.is-no-event .fc-title,
        .web-appointment-steps .fc-list-item-title a.fc-list-item-title-icon i,
        .web-appointment-steps .fc-list-item-title.is-no-event .fc-list-item-title-icon {
            font-size: 1.5rem;
        }

        .web-appointment-steps .fc-list-item-title {
            padding-left: 0px;
            padding-right: 0px;
        }

        .web-appointment-steps .fc-list-table tbody tr.fc-list-item {
            flex-basis: 20%;
        }
    }
</style>
