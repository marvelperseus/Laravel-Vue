<template>
    <v-layout justify-center>
        <v-flex xs12 md10>

            <v-tabs
                    v-model="activeTab"
                    color="transparent"
                    slider-color="secondary"
                    grow
                    class="mb-3"
            >
                <v-tab key="base">基本設定</v-tab>
                <v-tab key="treatment">診療内容設定</v-tab>
            </v-tabs>

            <v-tabs-items
                    v-model="activeTab"
            >
                <v-tab-item key="base">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">基本設定</v-toolbar-title>
                        </v-toolbar>
                        <v-form
                                v-on:submit.prevent="save"
                                class="pa-4"
                        >
                            <v-card-text>
                                <v-alert
                                        :value="flashMessage"
                                        type="success"
                                        transition="slide-y-reverse-transition"
                                        class="mb-4"
                                >
                                    更新しました
                                </v-alert>

                                <v-layout wrap>
                                    <v-flex xs12 md5>
                                        <v-subheader class="pl-0">
                                            <v-icon class="mr-1">web</v-icon>
                                            ウェブ予約
                                        </v-subheader>
                                        <v-radio-group
                                                v-model="webAppointmentSetting.status"
                                                row
                                        >
                                            <v-radio label="ON" value="ON"></v-radio>
                                            <v-radio label="確認のみ" value="CONFIRM"></v-radio>
                                            <v-radio label="OFF" value="OFF"></v-radio>
                                        </v-radio-group>

                                        <v-layout align-center>
                                            <v-flex class="mr-2" style="flex-grow: 0;">
                                                <v-subheader class="pl-0">
                                                    <v-icon class="mr-1">autorenew</v-icon>
                                                    自動登録
                                                </v-subheader>
                                            </v-flex>
                                            <v-flex>
                                                <v-switch
                                                        :label="webAppointmentSetting.auto_regist"
                                                        v-model="webAppointmentSetting.auto_regist"
                                                        true-value="ON"
                                                        false-value="OFF"
                                                        color="primary"
                                                        class="pt-0"
                                                        hide-details
                                                >
                                                </v-switch>
                                            </v-flex>
                                        </v-layout>

                                        <v-layout align-center>
                                            <v-flex class="mr-2" style="flex-grow: 0;">
                                                <v-subheader class="pl-0">
                                                    <v-icon class="mr-1">redo</v-icon>
                                                    キャンセル待ち自動登録
                                                </v-subheader>
                                            </v-flex>
                                            <v-flex>
                                                <v-switch
                                                        :label="webAppointmentSetting.cancel_auto_regist"
                                                        v-model="webAppointmentSetting.cancel_auto_regist"
                                                        true-value="ON"
                                                        false-value="OFF"
                                                        color="primary"
                                                        class="pt-0"
                                                        hide-details
                                                >
                                                </v-switch>
                                            </v-flex>
                                        </v-layout>

                                        <v-subheader class="pl-0">
                                            <v-icon class="mr-1">input</v-icon>
                                            ウェブからのキャンセル
                                        </v-subheader>
                                        <v-radio-group
                                                v-model="webAppointmentSetting.web_cancel_flag"
                                                row
                                        >
                                            <v-radio label="できる" :value="true"></v-radio>
                                            <v-radio label="できない" :value="false"></v-radio>
                                        </v-radio-group>

                                        <v-layout align-center>
                                            <v-flex xs6>
                                                <v-select
                                                        v-model="webAppointmentSetting.permit_cancel_day"
                                                        :items="weekItems"
                                                        prepend-icon="pan_tool"
                                                        label="キャンセルの受付"
                                                ></v-select>
                                            </v-flex>
                                            <v-flex xs6>
                                                <div class="pl-2">日前までキャンセル可能</div>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs12 md6 offset-md1>
                                        <v-layout>
                                            <v-flex xs6>
                                                <v-select
                                                        v-model="webAppointmentSetting.time_delimiter"
                                                        :items="timeDelimiterItems"
                                                        prepend-icon="format_line_spacing"
                                                        label="時間区切り"
                                                        suffix="分"
                                                ></v-select>
                                            </v-flex>
                                        </v-layout>

                                        <v-subheader class="pl-0">
                                            <v-icon class="mr-1">view_column</v-icon>
                                            使用ユニット
                                        </v-subheader>
                                        <v-layout row>
                                            <v-checkbox
                                                    v-for="(item, index) in webAppointmentSetting.unit_items"
                                                    :key="index"
                                                    v-model="units"
                                                    :label="item"
                                                    :value="index"
                                            >
                                            </v-checkbox>
                                        </v-layout>

                                        <v-subheader class="pl-0">
                                            <v-icon class="mr-1">face</v-icon>
                                            受付ける患者
                                        </v-subheader>
                                        <v-layout row>
                                            <v-checkbox
                                                    v-model="webAppointmentSetting.target"
                                                    label="初診"
                                                    value="first"
                                            >
                                            </v-checkbox>
                                            <v-checkbox
                                                    v-model="webAppointmentSetting.target"
                                                    label="治療中"
                                                    value="treatment"
                                            >
                                            </v-checkbox>
                                        </v-layout>

                                        <v-layout align-center>
                                            <v-flex xs6>
                                                <v-select
                                                        v-model="webAppointmentSetting.start_day"
                                                        :items="weekItems"
                                                        prepend-icon="arrow_forward"
                                                        label="予約受付開始日"
                                                ></v-select>
                                            </v-flex>
                                            <v-flex xs6>
                                                <div class="pl-2">日後から</div>
                                            </v-flex>
                                        </v-layout>

                                        <v-layout align-center>
                                            <v-flex xs6>
                                                <v-select
                                                        v-model="webAppointmentSetting.term"
                                                        :items="termItems"
                                                        prepend-icon="arrow_downward"
                                                        label="予約受付期間"
                                                ></v-select>
                                            </v-flex>
                                            <v-flex xs6>
                                                <div class="pl-2">ヶ月先まで受付可能</div>
                                            </v-flex>
                                        </v-layout>

                                        <!--<v-layout>-->
                                            <!--<v-flex xs8>-->
                                                <!--<v-select-->
                                                        <!--v-model="webAppointmentSetting.day_limit_count"-->
                                                        <!--:items="limitCountItems"-->
                                                        <!--prepend-icon="not_interested"-->
                                                        <!--label="1日の予約数制限（1患者）"-->
                                                        <!--suffix="回"-->
                                                <!--&gt;</v-select>-->
                                            <!--</v-flex>-->
                                        <!--</v-layout>-->

                                        <!--<v-layout>-->
                                            <!--<v-flex xs8>-->
                                                <!--<v-select-->
                                                        <!--v-model="webAppointmentSetting.max_limit_count"-->
                                                        <!--:items="limitCountItems"-->
                                                        <!--prepend-icon="new_releases"-->
                                                        <!--label="最大予約数制限（1患者）"-->
                                                        <!--suffix="件"-->
                                                <!--&gt;</v-select>-->
                                            <!--</v-flex>-->
                                        <!--</v-layout>-->
                                    </v-flex>
                                </v-layout>
                            </v-card-text>

                            <v-card-actions class="justify-center">
                                <v-spacer></v-spacer>
                                <v-btn type="submit" color="accent">更新</v-btn>
                            </v-card-actions>

                        </v-form>
                    </v-card>
                </v-tab-item>
                <v-tab-item key="treatment">
                    <v-card class="py-3">
                        <v-toolbar flat color="white">
                            <v-toolbar-title class="grey--text">ウェブ予約診療内容</v-toolbar-title>
                            <v-divider
                                    class="ml-4"
                                    inset
                                    vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                            <v-select
                                    v-model="addTreatmentId"
                                    :items="treatmentOriginals"
                                    item-text="treatment"
                                    item-value="id"
                                    label="診療内容"
                                    :error-messages="treatmentValidationErrors.treatment_id"
                            ></v-select>
                            <v-btn @click="addTreatment" color="accent" dark class="mb-2">追加</v-btn>
                            <v-dialog v-model="dialog" max-width="500px">
                                <v-card>
                                    <v-card-title>
                                        <span class="headline primary--text">アイテムを削除する</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <p>「{{ editedItem.treatment }}」を削除してもよろしいですか？</p>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="accent" flat @click="close">キャンセル</v-btn>
                                        <v-btn color="accent" flat @click="destroy">削除する</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>

                        <v-card-text>
                            <v-data-table
                                    :headers="headers"
                                    :items="treatments"
                                    hide-actions
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.item.treatment }}</td>
                                    <td class="text-xs-center">{{ props.item.time }} 分</td>
                                    <td class="text-xs-center">
                                        <v-icon :color="props.item.color" small>style</v-icon>
                                    </td>
                                    <td class="justify-center align-center layout pa-0">
                                        <v-btn
                                                flat
                                                icon
                                                @click="deleteItem(props.item)"
                                                color="grey"
                                                class="mx-1"
                                        >
                                            <v-icon>delete</v-icon>
                                        </v-btn>
                                    </td>
                                </template>
                                <template slot="no-data">
                                    <div class="py-4">
                                        <p class="text-xs-center">登録データはありません</p>
                                    </div>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "WebAppointmentSettingBase",
        data: () => ({
            activeTab: null,
            units: [],
            weekItems: [0, 1, 2, 3, 4, 5, 6, 7],
            termItems: [1, 2, 3, 4, 5, 6],
            timeDelimiterItems: [15, 30, 60],
            limitCountItems: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            webAppointmentSetting: [],
            flashMessage: false,
            validationErrors: [],
            dialog: false,
            headers: [
                {
                    text: '診療内容',
                    align: 'left',
                    sortable: true,
                    value: 'treatment'
                },
                {
                    text: '診療時間',
                    align: 'center',
                    sortable: true,
                    value: 'time'
                },
                {
                    text: 'カラー',
                    align: 'center',
                    sortable: false,
                    value: 'color'
                },
                {
                    text: '操作',
                    align: 'center',
                    sortable: false,
                    value: 'name'
                }
            ],
            treatmentOriginals: [],
            treatmentValidationErrors: [],
            addTreatmentId: null,
            treatments: [],
            editedIndex: -1,
            editedItem: {
                id: '',
                treatment: '',
                time: '',
                color: ''
            },
            defaultItem: {
                id: '',
                treatment: '',
                time: '',
                color: '#49A2BC'
            }
        }),
        watch: {
            dialog(val) {
                val || this.close()
            }
        },
        created() {
            this.initialize()
            this.initializeTreatment()
            this.editedItem = Object.assign({}, this.defaultItem)
        },
        methods: {
            async initialize() {
                await axios
                    .get('/api/web-appointment-settings')
                    .then(response => (
                        this.webAppointmentSetting = response.data
                    ))

                if (this.webAppointmentSetting.id == null) {
                    this.webAppointmentSetting = {
                        status: 'OFF',
                        auto_regist: 'OFF',
                        cancel_auto_regist: 'OFF',
                        web_cancel_flag: false,
                        target: ['first'],
                        unit_items: this.webAppointmentSetting.unit_items
                    }
                } else {
                    this.units = this.webAppointmentSetting.units
                }
            },
            save() {
                this.validationErrors = []
                this.webAppointmentSetting.units = this.units
                axios
                    .post('/api/web-appointment-settings', Object.assign({}, this.webAppointmentSetting))
                    .then(response => (
                        this.flashMessage = response.status === 200
                    ))
                    .catch(error => (
                        this.validationErrors = error.response.data.errors
                    ))
            },
            async initializeTreatment() {
                await axios
                    .get('/api/treatments')
                    .then(response => (
                        this.treatmentOriginals = response.data
                    ))
                await axios
                    .get('/api/web-appointment-treatments')
                    .then(response => (
                        this.treatments = response.data
                    ))
            },
            deleteItem(item) {
                this.editedIndex = this.treatments.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            close() {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },
            async addTreatment() {
                this.treatmentValidationErrors = []
                await axios
                    .post('/api/web-appointment-treatments', Object.assign({}, {treatment_id: this.addTreatmentId}))
                    .then(response => (
                        console.log(response.status)
                    ))
                    .catch(error => (
                        this.treatmentValidationErrors = error.response.data.errors
                    ))
                this.initializeTreatment()
            },
            async destroy() {
                if (this.editedIndex > -1) {
                    await axios
                        .get('/api/web-appointment-treatments/delete/' + this.editedItem.id)
                        .then(response => (
                            console.log(response.status)
                        ))
                        .catch(error => (
                            console.log(error.response)
                        ))
                }

                this.initializeTreatment()
                this.close()
            }
        }
    }
</script>

<style scoped>
    .v-input--selection-controls {
        margin-top: 0;
    }
</style>