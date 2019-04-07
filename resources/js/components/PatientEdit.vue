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
                <v-tab key="edit">患者情報</v-tab>
                <v-tab key="appointments">診療履歴</v-tab>
                <v-tab key="amounts">契約金額</v-tab>

            </v-tabs>

            <v-tabs-items
                    v-model="activeTab"
            >
                <v-tab-item key="edit">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">患者情報更新</v-toolbar-title>
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
                                        <v-layout>
                                            <v-flex xs6>
                                                <v-select
                                                        v-model="patient.status"
                                                        :items="statusItems"
                                                        item-text="label"
                                                        item-value="value"
                                                        prepend-icon="how_to_reg"
                                                        label="ステータス"
                                                        :error-messages="validationErrors.status"
                                                ></v-select>
                                            </v-flex>
                                        </v-layout>

                                        <v-text-field
                                                v-model="patient.karte_number"
                                                prepend-icon="assignment_ind"
                                                label="カルテ番号"
                                                :error-messages="validationErrors.karte_number"
                                        >
                                        </v-text-field>

                                        <v-text-field
                                                v-model="patient.insurance_number"
                                                prepend-icon="favorite_border"
                                                label="保険者番号"
                                                :error-messages="validationErrors.insurance_number"
                                        >
                                        </v-text-field>

                                        <v-layout wrap class="mt-3">
                                            <v-flex xs12>
                                                <v-subheader class="pl-0 mt-3">
                                                    <v-icon class="mr-1">face</v-icon>
                                                    患者氏名
                                                </v-subheader>
                                            </v-flex>
                                            <v-flex xs5>
                                                <v-text-field
                                                        v-model="patient.name"
                                                        label="名前"
                                                        :error-messages="validationErrors.name"
                                                        class="pt-0"
                                                >
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs6 offset-xs1>
                                                <v-text-field
                                                        v-model="patient.kana_name"
                                                        label="フリガナ"
                                                        :error-messages="validationErrors.kana_name"
                                                        class="pt-0"
                                                >
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>

                                        <v-subheader class="pl-0 mt-3">
                                            <v-icon class="mr-1">supervisor_account</v-icon>
                                            性別
                                        </v-subheader>
                                        <v-radio-group
                                                v-model="patient.gender"
                                                row
                                        >
                                            <v-radio label="男" value="male"></v-radio>
                                            <v-radio label="女" value="female"></v-radio>
                                        </v-radio-group>

                                        <v-text-field
                                                v-model="patient.birthday"
                                                prepend-icon="cake"
                                                label="生年月日"
                                                :error-messages="validationErrors.birthday"
                                        >
                                        </v-text-field>

                                        <v-layout
                                                wrap
                                                align-center
                                                class="my-3"
                                        >
                                            <v-flex xs2>
                                                <v-subheader class="pl-0">
                                                    <v-icon class="mr-1">location_on</v-icon>
                                                    住所
                                                </v-subheader>
                                            </v-flex>
                                            <v-flex xs10>
                                                <v-text-field
                                                        v-model="patient.zip"
                                                        label="郵便番号"
                                                        :error-messages="validationErrors.zip"
                                                >
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-text-field
                                                        v-model="patient.address"
                                                        label="住所"
                                                        :error-messages="validationErrors.address"
                                                        class="pt-0"
                                                >
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs12 md5 offset-md1>
                                        <v-subheader class="pl-0">
                                            <v-icon class="mr-1">contacts</v-icon>
                                            連絡方法
                                        </v-subheader>
                                        <v-layout row justify-start>
                                            <v-checkbox
                                                    v-model="patient.contact_way"
                                                    label="電話"
                                                    value="tel"
                                            >
                                            </v-checkbox>
                                            <v-checkbox
                                                    v-model="patient.contact_way"
                                                    label="E-mail"
                                                    value="email"
                                            >
                                            </v-checkbox>
                                        </v-layout>

                                        <v-text-field
                                                v-model="patient.tel"
                                                prepend-icon="call"
                                                label="電話番号"
                                                type="tel"
                                                :error-messages="validationErrors.tel"
                                        >
                                        </v-text-field>

                                        <v-text-field
                                                v-model="patient.email"
                                                prepend-icon="mail_outline"
                                                label="E-mail"
                                                type="email"
                                                :error-messages="validationErrors.email"
                                        >
                                        </v-text-field>

                                        <v-textarea
                                                v-model="patient.note"
                                                prepend-icon="comment"
                                                label="メモ"
                                                :error-messages="validationErrors.note"
                                                class="mt-5"
                                        ></v-textarea>
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

                <v-tab-item key="appointments">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">診療履歴</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>

                            <p>※ 準備中</p>

                        </v-card-text>
                    </v-card>
                </v-tab-item>

                <v-tab-item key="amounts">
                    <v-card>
                        <v-toolbar flat class="justify-center align-center">
                            <v-toolbar-title class="grey--text">契約金額</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>

                            <p>※ 準備中</p>

                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "PatientEdit",
        data: () => ({
            activeTab: null,
            statusItems: [
                {value: 'first', label: '初診'},
                {value: 'before', label: '治療前'},
                {value: 'treatment', label: '治療中'},
                {value: 'suspend', label: '中断'},
                {value: 'complete', label: '治療済み'}
            ],
            patient: [],
            flashMessage: false,
            validationErrors: []
        }),
        created() {
            axios
                .get('/api/patients/' + this.$route.params.id)
                .then(response => (
                    this.patient = response.data
                ))
        },
        methods: {
            save() {
                this.flashMessage = false
                this.validationErrors = []
                axios
                    .post('/api/patients/' + this.patient.id, Object.assign({}, this.patient))
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