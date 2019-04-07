<template>
    <v-layout align-center justify-center>
        <v-flex xs12 md10>
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
                            <td>{{ props.item.tel }}</td>
                            <td>{{ props.item.email }}</td>
                            <td class="text-xs-center">{{ props.item.status_label }}</td>
                            <td class="justify-center align-center layout pa-0">
                                <v-btn
                                        flat
                                        icon
                                        :to="'/patient/edit/' + props.item.id"
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
                            <span class="headline primary--text">アイテムを削除する</span>
                        </v-card-title>

                        <v-card-text>
                            <p>「{{ editedItem.name }}」さんの情報を削除してもよろしいですか？</p>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="accent" flat @click="close">キャンセル</v-btn>
                            <v-btn color="accent" flat @click="destroy">削除する</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "PatientIndex",
        data: () => ({
            dialog: false,
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
                    text: '電話番号',
                    align: 'left',
                    sortable: false,
                    value: 'tel'
                },
                {
                    text: 'メールアドレス',
                    align: 'left',
                    sortable: false,
                    value: 'email'
                },
                {
                    text: 'ステータス',
                    align: 'center',
                    sortable: true,
                    value: 'status_label'
                },
                {
                    text: '操作',
                    align: 'center',
                    sortable: false,
                    value: 'name'
                }
            ],
            patients: [],
            editedIndex: -1,
            editedItem: {
                id: '',
                name: ''
            },
            defaultItem: {
                id: '',
                name: ''
            }
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
            initialize() {
                axios
                    .get('/api/patients')
                    .then(response => (
                        this.patients = response.data
                    ))
            },
            deleteItem(item) {
                this.editedIndex = this.patients.indexOf(item)
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
            async destroy() {
                if (this.editedIndex > -1) {
                    await axios
                        .get('/api/patients/delete/' + this.editedItem.id)
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