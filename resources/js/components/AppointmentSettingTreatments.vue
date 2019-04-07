<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md6>
            <v-card class="py-3">
                <v-toolbar flat color="white">
                    <v-toolbar-title class="grey--text">診療内容</v-toolbar-title>
                    <v-divider
                            class="ml-4"
                            inset
                            vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <v-btn slot="activator" color="accent" dark class="mb-2">追加</v-btn>
                        <v-card>
                            <template v-if="isDelete">

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

                            </template>
                            <template v-else>

                                <v-card-title>
                                    <span class="headline primary--text">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12>
                                                <v-text-field
                                                        v-model="editedItem.treatment"
                                                        prepend-icon="event_note"
                                                        label="診療内容"
                                                        :error-messages="validationErrors.treatment"
                                                >
                                                </v-text-field>
                                            </v-flex>
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
                                            <v-flex xs4 offset-xs2>
                                                <v-layout row align-center class="pt-2">
                                                    <v-flex>
                                                        <swatches
                                                                v-model="editedItem.color"
                                                                colors="material-light"
                                                                row-length="10"
                                                                swatch-size="24"
                                                                popover-to="left"
                                                        >
                                                        </swatches>
                                                    </v-flex>
                                                    <v-flex>カラー選択</v-flex>
                                                </v-layout>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
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
                                <v-progress-linear
                                        :indeterminate="true"
                                        color="secondary"
                                >
                                </v-progress-linear>
                            </div>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import Swatches from 'vue-swatches'
    import 'vue-swatches/dist/vue-swatches.min.css'

    export default {
        name: "AppointmentSettingTreatments",
        components: { Swatches },
        data: () => ({
            dialog: false,
            isDelete: false,
            validationErrors: [],
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
        computed: {
            formTitle() {
                return this.editedIndex === -1 ? '新しく追加する' : '内容を編集する'
            }
        },
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
            initialize() {
                axios
                    .get('/api/treatments')
                    .then(response => (
                        this.treatments = response.data
                    ))
            },
            editItem(item) {
                this.editedIndex = this.treatments.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            deleteItem(item) {
                this.isDelete = true
                this.editedIndex = this.treatments.indexOf(item)
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
                this.validationErrors = []
                if (this.editedIndex > -1) {
                    await axios
                        .post('/api/treatments/' + this.editedItem.id, this.editedItem)
                        .then(response => (
                            console.log(response.status)
                        ))
                        .catch(error => (
                            this.validationErrors = error.response.data.errors
                        ))
                } else {
                    await axios
                        .post('/api/treatments', this.editedItem)
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
                        .get('/api/treatments/delete/' + this.editedItem.id)
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