<template>
    <v-layout align-center justify-center>
        <v-flex xs12 md8>
            <v-card class="py-3">
                <v-toolbar flat color="white">
                    <v-toolbar-title class="grey--text">リコール設定</v-toolbar-title>
                    <v-divider
                            class="ml-4"
                            inset
                            vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" :max-width="dialogWidth">
                        <v-btn slot="activator" color="accent" dark class="mb-2">追加</v-btn>
                        <v-card>
                            <template v-if="isDelete">

                                <v-card-title>
                                    <span class="headline primary--text">アイテムを削除する</span>
                                </v-card-title>

                                <v-card-text>
                                    <p>「{{ editedItem.name }}」を削除してもよろしいですか？</p>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="accent" flat @click="close">キャンセル</v-btn>
                                    <v-btn color="accent" flat @click="destroy">削除する</v-btn>
                                </v-card-actions>

                            </template>
                            <template v-else>

                                <v-card-title>
                                    <span class="headline primary--text">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-layout wrap>
                                        <v-flex xs12 md5 class="mb-3">
                                            <v-text-field
                                                    v-model="editedItem.name"
                                                    prepend-icon="assignment"
                                                    label="名前"
                                                    :error-messages="validationErrors.name"
                                            >
                                            </v-text-field>
                                            <v-checkbox
                                                    v-model="editedItem.draft_flag"
                                                    label="下書き"
                                                    :true-value="true"
                                                    :false-value="false"
                                            >
                                            </v-checkbox>

                                            <v-layout>
                                                <v-flex xs6>
                                                    <v-select
                                                            v-model="editedItem.patient_status"
                                                            :items="statusItems"
                                                            item-text="label"
                                                            item-value="value"
                                                            prepend-icon="how_to_reg"
                                                            label="患者ステータス"
                                                            :error-messages="validationErrors.patient_status"
                                                    ></v-select>
                                                </v-flex>
                                            </v-layout>

                                            <v-subheader class="pl-0">
                                                <v-icon class="mr-1">send</v-icon>
                                                配信タイミング
                                            </v-subheader>
                                            <v-layout align-start>
                                                <v-flex xs2>
                                                    <v-select
                                                            v-model="editedItem.timing_number"
                                                            :items="timingNumberItems"
                                                            class="pt-0"
                                                            :error-messages="validationErrors.timing_number"
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex xs2 class="ml-5">
                                                    <v-select
                                                            v-model="editedItem.timing_unit"
                                                            :items="timingUnitItems"
                                                            item-text="label"
                                                            item-value="value"
                                                            class="pt-0"
                                                            :error-messages="validationErrors.timing_unit"
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex xs2>
                                                    <div class="pl-2 pt-3">後の</div>
                                                </v-flex>
                                                <v-flex xs2>
                                                    <v-select
                                                            v-model="editedItem.time"
                                                            :items="timeItems"
                                                            item-text="number"
                                                            item-value="time"
                                                            class="pt-0"
                                                            :error-messages="validationErrors.time"
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex xs2>
                                                    <div class="pl-2 pt-3">時に送信</div>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                        <v-flex xs12 md6 offset-md1>
                                            <v-alert
                                                    :value="true"
                                                    type="info"
                                                    color="primary"
                                                    outline
                                                    class="mb-3"
                                            >
                                                <h4>置換可能な文字</h4>
                                                <ul>
                                                    <li>%患者名% → 患者名</li>
                                                    <li>%コンテンツ% → 予約情報等</li>
                                                </ul>
                                            </v-alert>

                                            <v-layout>
                                                <v-flex xs12 md6>
                                                    <v-select
                                                            v-model="loadMailTemplateId"
                                                            :items="mailTemplates"
                                                            item-text="name"
                                                            item-value="id"
                                                            prepend-icon="assignment_turned_in"
                                                            label="メールテンプレート"
                                                    ></v-select>
                                                </v-flex>
                                            </v-layout>

                                            <v-text-field
                                                    v-model="editedItem.title"
                                                    prepend-icon="title"
                                                    label="件名"
                                                    :error-messages="validationErrors.title"
                                            >
                                            </v-text-field>
                                            <v-textarea
                                                    v-model="editedItem.body"
                                                    prepend-icon="notes"
                                                    label="本文"
                                                    rows="6"
                                                    :error-messages="validationErrors.body"
                                            ></v-textarea>
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="accent" flat @click="close">キャンセル</v-btn>
                                    <v-btn color="accent" flat @click="save">保存する</v-btn>
                                </v-card-actions>

                            </template>
                        </v-card>
                    </v-dialog>
                </v-toolbar>

                <v-card-text>
                    <v-data-table
                            :headers="headers"
                            :items="recallMasters"
                            hide-actions
                    >
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.name }}</td>
                            <td class="text-xs-center">{{ props.item.draft_flag_label }}</td>
                            <td class="text-xs-center">{{ props.item.patient_status_label }}</td>
                            <td class="text-xs-center">{{ props.item.timing_label }}</td>
                            <td class="justify-center align-center layout pa-0">
                                <v-btn
                                        flat
                                        icon
                                        @click="editItem(props.item)"
                                        color="grey"
                                        class="mx-1"
                                >
                                    <v-icon>edit</v-icon>
                                </v-btn>
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
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "MailRecall",
        data: () => ({
            timingNumberItems: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
            timingUnitItems: [
                {label: 'ヶ月', value: 'month'},
                {label: '年', value: 'year'},
            ],
            timeItems: [
                {number: 0, time: '00:00:00'},
                {number: 1, time: '01:00:00'},
                {number: 2, time: '02:00:00'},
                {number: 3, time: '03:00:00'},
                {number: 4, time: '04:00:00'},
                {number: 5, time: '05:00:00'},
                {number: 6, time: '06:00:00'},
                {number: 7, time: '07:00:00'},
                {number: 8, time: '08:00:00'},
                {number: 9, time: '09:00:00'},
                {number: 10, time: '10:00:00'},
                {number: 11, time: '11:00:00'},
                {number: 12, time: '12:00:00'},
                {number: 13, time: '13:00:00'},
                {number: 14, time: '14:00:00'},
                {number: 15, time: '15:00:00'},
                {number: 16, time: '16:00:00'},
                {number: 17, time: '17:00:00'},
                {number: 18, time: '18:00:00'},
                {number: 19, time: '19:00:00'},
                {number: 20, time: '20:00:00'},
                {number: 21, time: '21:00:00'},
                {number: 22, time: '22:00:00'},
                {number: 23, time: '23:00:00'}
            ],
            loadMailTemplateId: null,
            statusItems: [
                {value: 'first', label: '初診'},
                {value: 'before', label: '治療前'},
                {value: 'treatment', label: '治療中'},
                {value: 'suspend', label: '中断'},
                {value: 'complete', label: '治療済み'}
            ],
            dialog: false,
            isDelete: false,
            validationErrors: [],
            headers: [
                {
                    text: '名称',
                    align: 'left',
                    sortable: true,
                    value: 'name'
                },
                {
                    text: '下書き',
                    align: 'center',
                    sortable: true,
                    value: 'draft_flag'
                },
                {
                    text: '患者ステータス',
                    align: 'center',
                    sortable: true,
                    value: 'patient_status'
                },
                {
                    text: 'タイミング',
                    align: 'center',
                    sortable: false,
                    value: 'timing'
                },
                {
                    text: '操作',
                    align: 'center',
                    sortable: false,
                    value: 'name'
                }
            ],
            mailTemplates: [],
            recallMasters: [],
            editedIndex: -1,
            editedItem: {
                id: '',
                name: '',
                draft_flag: '',
                patient_status: '',
                timing_number: '',
                timing_unit: '',
                time: '',
                title: '',
                body: ''
            },
            defaultItem: {
                id: '',
                name: '',
                draft_flag: false,
                patient_status: '',
                timing_number: '',
                timing_unit: '',
                time: '',
                title: '',
                body: ''
            }
        }),
        computed: {
            formTitle() {
                return this.editedIndex === -1 ? '新しく追加する' : '内容を編集する'
            },
            dialogWidth() {
                return this.isDelete ? '500px' : '960px'
            }
        },
        watch: {
            dialog(val) {
                val || this.close()
            },
            loadMailTemplateId(val) {
                const loaded = this.mailTemplates.find(function (item) {
                    return (item.id === val) ? item : null
                })
                this.editedItem.title = loaded.title
                this.editedItem.body = loaded.body
            }
        },
        created() {
            this.initialize()
            this.initializeMailTemplate()
            this.editedItem = Object.assign({}, this.defaultItem)
        },
        methods: {
            initialize() {
                axios
                    .get('/api/recall-masters')
                    .then(response => (
                        this.recallMasters = response.data
                    ))
            },
            initializeMailTemplate() {
                axios
                    .get('/api/mail-templates')
                    .then(response => (
                        this.mailTemplates = response.data
                    ))
            },
            editItem(item) {
                this.editedIndex = this.recallMasters.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            deleteItem(item) {
                this.isDelete = true
                this.editedIndex = this.recallMasters.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            close() {
                this.validationErrors = []
                this.dialog = false
                setTimeout(() => {
                    this.isDelete = false
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },
            async save() {
                this.validationErrors = []
                if (this.editedIndex > -1) {
                    await axios
                        .post('/api/recall-masters/' + this.editedItem.id, this.editedItem)
                        .then(response => (
                            console.log(response.status)
                        ))
                        .catch(error => (
                            this.validationErrors = error.response.data.errors
                        ))
                } else {
                    await axios
                        .post('/api/recall-masters', this.editedItem)
                        .then(response => (
                            console.log(response.status)
                        ))
                        .catch(error => (
                            this.validationErrors = error.response.data.errors
                        ))
                }

                if (this.validationErrors.length === 0) {
                    this.close()
                    this.initialize()
                }
            },
            async destroy() {
                if (this.editedIndex > -1) {
                    await axios
                        .get('/api/recall-masters/delete/' + this.editedItem.id)
                        .then(response => (
                            console.log(response.status)
                        ))
                        .catch(error => (
                            console.log(error.response)
                        ))
                }

                this.close()
                this.initialize()
            }
        }

    }
</script>

<style scoped>

</style>