<template>
    <v-app id="home">
        <v-navigation-drawer
                :clipped="$vuetify.breakpoint.lgAndUp"
                v-model="drawer"
                fixed
                app
        >
            <v-layout
                    justify-center
                    pt-4
            >
                <router-link to="/">
                    <img class="logo-mark" src="/svg/mark_color.svg">
                </router-link>
            </v-layout>
            <v-list>
                <v-list-group
                        v-for="item in items"
                        v-model="item.active"
                        :key="item.title"
                        :prepend-icon="item.action"
                        no-action
                >
                    <v-list-tile slot="activator">
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile
                            v-for="subItem in item.items"
                            :key="subItem.title"
                            :to="subItem.to"
                    >
                        <v-list-tile-content>
                            <v-list-tile-title>{{ subItem.title }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list-group>
            </v-list>
            <v-divider class="mx-3"></v-divider>
            <v-layout class="mx-3 mt-1">
                <v-flex xs12>
                    <v-subheader>
                        <v-icon small class="mr-1">search</v-icon>
                        患者検索
                    </v-subheader>
                    <v-text-field
                            v-model="navigationPatientKeyword"
                            @keyup.enter="navigationPatientSearch"
                            label="カルテ番号・名前など"
                            single-line
                            solo
                            class="body-1"
                            slot="activator"
                            id="nav-pat-text"
                    >
                    </v-text-field>

                    <v-menu
                            v-model="navigationPatientMenu"
                            absolute
                            :position-x="navigationPatientMenuX"
                            :position-y="navigationPatientMenuY"
                    >
                        <v-list two-line>
                            <v-list-tile
                                    v-for="result in navigationPatientItems"
                                    :key="result.id"
                                    :to="'/' + result.id"
                            >
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ result.name }}</v-list-tile-title>
                                    <v-list-tile-sub-title>{{ result.karte_number }} {{ result.tel }} {{ result.email }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-menu>

                </v-flex>
            </v-layout>
            <v-layout class="mx-3 mb-3">
                <v-flex xs12>
                    <v-subheader>
                        <v-icon small class="mr-1">calendar_today</v-icon>
                        診療カレンダー
                    </v-subheader>
                    <v-date-picker
                            v-model="navigationCalendarDate"
                            :events="navigationCalendarIsHoliday"
                            :event-color="navigationCalendarHolidayColor"
                            @input="navigationCalendarSelectedDate"
                            color="primary"
                            full-width
                            no-title
                            locale="ja-jp"
                            :day-format="date => new Date(date).getDate()"
                            class="elevation-1"
                    >
                    </v-date-picker>
                </v-flex>
            </v-layout>
        </v-navigation-drawer>
        <v-toolbar
                :clipped-left="$vuetify.breakpoint.lgAndUp"
                color="primary"
                dark
                app
                fixed
        >
            <v-toolbar-title style="width: 360px" class="ml-0 pl-3">
                <v-layout align-center>
                    <v-flex class="mr-3" style="flex: 0;">
                        <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                    </v-flex>
                    <v-flex class="py-2" style="flex: 0;">
                        <router-link to="/">
                            <v-img
                                    src="/svg/logo_white.svg"
                                    aspect-ratio="4.8"
                                    contain
                                    width="160px"
                            >
                            </v-img>
                        </router-link>
                    </v-flex>
                </v-layout>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu
                    transition="slide-y-transition"
                    offset-y
            >
                <v-btn slot="activator" icon>
                    <v-badge
                            v-model="mailInboxesCount.count_total"
                            overlap
                            color="error"
                            class="toolbar-mailinboxes"
                    >
                        <span slot="badge">!</span>
                        <v-icon>
                            email
                        </v-icon>
                    </v-badge>
                </v-btn>
                <v-card>
                    <v-list>
                        <v-card-title class="justify-center py-1">未読件数</v-card-title>
                        <v-divider></v-divider>
                        <v-list-tile style="height: 2rem;">
                            <v-list-tile-content class="caption">予約</v-list-tile-content>
                            <v-list-tile-content class="caption align-end"><span class="title">{{ mailInboxesCount.count_new }}</span>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="height: 2rem;">
                            <v-list-tile-content class="caption">変更</v-list-tile-content>
                            <v-list-tile-content class="caption align-end"><span class="title">{{ mailInboxesCount.count_change }}</span>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="height: 2rem;">
                            <v-list-tile-content class="caption">キャンセル</v-list-tile-content>
                            <v-list-tile-content class="caption align-end"><span class="title">{{ mailInboxesCount.count_cancel }}</span>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="height: 2rem;">
                            <v-list-tile-content class="caption">エラー</v-list-tile-content>
                            <v-list-tile-content class="caption align-end"><span class="title">{{ mailInboxesCount.count_error }}</span>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-divider class="mt-3"></v-divider>
                        <v-btn flat color="accent" to="/mailbox/inbox">
                            受信メール一覧
                        </v-btn>
                    </v-list>
                </v-card>
            </v-menu>
            <v-btn flat icon>
                <v-tooltip bottom>
                    <v-icon
                            slot="activator"
                    >
                        notifications
                    </v-icon>
                    <span>お知らせ</span>
                </v-tooltip>
            </v-btn>
            <v-btn flat icon>
                <v-tooltip bottom>
                    <v-icon
                            slot="activator"
                    >
                        help_outline
                    </v-icon>
                    <span>ヘルプ</span>
                </v-tooltip>
            </v-btn>
            <v-menu
                    transition="slide-y-transition"
                    offset-y
            >
                <v-btn slot="activator" icon>
                    <v-tooltip bottom>
                        <v-icon
                                slot="activator"
                        >
                            settings
                        </v-icon>
                        <span>設定</span>
                    </v-tooltip>
                </v-btn>
                <v-layout class="pa-2 white setting-menus">
                    <v-flex>
                        <v-list subheader>
                            <v-subheader>
                                <v-icon small class="mr-1">location_city</v-icon>
                                医院設定
                            </v-subheader>
                            <v-list-tile to="/clinic/base">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        基本情報
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile to="/clinic/hours">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        診療時間
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile to="/clinic/holidays">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        休診日
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                    <v-flex>
                        <v-list subheader>
                            <v-subheader>
                                <v-icon small class="mr-1">table_chart</v-icon>
                                予約設定
                            </v-subheader>
                            <v-list-tile to="/appointment-setting/table">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        予約表設定
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile to="/appointment-setting/treatments">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        診療内容
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                    <v-flex>
                        <v-list subheader>
                            <v-subheader>
                                <v-icon small class="mr-1">cloud_done</v-icon>
                                ウェブ予約設定
                            </v-subheader>
                            <v-list-tile to="/web-appointment-setting/base">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        基本設定
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile to="/web-appointment-setting/mail">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        メール設定
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile to="/web-appointment-setting/page">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        ページ設定
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile to="/web-appointment-setting/by-patient">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        患者別設定
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                    <v-flex>
                        <v-list subheader>
                            <v-subheader>
                                <v-icon small class="mr-1">contact_mail</v-icon>
                                メール設定
                            </v-subheader>
                            <v-list-tile to="/mail/template">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        メールテンプレート
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile to="/mail/reminder">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        リマインダー設定
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile to="/mail/recall">
                                <v-list-tile-content>
                                    <v-list-tile-title class="body-1">
                                        <v-icon small>keyboard_arrow_right</v-icon>
                                        リコール設定
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                </v-layout>
            </v-menu>
            <v-menu
                    transition="slide-x-reverse-transition"
                    offset-y
            >
                <v-btn
                        slot="activator"
                        flat
                >
                    <v-icon class="mr-2">location_city</v-icon>
                    {{ clinic.name }}
                </v-btn>
                <v-list>
                    <v-list-tile
                            to="/account"
                    >
                        <v-list-tile-avatar>
                            <v-icon>account_circle</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title class="body-1">アカウント</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile
                            v-on:click="logout"
                    >
                        <v-list-tile-avatar>
                            <v-icon>exit_to_app</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title class="body-1">ログアウト</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-menu>

        </v-toolbar>
        <v-content>
            <v-container fluid fill-height>
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        name: "HomeComponent",
        data: () => ({
            drawer: null,
            clinic: [],
            mailInboxesCount: {
                count_total: false
            },
            items: [
                // {
                //     action: 'location_city',
                //     title: '医院設定',
                //     items: [
                //         {title: '基本情報', to: '/clinic/base'},
                //         {title: '診療時間', to: '/clinic/hours'},
                //         {title: '休診日', to: '/clinic/holidays'}
                //     ]
                // },
                // {
                //     action: 'table_chart',
                //     title: '予約設定',
                //     items: [
                //         {title: '予約表設定', to: '/appointment-setting/table'},
                //         {title: '診療内容', to: '/appointment-setting/treatments'}
                //     ]
                // },
                // {
                //     action: 'cloud_done',
                //     title: 'ウェブ予約設定',
                //     items: [
                //         {title: '基本設定', to: '/web-appointment-setting/base'},
                //         {title: 'メール設定', to: '/web-appointment-setting/mail'},
                //         {title: 'ページ設定', to: '/web-appointment-setting/page'},
                //         {title: '患者別設定', to: '/web-appointment-setting/by-patient'}
                //     ]
                // },
                // {
                //     action: 'contact_mail',
                //     title: 'メール設定',
                //     items: [
                //         {title: 'メールテンプレート', to: '/mail/template'},
                //         {title: 'リマインダー設定', to: '/mail/reminder'},
                //         {title: 'リコール設定', to: '/mail/recall'}
                //     ]
                // },
                {
                    action: 'mail',
                    title: 'メールボックス',
                    items: [
                        {title: '予約確定待ち', to: '/mailbox/temporary'},
                        {title: '受信メール', to: '/mailbox/inbox'},
                        {title: '送信メール', to: '/mailbox/outbox'}
                    ]
                },
                {
                    action: 'face',
                    title: '患者管理',
                    items: [
                        {title: '新規登録', to: '/patient/new'},
                        {title: '一覧', to: '/patient/index'},
                        {title: '一括登録', to: '/patient/import'}
                    ]
                }
            ],


            navigationPatientKeyword: '',
            navigationPatientItems: [],
            navigationPatientMenu: false,
            navigationPatientMenuX: 0,
            navigationPatientMenuY: 0,

            navigationCalendarDate: new Date().toISOString().substr(0, 10),
            navigationCalendarHolidays: []

        }),
        created() {
            this.initializeToolbarClinic()
            this.initializeToolbarMailinbox()
            this.initializeNavigationHolidays()

            this.$eventHub.$on('init-toolbar-mailinbox', this.initializeToolbarMailinbox)
        },
        beforeDestroy() {
            this.$eventHub.$off('init-toolbar-mailinbox')
        },
        methods: {
            initializeToolbarClinic() {
                axios
                    .get('/api/clinic')
                    .then(response => (
                        this.clinic = response.data
                    ))

            },
            initializeToolbarMailinbox() {
                axios
                    .get('/api/mail-inboxes/counter')
                    .then(response => (
                        this.mailInboxesCount = response.data
                    ))
            },
            initializeNavigationHolidays() {
                axios
                    .get('/api/clinic-holidays/mini')
                    .then(response => (
                        this.navigationCalendarHolidays = response.data
                    ))
            },
            async navigationPatientSearch() {
                await axios
                    .get('/api/patients/search/' + this.navigationPatientKeyword)
                    .then(response => (
                        this.navigationPatientItems = response.data
                    ))

                this.navigationPatientMenuX = $('#nav-pat-text').offset().left + 285
                this.navigationPatientMenuY = $('#nav-pat-text').offset().top + 45

                this.navigationPatientMenu = true
            },
            navigationCalendarIsHoliday(date) {
                if (date in this.navigationCalendarHolidays) {
                    return true
                }
                return false
            },
            navigationCalendarHolidayColor(date) {
                if (date in this.navigationCalendarHolidays) {
                    return this.navigationCalendarHolidays[date]
                }
                return false
            },
            navigationCalendarSelectedDate(date) {

                console.log('call selected: ' + date)

            },
            logout: function () {
                axios
                    .post('/api/logout')
                    .then(response => (
                        console.log('logged out.')
                    ))
                    .catch(error => (
                        location.href = '/login'
                    ))
            }
        }
    }
</script>

<style scoped>
@media print {
        aside, nav { display: none !important; }
        footer { display: none !important; }
        body * { color: black !important; background-color: white !important; }
        h1, h2, h3, h4, h5, h6 {
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        main {
            padding: 0 !important;
        }

        .layout, .card, .card__content {
            box-shadow: unset !important;
            -webkit-box-shadow: unset !important;
            border-radius: unset !important;
        }
        .carousel__left, .carousel__right, .carousel__controls {
            display: none !important;
        }
        .grey--text{ color: black !important; }
        .chip--outline{ border-color: black !important; }
        .list{ background: unset !important; }
        .elevation-1 { box-shadow: unset !important; }        
        .elevation-2 { box-shadow: unset !important; }        
        .elevation-3 { box-shadow: unset !important; }        

        a:link          { color: black !important; text-decoration:none; }
        a:visited       { color: black !important; text-decoration:none; }
        a:hover         { color: black !important; text-decoration:none; }
        a:active        { color: black !important; text-decoration:none; }
        /* Utility Classes */
        .print-together {
            page-break-inside: avoid;
        }
        /* Use .print-no to hide buttons or controls. Alternately you can use display: none on all control classes like carousel above. */
        .print-no { display: none !important; }
        .print-break-after { page-break-after: always !important; }
    }
    @media screen {
        .screen-no{ display: none !important }
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .3s ease;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .logo-mark {
        width: 36px;
        height: 35px;
    }
</style>
<style>
    .toolbar-mailinboxes .v-badge__badge {
        width: 16px;
        height: 16px;
        top: -5px;
    }

    .toolbar-mailinboxes .v-badge__badge span {
        font-size: 12px;
    }

    .setting-menus .v-list__tile {
        height: 30px;
    }
</style>
