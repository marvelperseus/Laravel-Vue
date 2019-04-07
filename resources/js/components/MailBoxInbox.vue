<template>
    <v-layout align-center justify-center>
        <v-flex xs12 md8>
            <v-card class="py-3">
                <v-toolbar flat color="white">
                    <v-toolbar-title class="grey--text">受信メール一覧</v-toolbar-title>
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
                            :items="mailInboxes"
                            :search="search"
                            select-all
                            disable-initial-sort
                            rows-per-page-text="表示件数"
                    >
                        <template slot="items" slot-scope="props">
                            <td>
                                <v-checkbox
                                        v-model="props.selected"
                                        accent
                                        hide-details
                                >
                                </v-checkbox>
                            </td>
                            <td class="text-xs-center" @click="showItem(props.item)">
                                <template v-if="!props.item.read_at">
                                    <v-icon color="accent">fiber_manual_record</v-icon>
                                </template>
                            </td>
                            <td @click="showItem(props.item)">{{ props.item.name }}</td>
                            <td @click="showItem(props.item)">{{ props.item.email }}</td>
                            <td class="text-xs-center" @click="showItem(props.item)">{{ props.item.type_label }}</td>
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

                <v-card-actions class="d-flex execute-actions">
                    <p>選択したアイテムを</p>
                    <v-select
                            v-model="executeItem"
                            :items="executeItems"
                            item-text="label"
                            item-value="value"
                            label="操作内容"
                    ></v-select>
                    <v-btn color="accent" @click="selectedExecute">実行</v-btn>
                </v-card-actions>

                <v-dialog v-model="dialog" max-width="500">
                    <v-card>
                        <v-card-title>
                            <span class="headline primary--text">メール詳細</span>
                        </v-card-title>

                        <v-card-text>
                            <v-text-field
                                    v-model="editedItem.type_label"
                                    prepend-icon="all_inbox"
                                    label="種類"
                                    readonly
                            >
                            </v-text-field>
                            <v-text-field
                                    v-model="editedItem.created_at"
                                    prepend-icon="send"
                                    label="送信日時"
                                    readonly
                            >
                            </v-text-field>
                            <v-text-field
                                    v-model="editedItem.name"
                                    prepend-icon="face"
                                    label="送信者"
                                    readonly
                            >
                            </v-text-field>
                            <v-text-field
                                    v-model="editedItem.email"
                                    prepend-icon="mail_outline"
                                    label="送信者メールアドレス"
                                    readonly
                            >
                            </v-text-field>
                            <v-textarea
                                    v-model="editedItem.body"
                                    prepend-icon="notes"
                                    label="本文"
                                    readonly
                            ></v-textarea>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="accent" flat @click="close">閉じる</v-btn>
                            <v-btn color="accent" flat @click="unread">未読にする</v-btn>
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
        name: "MailBoxInbox",
        data: () => ({
            dialog: false,
            search: '',
            selected: [],
            headers: [
                {
                    text: '未読',
                    align: 'center',
                    sortable: true,
                    value: 'read_at'
                },
                {
                    text: '名前',
                    align: 'left',
                    sortable: true,
                    value: 'name'
                },
                {
                    text: 'メールアドレス',
                    align: 'left',
                    sortable: false,
                    value: 'email'
                },
                {
                    text: '種類',
                    align: 'center',
                    sortable: true,
                    value: 'type_label'
                }
            ],
            mailInboxes: [],
            editedItem: {
                id: '',
                type: '',
                type_label: '',
                name: '',
                email: '',
                body: '',
                read_at: '',
                created_at: ''
            },
            defaultItem: {
                id: '',
                type: '',
                type_label: '',
                name: '',
                email: '',
                body: '',
                read_at: '',
                created_at: ''
            },
            executeItem: '',
            executeItems: [
                {value: 'delete', label: '削除する'},
                {value: 'read', label: '既読にする'},
                {value: 'unread', label: '未読にする'},
            ],
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
                    .get('/api/mail-inboxes')
                    .then(response => (
                        this.mailInboxes = response.data
                    ))

                this.$eventHub.$emit('init-toolbar-mailinbox')
            },
            async showItem(item) {
                this.editedItem = Object.assign({}, item)
                this.dialog = true

                if (this.editedItem.read_at === null) {
                    await axios
                        .post('/api/mail-inboxes/read/' + this.editedItem.id)
                        .then(response => (
                            console.log(response.status)
                        ))
                        .catch(error => (
                            console.log(error.response)
                        ))
                    this.initialize()
                }
            },
            close() {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                }, 300)
            },
            async unread() {
                await axios
                    .post('/api/mail-inboxes/unread/' + this.editedItem.id)
                    .then(response => (
                        console.log(response.status)
                    ))
                    .catch(error => (
                        console.log(error.response)
                    ))

                this.close()
                this.initialize()
            },
            async destroy() {

                console.log('/api/mail-inboxes/delete/' + this.editedItem.id)

                await axios
                    .get('/api/mail-inboxes/delete/' + this.editedItem.id)
                    .then(response => (
                        console.log(response.status)
                    ))
                    .catch(error => (
                        console.log(error.response)
                    ))

                this.close()
                this.initialize()
            },
            async selectedExecute() {
                const ids = []
                Object.keys(this.selected).forEach(function (key) {
                    ids.push(this[key].id)
                }, this.selected)

                if (ids.length > 0) {
                    await axios
                        .post('/api/mail-inboxes/selected/' + this.executeItem, Object.assign({}, {ids: ids}))
                        .then(response => (
                            console.log(response.status)
                        ))
                        .catch(error => (
                            console.log(error.response)
                        ))

                    this.selected = []
                    this.initialize()
                }
            }
        }
    }
</script>

<style scoped>
    .v-card__actions.d-flex.execute-actions > p,
    .v-card__actions.d-flex.execute-actions > div,
    .v-card__actions.d-flex.execute-actions > button {
        flex: 0 0 auto !important;
    }

    .v-card__actions.d-flex.execute-actions > div {
        margin-left: 16px;
        margin-right: 16px;
        width: 120px;
    }
</style>