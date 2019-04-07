<template>
    <v-layout align-start justify-center>
        <v-flex xs12 md10>
            <v-tabs
                    v-model="activeTab"
                    color="transparent"
                    slider-color="secondary"
                    grow
                    class="mb-3"
            >
                <v-tab key="bypatients">患者個別設定</v-tab>
                <v-tab key="target">患者検索</v-tab>
            </v-tabs>

            <v-tabs-items
                    v-model="activeTab"
            >
                <v-tab-item key="bypatients">
                    <v-card class="py-3">
                        <v-toolbar flat color="white">
                            <v-toolbar-title class="grey--text">設定済み患者</v-toolbar-title>
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
                                    :headers="headers"
                                    :items="patients"
                                    :search="search"
                                    rows-per-page-text="表示件数"
                            >
                                <template slot="items" slot-scope="props">
                                    <td class="text-xs-center">{{ props.item.karte_number }}</td>
                                    <td>{{ props.item.name }}</td>
                                    <td>{{ props.item.treatment }}</td>
                                    <td class="text-xs-center">{{ props.item.time }} 分</td>
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
                                <template v-if="isDelete">
                                    <v-card-title>
                                        <span class="headline primary--text">設定を削除する</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <p>「{{ editedItem.name }}」さんの設定を削除してもよろしいですか？</p>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="accent" flat @click="close">キャンセル</v-btn>
                                        <v-btn color="accent" flat @click="destroy">削除する</v-btn>
                                    </v-card-actions>
                                </template>
                                <template v-else>




                                    <v-card-title>
                                        <span class="headline primary--text">個別設定を編集する</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <p>「{{ editedItem.name }}」さんを編集</p>

                                        <v-select
                                                v-model="editedItem.treatment_id"
                                                prepend-icon="event_note"
                                                :items="treatments"
                                                item-text="treatment"
                                                item-value="id"
                                                label="診療内容"
                                                :error-messages="validationErrors.treatment_id"
                                        ></v-select>

                                        <v-layout wrap>
                                            <v-flex xs6>
                                                <v-text-field
                                                        v-model="editedItem.time"
                                                        prepend-icon="timer"
                                                        label="診療時間"
                                                        suffix="分"
                                                        :error-messages="validationErrors.time"
                                                >
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>

                                        <v-subheader class="pl-0">
                                            <v-icon class="mr-1">view_column</v-icon>
                                            使用ユニット
                                        </v-subheader>
                                        <v-layout row>
                                            <v-checkbox
                                                    v-for="(item, index) in unitNames"
                                                    :key="index"
                                                    v-model="editedItem.units"
                                                    :label="item"
                                                    :value="index"
                                            >
                                            </v-checkbox>
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

                    </v-card>
                </v-tab-item>
                <v-tab-item key="target">
                    <v-card class="py-3">
                        <v-toolbar flat color="white">
                            <v-toolbar-title class="grey--text">患者一覧</v-toolbar-title>
                            <v-divider
                                    class="ml-4"
                                    inset
                                    vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                            <v-text-field
                                    v-model="searchOrigin"
                                    append-icon="search"
                                    label="検索"
                                    single-line
                                    hide-details
                            ></v-text-field>
                        </v-toolbar>

                        <v-card-text>
                            <v-data-table
                                    :headers="headersOrigin"
                                    :items="patientsOrigin"
                                    :search="searchOrigin"
                                    rows-per-page-text="表示件数"
                            >
                                <template slot="items" slot-scope="props">
                                    <td class="text-xs-center">{{ props.item.karte_number }}</td>
                                    <td>{{ props.item.name }}</td>
                                    <td class="text-xs-center">{{ props.item.status_label }}</td>
                                    <td class="justify-center align-center layout pa-0">
                                        <v-btn
                                                flat
                                                icon
                                                @click="addItem(props.item)"
                                                color="grey"
                                                class="mx-1"
                                        >
                                            <v-icon>library_add</v-icon>
                                        </v-btn>
                                    </td>
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
                                            「{{ searchOrigin }}」の検索で結果が見つかりませんでした。
                                        </v-alert>
                                    </div>
                                </template>
                                <template slot="pageText" slot-scope="props">
                                    {{ props.pageStart }} - {{ props.pageStop }} / {{ props.itemsLength }}件
                                </template>
                            </v-data-table>
                        </v-card-text>

                        <v-dialog v-model="dialogOrigin" max-width="500">
                            <v-card>
                                <v-card-title>
                                    <span class="headline primary--text">個別設定に追加する</span>
                                </v-card-title>

                                <v-card-text>
                                    <p>「{{ editedItemOrigin.name }}」さんを追加</p>

                                    <v-select
                                            v-model="editedItemOrigin.treatment_id"
                                            prepend-icon="event_note"
                                            :items="treatments"
                                            item-text="treatment"
                                            item-value="id"
                                            label="診療内容"
                                            :error-messages="validationErrorsOrigin.treatment_id"
                                    ></v-select>

                                    <v-layout wrap>
                                        <v-flex xs6>
                                            <v-text-field
                                                    v-model="editedItemOrigin.time"
                                                    prepend-icon="timer"
                                                    label="診療時間"
                                                    suffix="分"
                                                    :error-messages="validationErrorsOrigin.time"
                                            >
                                            </v-text-field>
                                        </v-flex>
                                    </v-layout>

                                    <v-subheader class="pl-0">
                                        <v-icon class="mr-1">view_column</v-icon>
                                        使用ユニット
                                    </v-subheader>
                                    <v-layout row>
                                        <v-checkbox
                                                v-for="(item, index) in unitNames"
                                                :key="index"
                                                v-model="editedItemOrigin.units"
                                                :label="item"
                                                :value="index"
                                        >
                                        </v-checkbox>
                                    </v-layout>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="accent" flat @click="closeOrigin">キャンセル</v-btn>
                                    <v-btn color="accent" flat @click="add">追加する</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>

                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "WebAppointmentSettingByPatient",
        data: () => ({
            activeTab: null,
            dialog: false,
            isDelete: false,
            search: '',
            headers: [
                {
                    text: 'カルテ番号',
                    align: 'center',
                    sortable: true,
                    value: 'karte_number'
                },
                {
                    text: '名前',
                    align: 'left',
                    sortable: true,
                    value: 'name'
                },
                {
                    text: '診療内容',
                    align: 'left',
                    sortable: true,
                    value: 'treatment'
                },
                {
                    text: '診療時間',
                    align: 'center',
                    sortable: false,
                    value: 'time'
                },
                {
                    text: '操作',
                    align: 'center',
                    sortable: false,
                    value: 'name'
                }
            ],
            patients: [],
            validationErrors: [],
            editedIndex: -1,
            editedItem: {
                id: '',
                patient_id: '',
                treatment_id: '',
                time: '',
                units: [],
                name: ''
            },
            defaultItem: {
                id: '',
                patient_id: '',
                treatment_id: '',
                time: '',
                units: [],
                name: ''
            },
            editedItemUnits: [],






            dialogOrigin: false,
            searchOrigin: '',
            headersOrigin: [
                {
                    text: 'カルテ番号',
                    align: 'center',
                    sortable: true,
                    value: 'karte_number'
                },
                {
                    text: '名前',
                    align: 'left',
                    sortable: true,
                    value: 'name'
                },
                {
                    text: 'ステータス',
                    align: 'center',
                    sortable: true,
                    value: 'status'
                },
                {
                    text: '追加',
                    align: 'center',
                    sortable: false,
                    value: 'name'
                }
            ],
            patientsOrigin: [],
            validationErrorsOrigin: [],
            editedItemOrigin: {
                patient_id: '',
                treatment_id: '',
                time: '',
                units: [],
                name: ''
            },
            defaultItemOrigin: {
                patient_id: '',
                treatment_id: '',
                time: '',
                units: [],
                name: ''
            },


            unitNames: [],
            treatments: []









        }),
        watch: {
            dialog(val) {
                val || this.close()
            },
            dialogOrigin(val) {
                val || this.closeOrigin()
            },
            'editedItem.treatment_id'(val) {
                if (val > 0) {
                    const selected = this.treatments.find(function (item) {
                        return (item.id === val) ? item : null
                    })
                    this.editedItem.time = selected.time
                }
            },
            'editedItemOrigin.treatment_id'(val) {
                if (val > 0) {
                    const selected = this.treatments.find(function (item) {
                        return (item.id === val) ? item : null
                    })
                    this.editedItemOrigin.time = selected.time
                }
            }
        },
        created() {
            this.initialize()
            this.editedItem = Object.assign({}, this.defaultItem)
            this.initializeOrigin()
            this.editedItemOrigin = Object.assign({}, this.defaultItemOrigin)
            this.initializeUnitNames()
            this.initializeTreatment()
        },
        methods: {
            initialize() {
                axios
                    .get('/api/web-appointment-by-patient_settings')
                    .then(response => (
                        this.patients = response.data
                    ))
            },
            initializeOrigin() {
                axios
                    .get('/api/patients')
                    .then(response => (
                        this.patientsOrigin = response.data
                    ))
            },
            initializeUnitNames() {
                axios
                    .get('/api/web-appointment-by-patient_settings/units')
                    .then(response => (
                        this.unitNames = response.data
                    ))
            },
            initializeTreatment() {
                axios
                    .get('/api/treatments')
                    .then(response => (
                        this.treatments = response.data
                    ))
            },
            editItem(item) {
                this.editedIndex = this.patients.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true

                // this.editedItemUnits = this.editedItem.units
                // this.editedItemUnits = 1



            },
            deleteItem(item) {
                this.isDelete = true
                this.editedIndex = this.patients.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            close() {
                this.validationErrors = []
                this.dialog = false
                this.isDelete = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },
            async save() {
                if (this.editedIndex > -1) {
                    await axios
                        .post('/api/patients/' + this.editedItem.id)
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
                        .get('/api/web-appointment-by-patient_settings/delete/' + this.editedItem.id)
                        .then(response => (
                            console.log(response.status)
                        ))
                        .catch(error => (
                            console.log(error.response)
                        ))
                }

                this.close()
                this.initialize()
            },

            addItem(item) {
                this.editedItemOrigin.patient_id = item.id
                this.editedItemOrigin.name = item.name
                this.editedItemOrigin = Object.assign({}, this.editedItemOrigin)
                this.dialogOrigin = true
            },
            closeOrigin() {
                this.dialogOrigin = false
                this.validationErrorsOrigin = []
                setTimeout(() => {
                    this.editedItemOrigin = Object.assign({}, this.defaultItemOrigin)
                }, 300)
            },
            async add() {
                await axios
                    .post('/api/web-appointment-by-patient_settings', this.editedItemOrigin)
                    .then(response => (
                        console.log(response.status)
                    ))
                    .catch(error => (
                        this.validationErrorsOrigin = error.response.data.errors
                    ))
                if (this.validationErrorsOrigin.length === 0) {
                    this.closeOrigin()
                    this.initializeOrigin()
                    this.initialize()
                }

            }









        }
    }
</script>

<style scoped>
    .v-input--selection-controls {
        margin-top: 0;
    }
</style>