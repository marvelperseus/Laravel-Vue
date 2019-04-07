<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md6>
            <v-card>
                <v-toolbar flat class="justify-center align-center">
                    <v-toolbar-title class="grey--text">予約表設定</v-toolbar-title>
                </v-toolbar>
                <v-form
                        v-on:submit.prevent="update"
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


                        <v-text-field
                                v-model="appointmentTableSettings.display_unit_number"
                                prepend-icon="view_column"
                                label="表示ユニット（列）数"
                                :error-messages="validationErrors.display_unit_number"
                        >
                        </v-text-field>

                        <v-subheader class="pl-1">
                            <v-icon class="mr-1">airline_seat_recline_extra</v-icon>
                            表示ユニットの列名
                        </v-subheader>
                        <v-layout justify-center>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex
                                            v-for="unitName in appointmentTableSettings.unit_names_array"
                                            :key="unitName.name"
                                            xs6
                                            class="px-2"
                                    >
                                        <v-text-field
                                                :name="unitName.name"
                                                :label="unitName.label"
                                                v-model="unitName.value"
                                        >
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>

                        <v-subheader class="pl-1">
                            <v-icon class="mr-1">view_day</v-icon>
                            時間区切り
                        </v-subheader>
                        <v-radio-group
                                v-model="appointmentTableSettings.time_delimiter"
                                row
                        >
                            <v-radio label="15分" :value="15"></v-radio>
                            <v-radio label="30分" :value="30"></v-radio>
                            <v-radio label="45分" :value="45"></v-radio>
                            <v-radio label="60分" :value="60"></v-radio>
                        </v-radio-group>

                        <v-subheader class="pl-1">
                            <v-icon class="mr-1">visibility</v-icon>
                            予約アイテム表示項目
                        </v-subheader>
                        <v-layout row>
                            <v-checkbox
                                    v-model="appointmentTableSettings.display_items_array"
                                    label="名前"
                                    value="name"
                            >
                            </v-checkbox>
                            <v-checkbox
                                    v-model="appointmentTableSettings.display_items_array"
                                    label="年齢"
                                    value="age"
                            >
                            </v-checkbox>
                            <v-checkbox
                                    v-model="appointmentTableSettings.display_items_array"
                                    label="カルテ番号"
                                    value="karte_number"
                            >
                            </v-checkbox>
                            <v-checkbox
                                    v-model="appointmentTableSettings.display_items_array"
                                    label="診療内容"
                                    value="treatment"
                            >
                            </v-checkbox>
                            <v-checkbox
                                    v-model="appointmentTableSettings.display_items_array"
                                    label="メモ"
                                    value="note"
                            >
                            </v-checkbox>
                        </v-layout>

                    </v-card-text>
                    <v-card-actions class="justify-center">
                        <v-btn type="submit" color="accent">更新</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "AppointmentSettingTable",
        data: () => ({
            appointmentTableSettings: [],
            flashMessage: false,
            validationErrors: []
        }),
        created() {
            axios
                .get('/api/appointment-table-settings')
                .then(response => (
                    this.appointmentTableSettings = response.data
                ))
        },
        methods: {
            update: function () {
                this.validationErrors = []
                axios
                    .post('/api/appointment-table-settings', this.appointmentTableSettings)
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