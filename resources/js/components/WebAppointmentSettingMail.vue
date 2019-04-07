<template>
    <v-layout justify-center>
        <v-flex xs12 md6>

            <v-tabs
                    v-model="activeTab"
                    color="transparent"
                    slider-color="secondary"
                    grow
                    class="mb-3"
            >
                <v-tab key="confirm">予約確定</v-tab>
                <v-tab key="temporary">仮予約</v-tab>
                <v-tab key="change">変更</v-tab>
                <v-tab key="cancel">キャンセル</v-tab>
            </v-tabs>

            <v-tabs-items
                    v-model="activeTab"
            >
                <v-tab-item key="confirm">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">予約確定メール</v-toolbar-title>
                        </v-toolbar>
                        <v-form
                                v-on:submit.prevent="updateConfirm"
                                class="pa-4"
                        >
                            <v-card-text>
                                <v-alert
                                        :value="flashMessageConfirm"
                                        type="success"
                                        transition="slide-y-reverse-transition"
                                        class="mb-4"
                                >
                                    更新しました
                                </v-alert>

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

                                <v-layout class="mb-3">
                                    <v-flex xs12 sm6>
                                        <v-select
                                                v-model="loadMailTemplateIdConfirm"
                                                :items="mailTemplates"
                                                item-text="name"
                                                item-value="id"
                                                prepend-icon="assignment_turned_in"
                                                label="メールテンプレート"
                                        ></v-select>
                                    </v-flex>
                                </v-layout>

                                <v-text-field
                                        v-model="webAppointmentMailConfirm.title"
                                        prepend-icon="title"
                                        label="件名"
                                        :error-messages="validationErrorsConfirm.title"
                                >
                                </v-text-field>
                                <v-textarea
                                        v-model="webAppointmentMailConfirm.body"
                                        prepend-icon="notes"
                                        label="本文"
                                        rows="12"
                                        :error-messages="validationErrorsConfirm.body"
                                ></v-textarea>
                            </v-card-text>
                            <v-card-actions class="justify-center">
                                <v-btn type="submit" color="accent">更新</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-tab-item>

                <v-tab-item key="temporary">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">仮予約メール</v-toolbar-title>
                        </v-toolbar>
                        <v-form
                                v-on:submit.prevent="updateTemporary"
                                class="pa-4"
                        >
                            <v-card-text>
                                <v-alert
                                        :value="flashMessageTemporary"
                                        type="success"
                                        transition="slide-y-reverse-transition"
                                        class="mb-4"
                                >
                                    更新しました
                                </v-alert>

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

                                <v-layout class="mb-3">
                                    <v-flex xs12 sm6>
                                        <v-select
                                                v-model="loadMailTemplateIdTemporary"
                                                :items="mailTemplates"
                                                item-text="name"
                                                item-value="id"
                                                prepend-icon="assignment_turned_in"
                                                label="メールテンプレート"
                                        ></v-select>
                                    </v-flex>
                                </v-layout>

                                <v-text-field
                                        v-model="webAppointmentMailTemporary.title"
                                        prepend-icon="title"
                                        label="件名"
                                        :error-messages="validationErrorsTemporary.title"
                                >
                                </v-text-field>
                                <v-textarea
                                        v-model="webAppointmentMailTemporary.body"
                                        prepend-icon="notes"
                                        label="本文"
                                        rows="12"
                                        :error-messages="validationErrorsTemporary.body"
                                ></v-textarea>
                            </v-card-text>
                            <v-card-actions class="justify-center">
                                <v-btn type="submit" color="accent">更新</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-tab-item>

                <v-tab-item key="change">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">変更メール</v-toolbar-title>
                        </v-toolbar>
                        <v-form
                                v-on:submit.prevent="updateChange"
                                class="pa-4"
                        >
                            <v-card-text>
                                <v-alert
                                        :value="flashMessageChange"
                                        type="success"
                                        transition="slide-y-reverse-transition"
                                        class="mb-4"
                                >
                                    更新しました
                                </v-alert>

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

                                <v-layout class="mb-3">
                                    <v-flex xs12 sm6>
                                        <v-select
                                                v-model="loadMailTemplateIdChange"
                                                :items="mailTemplates"
                                                item-text="name"
                                                item-value="id"
                                                prepend-icon="assignment_turned_in"
                                                label="メールテンプレート"
                                        ></v-select>
                                    </v-flex>
                                </v-layout>

                                <v-text-field
                                        v-model="webAppointmentMailChange.title"
                                        prepend-icon="title"
                                        label="件名"
                                        :error-messages="validationErrorsChange.title"
                                >
                                </v-text-field>
                                <v-textarea
                                        v-model="webAppointmentMailChange.body"
                                        prepend-icon="notes"
                                        label="本文"
                                        rows="12"
                                        :error-messages="validationErrorsChange.body"
                                ></v-textarea>
                            </v-card-text>
                            <v-card-actions class="justify-center">
                                <v-btn type="submit" color="accent">更新</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-tab-item>

                <v-tab-item key="cancel">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">キャンセルメール</v-toolbar-title>
                        </v-toolbar>
                        <v-form
                                v-on:submit.prevent="updateCancel"
                                class="pa-4"
                        >
                            <v-card-text>
                                <v-alert
                                        :value="flashMessageCancel"
                                        type="success"
                                        transition="slide-y-reverse-transition"
                                        class="mb-4"
                                >
                                    更新しました
                                </v-alert>

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

                                <v-layout class="mb-3">
                                    <v-flex xs12 sm6>
                                        <v-select
                                                v-model="loadMailTemplateIdCancel"
                                                :items="mailTemplates"
                                                item-text="name"
                                                item-value="id"
                                                prepend-icon="assignment_turned_in"
                                                label="メールテンプレート"
                                        ></v-select>
                                    </v-flex>
                                </v-layout>

                                <v-text-field
                                        v-model="webAppointmentMailCancel.title"
                                        prepend-icon="title"
                                        label="件名"
                                        :error-messages="validationErrorsCancel.title"
                                >
                                </v-text-field>
                                <v-textarea
                                        v-model="webAppointmentMailCancel.body"
                                        prepend-icon="notes"
                                        label="本文"
                                        rows="12"
                                        :error-messages="validationErrorsCancel.body"
                                ></v-textarea>
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
        name: "WebAppointmentSettingMail",
        data: () => ({
            activeTab: null,
            mailTemplates: [],
            webAppointmentMails: [],
            webAppointmentMailConfirm: [],
            validationErrorsConfirm: [],
            flashMessageConfirm: false,
            loadMailTemplateIdConfirm: null,
            webAppointmentMailTemporary: [],
            validationErrorsTemporary: [],
            flashMessageTemporary: false,
            loadMailTemplateIdTemporary: null,
            webAppointmentMailChange: [],
            validationErrorsChange: [],
            flashMessageChange: false,
            loadMailTemplateIdChange: null,
            webAppointmentMailCancel: [],
            validationErrorsCancel: [],
            flashMessageCancel: false,
            loadMailTemplateIdCancel: null
        }),
        watch: {
            loadMailTemplateIdConfirm(val) {
                this.setMailTemplate(val, this.webAppointmentMailConfirm)
            },
            loadMailTemplateIdTemporary(val) {
                this.setMailTemplate(val, this.webAppointmentMailTemporary)
            },
            loadMailTemplateIdChange(val) {
                this.setMailTemplate(val, this.webAppointmentMailChange)
            },
            loadMailTemplateIdCancel(val) {
                this.setMailTemplate(val, this.webAppointmentMailCancel)
            }
        },
        created() {
            this.initialize()
            this.initializeMailTemplate()
        },
        methods: {
            async initialize() {
                await axios
                    .get('/api/web-appointment-mails')
                    .then(response => (
                        this.webAppointmentMails = response.data
                    ))

                for (let i = 0; i < this.webAppointmentMails.length; i++) {
                    const item = this.webAppointmentMails[i]
                    if (item.type === 'confirm') {
                        this.webAppointmentMailConfirm.type = 'confirm'
                        this.webAppointmentMailConfirm.title = item.title
                        this.webAppointmentMailConfirm.body = item.body
                        this.webAppointmentMailConfirm = Object.assign({}, this.webAppointmentMailConfirm)
                    } else if (item.type === 'temporary') {
                        this.webAppointmentMailTemporary.type = 'temporary'
                        this.webAppointmentMailTemporary.title = item.title
                        this.webAppointmentMailTemporary.body = item.body
                    } else if (item.type === 'change') {
                        this.webAppointmentMailChange.type = 'change'
                        this.webAppointmentMailChange.title = item.title
                        this.webAppointmentMailChange.body = item.body
                    } else if (item.type === 'cancel') {
                        this.webAppointmentMailCancel.type = 'cancel'
                        this.webAppointmentMailCancel.title = item.title
                        this.webAppointmentMailCancel.body = item.body
                    }
                }
            },
            initializeMailTemplate() {
                axios
                    .get('/api/mail-templates')
                    .then(response => (
                        this.mailTemplates = response.data
                    ))
            },
            setMailTemplate(val, target) {
                const loaded = this.mailTemplates.find(function (item) {
                    return (item.id === val) ? item : null
                })
                target.title = loaded.title
                target.body = loaded.body
            },
            updateConfirm() {
                this.flashMessageConfirm = false
                this.validationErrorsConfirm = []
                this.webAppointmentMailConfirm.type = 'confirm'
                axios
                    .post('/api/web-appointment-mails', Object.assign({}, this.webAppointmentMailConfirm))
                    .then(response => (
                        this.flashMessageConfirm = response.status === 200
                    ))
                    .catch(error => (
                        this.validationErrorsConfirm = error.response.data.errors
                    ))
            },
            updateTemporary() {
                this.flashMessageTemporary = false
                this.validationErrorsTemporary = []
                this.webAppointmentMailTemporary.type = 'temporary'
                axios
                    .post('/api/web-appointment-mails', Object.assign({}, this.webAppointmentMailTemporary))
                    .then(response => (
                        this.flashMessageTemporary = response.status === 200
                    ))
                    .catch(error => (
                        this.validationErrorsTemporary = error.response.data.errors
                    ))
            },
            updateChange() {
                this.flashMessageChange = false
                this.validationErrorsChange = []
                this.webAppointmentMailChange.type = 'change'
                axios
                    .post('/api/web-appointment-mails', Object.assign({}, this.webAppointmentMailChange))
                    .then(response => (
                        this.flashMessageChange = response.status === 200
                    ))
                    .catch(error => (
                        this.validationErrorsChange = error.response.data.errors
                    ))
            },
            updateCancel() {
                this.flashMessageCancel = false
                this.validationErrorsCancel = []
                this.webAppointmentMailCancel.type = 'cancel'
                axios
                    .post('/api/web-appointment-mails', Object.assign({}, this.webAppointmentMailCancel))
                    .then(response => (
                        this.flashMessageCancel = response.status === 200
                    ))
                    .catch(error => (
                        this.validationErrorsCancel = error.response.data.errors
                    ))
            }
        }
    }
</script>

<style scoped>

</style>