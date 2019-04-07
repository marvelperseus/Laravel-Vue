<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md6>
            <v-card class="py-3">
                <v-toolbar flat color="white">
                    <v-toolbar-title class="grey--text">メールテンプレート</v-toolbar-title>
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
                                        <v-flex xs12 md5>
                                            <v-text-field
                                                    v-model="editedItem.name"
                                                    prepend-icon="assignment_turned_in"
                                                    label="テンプレート名"
                                                    :error-messages="validationErrors.name"
                                            >
                                            </v-text-field>
                                            <v-text-field
                                                    v-model="editedItem.title"
                                                    prepend-icon="title"
                                                    label="件名"
                                                    :error-messages="validationErrors.title"
                                            >
                                            </v-text-field>
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
                                            <v-textarea
                                                    v-model="editedItem.body"
                                                    prepend-icon="notes"
                                                    label="本文"
                                                    rows="12"
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
                            :items="mailTemplates"
                            hide-actions
                    >
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.name }}</td>
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
        name: "MailTemplate",
        data: () => ({
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
                    text: '操作',
                    align: 'center',
                    sortable: false,
                    value: 'name'
                }
            ],
            mailTemplates: [],
            editedIndex: -1,
            editedItem: {
                id: '',
                name: '',
                title: '',
                body: ''
            },
            defaultItem: {
                id: '',
                name: '',
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
            }
        },
        created() {
            this.initialize()
            this.editedItem = Object.assign({}, this.defaultItem)
        },
        methods: {
            initialize() {
                axios
                    .get('/api/mail-templates')
                    .then(response => (
                        this.mailTemplates = response.data
                    ))
            },
            editItem(item) {
                this.editedIndex = this.mailTemplates.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            deleteItem(item) {
                this.isDelete = true
                this.editedIndex = this.mailTemplates.indexOf(item)
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
                        .post('/api/mail-templates/' + this.editedItem.id, this.editedItem)
                        .then(response => (
                            console.log(response.status)
                        ))
                        .catch(error => (
                            this.validationErrors = error.response.data.errors
                        ))
                } else {
                    await axios
                        .post('/api/mail-templates', this.editedItem)
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
                        .get('/api/mail-templates/delete/' + this.editedItem.id)
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