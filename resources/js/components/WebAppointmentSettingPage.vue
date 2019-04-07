<template>
    <v-layout justify-center>
        <v-flex xs12 md10>
            <v-card>
                <v-toolbar flat class="justify-center align-center">
                    <v-toolbar-title class="grey--text">ページ設定</v-toolbar-title>
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
                            <v-flex xs12 md6>
                                <v-subheader class="pl-0">
                                    <v-icon class="mr-1">subject</v-icon>
                                    予約トップページ
                                </v-subheader>
                                <v-textarea
                                        v-model="webAppointmentPage.special_note"
                                        label="特記事項"
                                        :error-messages="validationErrors.special_note"
                                ></v-textarea>

                                <v-subheader class="pl-0">
                                    <v-icon class="mr-1">mood</v-icon>
                                    予約完了ページ
                                </v-subheader>
                                <v-textarea
                                        v-model="webAppointmentPage.end_message"
                                        label="メッセージ"
                                        :error-messages="validationErrors.end_message"
                                ></v-textarea>

                                <v-subheader class="pl-0">
                                    <v-icon class="mr-1">mood_bad</v-icon>
                                    キャンセルされた時
                                </v-subheader>
                                <v-textarea
                                        v-model="webAppointmentPage.cancel_message"
                                        label="メッセージ"
                                        :error-messages="validationErrors.cancel_message"
                                ></v-textarea>
                            </v-flex>
                            <v-flex xs12 md5 offset-md1>
                                <v-subheader class="pl-0">
                                    <v-icon class="mr-1">category</v-icon>
                                    入力フォーム設定
                                </v-subheader>
                                <v-chip label outline small color="error">必須</v-chip>
                                <v-layout row>
                                    <v-checkbox
                                            v-model="webAppointmentPage.form_objects"
                                            label="お名前"
                                            value="patient_name"
                                            disabled
                                    >
                                    </v-checkbox>
                                    <v-checkbox
                                            v-model="webAppointmentPage.form_objects"
                                            label="電話番号"
                                            value="patient_tel"
                                            disabled
                                    >
                                    </v-checkbox>
                                    <v-checkbox
                                            v-model="webAppointmentPage.form_objects"
                                            label="E-mail"
                                            value="patient_email"
                                            disabled
                                    >
                                    </v-checkbox>
                                    <v-checkbox
                                            v-model="webAppointmentPage.form_objects"
                                            label="生年月日"
                                            value="patient_birthday"
                                            disabled
                                    >
                                    </v-checkbox>
                                </v-layout>
                                <v-chip label outline small color="accent">選択</v-chip>
                                <v-layout row>
                                    <v-checkbox
                                            v-model="webAppointmentPage.form_objects"
                                            label="お名前（フリガナ）"
                                            value="patient_name_kana"
                                    >
                                    </v-checkbox>
                                    <v-checkbox
                                            v-model="webAppointmentPage.form_objects"
                                            label="性別"
                                            value="patient_gender"
                                    >
                                    </v-checkbox>
                                </v-layout>
                                <v-layout row>
                                    <v-checkbox
                                            v-model="webAppointmentPage.form_objects"
                                            label="ご住所"
                                            value="patient_zip_address"
                                    >
                                    </v-checkbox>
                                    <v-checkbox
                                            v-model="webAppointmentPage.form_objects"
                                            label="ご要望・ご質問など"
                                            value="note"
                                    >
                                    </v-checkbox>
                                </v-layout>

                                <v-subheader class="pl-0 mt-3">
                                    <v-icon class="mr-1">link</v-icon>
                                    予約完了後、リダイレクトするURL
                                </v-subheader>
                                <v-text-field
                                        v-model="webAppointmentPage.thankspage_url"
                                        label="サンクスページURL"
                                        :error-messages="validationErrors.thankspage_url"
                                >
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card-text>

                    <v-card-actions class="justify-center">
                        <v-spacer></v-spacer>
                        <v-btn type="submit" color="accent">更新</v-btn>
                    </v-card-actions>

                </v-form>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "WebAppointmentSettingPage",
        data: () => ({
            webAppointmentPage: [],
            flashMessage: false,
            validationErrors: []
        }),
        created() {
            axios
                .get('/api/web-appointment-pages')
                .then(response => (
                    this.webAppointmentPage = response.data
                ))
        },
        methods: {
            save() {
                this.flashMessage = false
                this.validationErrors = []
                axios
                    .post('/api/web-appointment-pages', Object.assign({}, this.webAppointmentPage))
                    .then(response => (
                        this.flashMessage = response.status === 200
                    ))
                    .catch(error => (
                        this.validationErrors = error.response.data.errors
                    ))
            }
        }
    }
</script>

<style scoped>
    .v-input--selection-controls {
        margin-top: 0;
    }
</style>