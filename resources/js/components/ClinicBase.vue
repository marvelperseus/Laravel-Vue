<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md6>
            <v-card>
                <v-toolbar flat class="justify-center align-center">
                    <v-toolbar-title class="grey--text">基本情報</v-toolbar-title>
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
                                v-model="clinic.name"
                                id="name"
                                name="name"
                                prepend-icon="location_city"
                                label="医院名"
                                :error-messages="validationErrors.name"
                        >
                        </v-text-field>
                        <v-text-field
                                v-model="clinic.director"
                                id="director"
                                name="director"
                                prepend-icon="person"
                                label="院長"
                                :error-messages="validationErrors.director"
                        >
                        </v-text-field>
                        <v-text-field
                                v-model="clinic.tel"
                                id="tel"
                                name="tel"
                                prepend-icon="phone"
                                label="電話番号"
                                type="tel"
                                :error-messages="validationErrors.tel"
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
                                        v-model="clinic.zip"
                                        label="郵便番号"
                                        :error-messages="validationErrors.zip"
                                >
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field
                                        v-model="clinic.address"
                                        label="住所"
                                        :error-messages="validationErrors.address"
                                        class="pt-0"
                                >
                                </v-text-field>
                            </v-flex>
                        </v-layout>

                        <v-text-field
                                v-model="clinic.url"
                                id="url"
                                name="url"
                                prepend-icon="domain"
                                label="URL"
                                :error-messages="validationErrors.url"
                        >
                        </v-text-field>

                        <v-text-field
                                v-model="clinic.unit_number"
                                id="unit_number"
                                name="unit_number"
                                prepend-icon="view_column"
                                label="使用可能ユニット数"
                                disabled
                        >
                        </v-text-field>
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
        name: "ClinicBase",
        data: () => ({
            clinic: [],
            flashMessage: false,
            validationErrors: []
        }),
        created() {
            axios
                .get('/api/clinic')
                .then(response => (
                    this.clinic = response.data
                ))
        },
        methods: {
            update: function () {
                this.flashMessage = false
                this.validationErrors = []
                axios
                    .post('/api/clinic', this.clinic)
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

</style>