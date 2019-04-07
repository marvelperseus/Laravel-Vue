<template>
    <v-layout align-start justify-center>
        <v-flex xs12 md8>

            <v-tabs
                    v-model="activeTab"
                    color="transparent"
                    slider-color="secondary"
                    grow
                    class="mb-3"
            >
                <v-tab key="defaultTime">診療時間</v-tab>
                <v-tab key="webTime">診療時間（ウェブ予約）</v-tab>
                <v-tab key="lunchTime">お昼休み</v-tab>
            </v-tabs>

            <v-tabs-items
                    v-model="activeTab"
            >
                <v-tab-item key="defaultTime">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">診療時間の設定</v-toolbar-title>
                        </v-toolbar>
                        <v-form
                                v-on:submit.prevent="updateClinicTimes"
                                class="pa-4"
                        >
                            <v-card-text>
                                <v-alert
                                        :value="flashMessageClinicTimes"
                                        type="success"
                                        transition="slide-y-reverse-transition"
                                        class="mb-4"
                                >
                                    更新しました
                                </v-alert>

                                <v-subheader class="pl-0">
                                    <v-icon class="mr-1">access_time</v-icon>
                                    基本時間
                                </v-subheader>

                                <template v-if="validationErrorsClinicTimes">
                                    <ul class="mb-2">
                                        <li class="error--text" v-for="message in validationErrorsClinicTimes">{{ message[0] }}</li>
                                    </ul>
                                </template>

                                <v-layout align-center>
                                    <v-flex xs5 class="is-item-centered">
                                        <v-time-picker
                                                v-model="clinicTimes.base.start"
                                                color="secondary"
                                        >
                                        </v-time-picker>
                                    </v-flex>
                                    <v-flex xs2 class="is-item-centered">
                                        <v-icon class="mr-1">arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs5 class="is-item-centered">
                                        <v-time-picker
                                                v-model="clinicTimes.base.end"
                                                color="secondary darken-1"
                                        >
                                        </v-time-picker>
                                    </v-flex>
                                </v-layout>
                                <v-subheader class="pl-0 mt-5">
                                    <v-icon class="mr-1">av_timer</v-icon>
                                    曜日毎の設定
                                    <v-btn
                                            color="primary"
                                            outline
                                            class="ml-4"
                                            v-on:click="copyBaseClinicTimes"
                                    >
                                        基本時間をコピー
                                        <v-icon right>save_alt</v-icon>
                                    </v-btn>
                                </v-subheader>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        日曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_sun_start"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.sun.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.sun.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.sun.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.sun.start"
                                                    v-model="clinicTimes.sun.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_sun_start.save(clinicTimes.sun.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_sun_end"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.sun.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.sun.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.sun.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.sun.end"
                                                    v-model="clinicTimes.sun.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_sun_end.save(clinicTimes.sun.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        月曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_mon_start"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.mon.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.mon.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.mon.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.mon.start"
                                                    v-model="clinicTimes.mon.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_mon_start.save(clinicTimes.mon.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_mon_end"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.mon.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.mon.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.mon.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.mon.end"
                                                    v-model="clinicTimes.mon.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_mon_end.save(clinicTimes.mon.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        火曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_tue_start"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.tue.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.tue.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.tue.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.tue.start"
                                                    v-model="clinicTimes.tue.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_tue_start.save(clinicTimes.tue.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_tue_end"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.tue.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.tue.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.tue.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.tue.end"
                                                    v-model="clinicTimes.tue.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_tue_end.save(clinicTimes.tue.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        水曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_wed_start"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.wed.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.wed.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.wed.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.wed.start"
                                                    v-model="clinicTimes.wed.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_wed_start.save(clinicTimes.wed.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_wed_end"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.wed.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.wed.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.wed.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.wed.end"
                                                    v-model="clinicTimes.wed.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_wed_end.save(clinicTimes.wed.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        木曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_thu_start"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.thu.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.thu.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.thu.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.thu.start"
                                                    v-model="clinicTimes.thu.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_thu_start.save(clinicTimes.thu.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_thu_end"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.thu.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.thu.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.thu.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.thu.end"
                                                    v-model="clinicTimes.thu.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_thu_end.save(clinicTimes.thu.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        金曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_fri_start"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.fri.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.fri.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.fri.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.fri.start"
                                                    v-model="clinicTimes.fri.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_fri_start.save(clinicTimes.fri.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_fri_end"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.fri.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.fri.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.fri.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.fri.end"
                                                    v-model="clinicTimes.fri.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_fri_end.save(clinicTimes.fri.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        土曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_sat_start"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.sat.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.sat.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.sat.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.sat.start"
                                                    v-model="clinicTimes.sat.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_sat_start.save(clinicTimes.sat.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="default_time_menu_sat_end"
                                                :close-on-content-click="false"
                                                v-model="defaultTimeMenu.sat.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicTimes.sat.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicTimes.sat.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="defaultTimeMenu.sat.end"
                                                    v-model="clinicTimes.sat.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.default_time_menu_sat_end.save(clinicTimes.sat.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                            <v-card-actions class="justify-center">
                                <v-btn type="submit" color="accent">更新</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-tab-item>

                <v-tab-item key="webTime">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">ウェブ予約用の診療時間の設定</v-toolbar-title>
                        </v-toolbar>
                        <v-form
                                v-on:submit.prevent="updateClinicWebTimes"
                                class="pa-4"
                        >
                            <v-card-text>
                                <v-alert
                                        :value="flashMessageClinicWebTimes"
                                        type="success"
                                        transition="slide-y-reverse-transition"
                                        class="mb-4"
                                >
                                    更新しました
                                </v-alert>

                                <v-layout justify-center>
                                    <v-flex>
                                        <v-btn
                                                color="primary"
                                                outline
                                                class="ml-4"
                                                v-on:click="copyFromClinicTimesToClinicWebTimes"
                                        >
                                            医院の診療時間をコピー
                                            <v-icon right>save_alt</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>

                                <v-subheader class="pl-0">
                                    <v-icon class="mr-1">access_time</v-icon>
                                    基本時間
                                </v-subheader>

                                <template v-if="validationErrorsClinicWebTimes">
                                    <ul class="mb-2">
                                        <li class="error--text" v-for="message in validationErrorsClinicWebTimes">{{ message[0] }}</li>
                                    </ul>
                                </template>

                                <v-layout align-center>
                                    <v-flex xs5 class="is-item-centered">
                                        <v-time-picker
                                                v-model="clinicWebTimes.base.start"
                                                color="secondary"
                                        >
                                        </v-time-picker>
                                    </v-flex>
                                    <v-flex xs2 class="is-item-centered">
                                        <v-icon class="mr-1">arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs5 class="is-item-centered">
                                        <v-time-picker
                                                v-model="clinicWebTimes.base.end"
                                                color="secondary darken-1"
                                        >
                                        </v-time-picker>
                                    </v-flex>
                                </v-layout>
                                <v-subheader class="pl-0 mt-5">
                                    <v-icon class="mr-1">av_timer</v-icon>
                                    曜日毎の設定
                                    <v-btn
                                            color="primary"
                                            outline
                                            class="ml-4"
                                            v-on:click="copyBaseClinicWebTimes"
                                    >
                                        基本時間をコピー
                                        <v-icon right>save_alt</v-icon>
                                    </v-btn>
                                </v-subheader>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        日曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_sun_start"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.sun.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.sun.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.sun.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.sun.start"
                                                    v-model="clinicWebTimes.sun.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_sun_start.save(clinicWebTimes.sun.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_sun_end"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.sun.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.sun.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.sun.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.sun.end"
                                                    v-model="clinicWebTimes.sun.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_sun_end.save(clinicWebTimes.sun.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        月曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_mon_start"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.mon.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.mon.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.mon.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.mon.start"
                                                    v-model="clinicWebTimes.mon.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_mon_start.save(clinicWebTimes.mon.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_mon_end"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.mon.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.mon.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.mon.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.mon.end"
                                                    v-model="clinicWebTimes.mon.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_mon_end.save(clinicWebTimes.mon.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        火曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_tue_start"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.tue.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.tue.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.tue.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.tue.start"
                                                    v-model="clinicWebTimes.tue.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_tue_start.save(clinicWebTimes.tue.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_tue_end"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.tue.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.tue.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.tue.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.tue.end"
                                                    v-model="clinicWebTimes.tue.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_tue_end.save(clinicWebTimes.tue.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        水曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_wed_start"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.wed.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.wed.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.wed.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.wed.start"
                                                    v-model="clinicWebTimes.wed.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_wed_start.save(clinicWebTimes.wed.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_wed_end"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.wed.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.wed.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.wed.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.wed.end"
                                                    v-model="clinicWebTimes.wed.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_wed_end.save(clinicWebTimes.wed.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        木曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_thu_start"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.thu.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.thu.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.thu.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.thu.start"
                                                    v-model="clinicWebTimes.thu.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_thu_start.save(clinicWebTimes.thu.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_thu_end"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.thu.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.thu.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.thu.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.thu.end"
                                                    v-model="clinicWebTimes.thu.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_thu_end.save(clinicWebTimes.thu.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        金曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_fri_start"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.fri.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.fri.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.fri.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.fri.start"
                                                    v-model="clinicWebTimes.fri.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_fri_start.save(clinicWebTimes.fri.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_fri_end"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.fri.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.fri.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.fri.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.fri.end"
                                                    v-model="clinicWebTimes.fri.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_fri_end.save(clinicWebTimes.fri.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        土曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_sat_start"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.sat.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.sat.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.sat.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.sat.start"
                                                    v-model="clinicWebTimes.sat.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_sat_start.save(clinicWebTimes.sat.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="web_time_menu_sat_end"
                                                :close-on-content-click="false"
                                                v-model="webTimeMenu.sat.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicWebTimes.sat.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicWebTimes.sat.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="webTimeMenu.sat.end"
                                                    v-model="clinicWebTimes.sat.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.web_time_menu_sat_end.save(clinicWebTimes.sat.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                            <v-card-actions class="justify-center">
                                <v-btn type="submit" color="accent">更新</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-tab-item>

                <v-tab-item key="lunchTime">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">お昼休み時間の設定</v-toolbar-title>
                        </v-toolbar>
                        <v-form
                                v-on:submit.prevent="updateClinicLunchbreaks"
                                class="pa-4"
                        >
                            <v-card-text>
                                <v-alert
                                        :value="flashMessageClinicLunchbreaks"
                                        type="success"
                                        transition="slide-y-reverse-transition"
                                        class="mb-4"
                                >
                                    更新しました
                                </v-alert>

                                <v-layout align-center>
                                    <v-flex class="mr-2" style="flex-grow: 0;">
                                        <v-subheader class="pl-0">
                                            <v-icon class="mr-1">remove_red_eye</v-icon>
                                            予約票に表示
                                        </v-subheader>
                                    </v-flex>
                                    <v-flex>
                                        <v-switch
                                                :label="displayFlagLabel"
                                                v-model="clinicLunchbreaks.display_flag"
                                                :true-value="true"
                                                :false-value="false"
                                                color="primary"
                                                class="pt-0 mt-0"
                                                hide-details
                                        >
                                        </v-switch>
                                    </v-flex>
                                </v-layout>

                                <v-subheader class="pl-0 mt-3">
                                    <v-icon class="mr-1">access_time</v-icon>
                                    基本時間
                                </v-subheader>

                                <template v-if="validationErrorsClinicLunchbreaks">
                                    <ul class="mb-2">
                                        <li class="error--text" v-for="message in validationErrorsClinicLunchbreaks">{{ message[0] }}</li>
                                    </ul>
                                </template>

                                <v-layout align-center>
                                    <v-flex xs5 class="is-item-centered">
                                        <v-time-picker
                                                v-model="clinicLunchbreaks.base.start"
                                                color="secondary"
                                        >
                                        </v-time-picker>
                                    </v-flex>
                                    <v-flex xs2 class="is-item-centered">
                                        <v-icon class="mr-1">arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs5 class="is-item-centered">
                                        <v-time-picker
                                                v-model="clinicLunchbreaks.base.end"
                                                color="secondary darken-1"
                                        >
                                        </v-time-picker>
                                    </v-flex>
                                </v-layout>
                                <v-subheader class="pl-0 mt-5">
                                    <v-icon class="mr-1">av_timer</v-icon>
                                    曜日毎の設定
                                    <v-btn
                                            color="primary"
                                            outline
                                            class="ml-4"
                                            v-on:click="copyBaseClinicLunchbreaks"
                                    >
                                        基本時間をコピー
                                        <v-icon right>save_alt</v-icon>
                                    </v-btn>
                                </v-subheader>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        日曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_sun_start"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.sun.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.sun.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.sun.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.sun.start"
                                                    v-model="clinicLunchbreaks.sun.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_sun_start.save(clinicLunchbreaks.sun.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_sun_end"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.sun.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.sun.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.sun.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.sun.end"
                                                    v-model="clinicLunchbreaks.sun.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_sun_end.save(clinicLunchbreaks.sun.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        月曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_mon_start"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.mon.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.mon.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.mon.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.mon.start"
                                                    v-model="clinicLunchbreaks.mon.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_mon_start.save(clinicLunchbreaks.mon.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_mon_end"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.mon.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.mon.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.mon.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.mon.end"
                                                    v-model="clinicLunchbreaks.mon.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_mon_end.save(clinicLunchbreaks.mon.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        火曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_tue_start"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.tue.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.tue.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.tue.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.tue.start"
                                                    v-model="clinicLunchbreaks.tue.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_tue_start.save(clinicLunchbreaks.tue.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_tue_end"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.tue.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.tue.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.tue.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.tue.end"
                                                    v-model="clinicLunchbreaks.tue.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_tue_end.save(clinicLunchbreaks.tue.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        水曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_wed_start"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.wed.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.wed.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.wed.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.wed.start"
                                                    v-model="clinicLunchbreaks.wed.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_wed_start.save(clinicLunchbreaks.wed.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_wed_end"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.wed.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.wed.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.wed.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.wed.end"
                                                    v-model="clinicLunchbreaks.wed.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_wed_end.save(clinicLunchbreaks.wed.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        木曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_thu_start"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.thu.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.thu.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.thu.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.thu.start"
                                                    v-model="clinicLunchbreaks.thu.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_thu_start.save(clinicLunchbreaks.thu.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_thu_end"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.thu.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.thu.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.thu.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.thu.end"
                                                    v-model="clinicLunchbreaks.thu.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_thu_end.save(clinicLunchbreaks.thu.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        金曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_fri_start"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.fri.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.fri.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.fri.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.fri.start"
                                                    v-model="clinicLunchbreaks.fri.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_fri_start.save(clinicLunchbreaks.fri.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_fri_end"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.fri.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.fri.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.fri.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.fri.end"
                                                    v-model="clinicLunchbreaks.fri.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_fri_end.save(clinicLunchbreaks.fri.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                                <v-layout align-center justify-center>
                                    <v-flex xs1>
                                        土曜日
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_sat_start"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.sat.start"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.sat.start"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.sat.start"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.sat.start"
                                                    v-model="clinicLunchbreaks.sat.start"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_sat_start.save(clinicLunchbreaks.sat.start)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs1 class="is-item-centered">
                                        <v-icon>arrow_forward_ios</v-icon>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu
                                                ref="lunch_time_menu_sat_end"
                                                :close-on-content-click="false"
                                                v-model="lunchTimeMenu.sat.end"
                                                :nudge-right="40"
                                                :return-value.sync="clinicLunchbreaks.sat.end"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                max-width="290px"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    v-model="clinicLunchbreaks.sat.end"
                                                    readonly
                                            ></v-text-field>
                                            <v-time-picker
                                                    v-if="lunchTimeMenu.sat.end"
                                                    v-model="clinicLunchbreaks.sat.end"
                                                    full-width
                                                    color="secondary"
                                                    v-on:change="$refs.lunch_time_menu_sat_end.save(clinicLunchbreaks.sat.end)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                            <v-card-actions class="justify-center">
                                <v-btn type="submit" color="accent">更新</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "ClinicHours",
        data: () => ({
            activeTab: null,
            flashMessageClinicTimes: false,
            validationErrorsClinicTimes: [],
            clinicTimes: {
                base: {
                    start: null,
                    end: null
                },
                sun: {
                    start: null,
                    end: null
                },
                mon: {
                    start: null,
                    end: null
                },
                tue: {
                    start: null,
                    end: null
                },
                wed: {
                    start: null,
                    end: null
                },
                thu: {
                    start: null,
                    end: null
                },
                fri: {
                    start: null,
                    end: null
                },
                sat: {
                    start: null,
                    end: null
                }
            },
            defaultTimeMenu: {
                sun: {
                    start: false,
                    end: false
                },
                mon: {
                    start: false,
                    end: false
                },
                tue: {
                    start: false,
                    end: false
                },
                wed: {
                    start: false,
                    end: false
                },
                thu: {
                    start: false,
                    end: false
                },
                fri: {
                    start: false,
                    end: false
                },
                sat: {
                    start: false,
                    end: false
                }
            },
            flashMessageClinicWebTimes: false,
            validationErrorsClinicWebTimes: [],
            clinicWebTimes: {
                base: {
                    start: null,
                    end: null
                },
                sun: {
                    start: null,
                    end: null
                },
                mon: {
                    start: null,
                    end: null
                },
                tue: {
                    start: null,
                    end: null
                },
                wed: {
                    start: null,
                    end: null
                },
                thu: {
                    start: null,
                    end: null
                },
                fri: {
                    start: null,
                    end: null
                },
                sat: {
                    start: null,
                    end: null
                }
            },
            webTimeMenu: {
                sun: {
                    start: false,
                    end: false
                },
                mon: {
                    start: false,
                    end: false
                },
                tue: {
                    start: false,
                    end: false
                },
                wed: {
                    start: false,
                    end: false
                },
                thu: {
                    start: false,
                    end: false
                },
                fri: {
                    start: false,
                    end: false
                },
                sat: {
                    start: false,
                    end: false
                }
            },
            flashMessageClinicLunchbreaks: false,
            validationErrorsClinicLunchbreaks: [],
            clinicLunchbreaks: {
                base: {
                    start: null,
                    end: null
                },
                sun: {
                    start: null,
                    end: null
                },
                mon: {
                    start: null,
                    end: null
                },
                tue: {
                    start: null,
                    end: null
                },
                wed: {
                    start: null,
                    end: null
                },
                thu: {
                    start: null,
                    end: null
                },
                fri: {
                    start: null,
                    end: null
                },
                sat: {
                    start: null,
                    end: null
                },
                display_flag: true
            },
            lunchTimeMenu: {
                sun: {
                    start: false,
                    end: false
                },
                mon: {
                    start: false,
                    end: false
                },
                tue: {
                    start: false,
                    end: false
                },
                wed: {
                    start: false,
                    end: false
                },
                thu: {
                    start: false,
                    end: false
                },
                fri: {
                    start: false,
                    end: false
                },
                sat: {
                    start: false,
                    end: false
                }
            },
            displayFlagLabel: '表示する'
        }),
        watch: {
            'clinicLunchbreaks.display_flag'(val) {
                this.displayFlagLabel = (val) ? '表示する' : '表示しない'
            }
        },
        created() {
            this.initializeClinicTimes()
            this.initializeClinicWebTimes()
            this.initializeClinicLunchbreaks()
        },
        methods: {
            async initializeClinicTimes() {
                let res = null
                await axios
                    .get('/api/clinic-times')
                    .then(response => (
                        res = response.data
                    ))
                if (res !== '') {
                    this.clinicTimes = res
                }
            },
            updateClinicTimes() {
                this.flashMessageClinicTimes = false
                this.validationErrorsClinicTimes = []
                axios
                    .post('/api/clinic-times', this.clinicTimes)
                    .then(response => (
                        this.flashMessageClinicTimes = response.status === 200
                    ))
                    .catch(error => (
                        this.validationErrorsClinicTimes = error.response.data.errors
                    ))
            },
            copyBaseClinicTimes() {
                this.clinicTimes.sun.start = this.clinicTimes.base.start
                this.clinicTimes.mon.start = this.clinicTimes.base.start
                this.clinicTimes.tue.start = this.clinicTimes.base.start
                this.clinicTimes.wed.start = this.clinicTimes.base.start
                this.clinicTimes.thu.start = this.clinicTimes.base.start
                this.clinicTimes.fri.start = this.clinicTimes.base.start
                this.clinicTimes.sat.start = this.clinicTimes.base.start
                this.clinicTimes.sun.end = this.clinicTimes.base.end
                this.clinicTimes.mon.end = this.clinicTimes.base.end
                this.clinicTimes.tue.end = this.clinicTimes.base.end
                this.clinicTimes.wed.end = this.clinicTimes.base.end
                this.clinicTimes.thu.end = this.clinicTimes.base.end
                this.clinicTimes.fri.end = this.clinicTimes.base.end
                this.clinicTimes.sat.end = this.clinicTimes.base.end
            },
            async initializeClinicWebTimes() {
                let res = null
                await axios
                    .get('/api/clinic-web-times')
                    .then(response => (
                        res = response.data
                    ))
                if (res !== '') {
                    this.clinicWebTimes = res
                }
            },
            updateClinicWebTimes() {
                this.flashMessageClinicWebTimes = false
                this.validationErrorsClinicWebTimes = []
                axios
                    .post('/api/clinic-web-times', this.clinicWebTimes)
                    .then(response => (
                        this.flashMessageClinicWebTimes = response.status === 200
                    ))
                    .catch(error => (
                        this.validationErrorsClinicWebTimes = error.response.data.errors
                    ))
            },
            copyBaseClinicWebTimes() {
                this.clinicWebTimes.sun.start = this.clinicWebTimes.base.start
                this.clinicWebTimes.mon.start = this.clinicWebTimes.base.start
                this.clinicWebTimes.tue.start = this.clinicWebTimes.base.start
                this.clinicWebTimes.wed.start = this.clinicWebTimes.base.start
                this.clinicWebTimes.thu.start = this.clinicWebTimes.base.start
                this.clinicWebTimes.fri.start = this.clinicWebTimes.base.start
                this.clinicWebTimes.sat.start = this.clinicWebTimes.base.start
                this.clinicWebTimes.sun.end = this.clinicWebTimes.base.end
                this.clinicWebTimes.mon.end = this.clinicWebTimes.base.end
                this.clinicWebTimes.tue.end = this.clinicWebTimes.base.end
                this.clinicWebTimes.wed.end = this.clinicWebTimes.base.end
                this.clinicWebTimes.thu.end = this.clinicWebTimes.base.end
                this.clinicWebTimes.fri.end = this.clinicWebTimes.base.end
                this.clinicWebTimes.sat.end = this.clinicWebTimes.base.end
            },
            async initializeClinicLunchbreaks() {
                let res = null
                await axios
                    .get('/api/clinic-lunchbreaks')
                    .then(response => (
                        res = response.data
                    ))
                if (res !== '') {
                    this.clinicLunchbreaks = res
                }
            },
            updateClinicLunchbreaks() {
                this.flashMessageClinicLunchbreaks = false
                this.validationErrorsClinicLunchbreaks = []
                axios
                    .post('/api/clinic-lunchbreaks', this.clinicLunchbreaks)
                    .then(response => (
                        this.flashMessageClinicLunchbreaks = response.status === 200
                    ))
                    .catch(error => (
                        this.validationErrorsClinicLunchbreaks = error.response.data.errors
                    ))
            },
            copyBaseClinicLunchbreaks() {
                this.clinicLunchbreaks.sun.start = this.clinicLunchbreaks.base.start
                this.clinicLunchbreaks.mon.start = this.clinicLunchbreaks.base.start
                this.clinicLunchbreaks.tue.start = this.clinicLunchbreaks.base.start
                this.clinicLunchbreaks.wed.start = this.clinicLunchbreaks.base.start
                this.clinicLunchbreaks.thu.start = this.clinicLunchbreaks.base.start
                this.clinicLunchbreaks.fri.start = this.clinicLunchbreaks.base.start
                this.clinicLunchbreaks.sat.start = this.clinicLunchbreaks.base.start
                this.clinicLunchbreaks.sun.end = this.clinicLunchbreaks.base.end
                this.clinicLunchbreaks.mon.end = this.clinicLunchbreaks.base.end
                this.clinicLunchbreaks.tue.end = this.clinicLunchbreaks.base.end
                this.clinicLunchbreaks.wed.end = this.clinicLunchbreaks.base.end
                this.clinicLunchbreaks.thu.end = this.clinicLunchbreaks.base.end
                this.clinicLunchbreaks.fri.end = this.clinicLunchbreaks.base.end
                this.clinicLunchbreaks.sat.end = this.clinicLunchbreaks.base.end
            },
            copyFromClinicTimesToClinicWebTimes() {
                this.clinicWebTimes = this.clinicTimes
            }
        }
    }
</script>

<style scoped>
    .is-item-centered {
        display: flex;
        justify-content: center;
    }
</style>