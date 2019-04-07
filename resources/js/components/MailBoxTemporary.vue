<template>
    <v-layout align-center justify-center>
        <v-flex xs12 md8>
            <v-card class="py-3">
                <v-toolbar flat color="white">
                    <v-toolbar-title class="grey--text">予約確定待ち一覧</v-toolbar-title>
                    <v-divider
                            class="ml-4"
                            inset
                            vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-text-field
                            v-model="search"
                            append-icon="search"
                            label="検索"
                            single-line
                            hide-details
                    ></v-text-field>
                </v-toolbar>

                <v-card-text>
                    <v-data-table
                            v-model="selected"
                            :headers="headers"
                            :items="temporaryAppointments"
                            :search="search"
                            disable-initial-sort
                            rows-per-page-text="表示件数"
                    >
                        <template slot="items" slot-scope="props">
                            <tr @click="showItem(props.item)">
                                <td class="text-xs-center">{{ props.item.time_label }}</td>
                                <td class="text-xs-center">{{ props.item.treatment }}</td>
                                <td>{{ props.item.created_at }}</td>
                                <td class="text-xs-center">{{ props.item.past_day }}日</td>
                            </tr>
                        </template>
                        <template slot="no-data">
                            <div class="py-4">
                                <v-progress-linear
                                        :indeterminate="true"
                                        color="secondary"
                                >
                                </v-progress-linear>
                            </div>
                        </template>
                        <template slot="no-results">
                            <div class="py-4">
                                <v-alert :value="true" color="error" icon="warning">
                                    「{{ search }}」の検索で結果が見つかりませんでした。
                                </v-alert>
                            </div>
                        </template>
                        <template slot="pageText" slot-scope="props">
                            {{ props.pageStart }} - {{ props.pageStop }} / {{ props.itemsLength }}件
                        </template>
                    </v-data-table>
                </v-card-text>

                <v-dialog v-model="dialog" max-width="500">
                    <v-card>
                        <v-card-title>
                            <span class="headline primary--text">仮予約内容</span>
                        </v-card-title>

                        <v-card-text>
                            <v-layout>
                                <v-flex xs7>
                                    <v-text-field
                                            v-model="editedItem.time_label"
                                            prepend-icon="access_time"
                                            label="予約日時"
                                            readonly
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 offset-xs1>
                                    <v-text-field
                                            v-model="editedItem.unit_name"
                                            prepend-icon="airline_seat_recline_extra"
                                            label="ユニット"
                                            readonly
                                    >
                                    </v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-text-field
                                    v-model="editedItem.treatment"
                                    prepend-icon="event_note"
                                    label="診療内容"
                                    readonly
                            >
                            </v-text-field>

                            <v-layout>
                                <v-flex xs7>
                                    <v-text-field
                                            v-model="editedItem.created_at"
                                            prepend-icon="date_range"
                                            label="登録日"
                                            readonly
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 offset-xs1>
                                    <v-text-field
                                            v-model="editedItem.past_day"
                                            prepend-icon="warning"
                                            label="経過日数"
                                            readonly
                                            suffix="日"
                                    >
                                    </v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-divider class="my-3"></v-divider>

                            <v-text-field
                                    v-model="editedItem.form_content.name"
                                    prepend-icon="face"
                                    label="名前"
                                    readonly
                            >
                            </v-text-field>

                            <v-text-field
                                    v-model="editedItem.form_content.tel"
                                    prepend-icon="call"
                                    label="電話番号"
                                    readonly
                            >
                            </v-text-field>

                            <v-text-field
                                    v-model="editedItem.form_content.email"
                                    prepend-icon="mail_outline"
                                    label="E-mail"
                                    readonly
                            >
                            </v-text-field>

                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="accent" flat @click="close">閉じる</v-btn>
                            <!--<v-btn color="accent" flat @click="destroy">削除する</v-btn>-->
                        </v-card-actions>
                    </v-card>
                </v-dialog>

            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "MailBoxTemporary",
        data: () => ({
            dialog: false,
            search: '',
            selected: [],
            headers: [
                {
                    text: '予約日時',
                    align: 'center',
                    sortable: true,
                    value: 'start'
                },
                {
                    text: '診療内容',
                    align: 'center',
                    sortable: true,
                    value: 'treatment'
                },
                {
                    text: '登録日',
                    align: 'left',
                    sortable: true,
                    value: 'created_at'
                },
                {
                    text: '経過日数',
                    align: 'center',
                    sortable: true,
                    value: 'past_day'
                }
            ],
            temporaryAppointments: [],
            editedItem: {
                id: '',
                start: '',
                end: '',
                time_label: '',
                past_day: '',
                unit: '',
                unit_name: '',
                treatment_id: '',
                treatment: '',
                way: '',
                note: '',
                form_content: {},
                created_at: ''
            },
            defaultItem: {
                id: '',
                start: '',
                end: '',
                time_label: '',
                past_day: '',
                unit: '',
                unit_name: '',
                treatment_id: '',
                treatment: '',
                way: '',
                note: '',
                form_content: {},
                created_at: ''
            },
        }),
        watch: {
            dialog(val) {
                val || this.close()
            }
        },
        created() {
            this.initialize()
            this.editedItem = Object.assign({}, this.defaultItem)
        },
        methods: {
            async initialize() {
                await axios
                    .get('/api/temporary-appointments')
                    .then(response => (
                        this.temporaryAppointments = response.data
                    ))
            },
            async showItem(item) {
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            close() {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                }, 300)
            },
            async destroy() {
                // await axios
                //     .delete('/api/mail-inboxes/' + this.editedItem.id)
                //     .then(response => (
                //         console.log(response.status)
                //     ))
                //     .catch(error => (
                //         console.log(error.response)
                //     ))
                //
                // this.close()
                // this.initialize()
            }
        }
    }
</script>

<style scoped>

</style>