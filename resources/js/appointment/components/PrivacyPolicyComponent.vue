<template>
    <v-app id="appointment">
        <v-content class="v-content-main-tag">
            <template v-if="loading">
                <v-layout
                        align-center
                        justify-center
                        style="height: 70vh;"
                >
                    <v-progress-circular
                            :size="80"
                            :width="8"
                            color="primary"
                            indeterminate
                    ></v-progress-circular>
                </v-layout>
            </template>
            <template v-else>
                <header-component v-bind:clinic="clinic"></header-component>
                <v-container fluid>
                    <v-layout align-center justify-center>
                        <v-flex xs12 md6>
                            <v-card class="pa-5">
                                <v-card-text>
                                    <p class="is-pre-wrap">*プライバシーポリシー</p>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
                <footer-component v-bind:clinic="clinic"></footer-component>
            </template>
        </v-content>
    </v-app>
</template>

<script>
    import Header from './Header.vue'
    import Footer from './Footer.vue'

    export default {
        name: "PrivacyPolicyComponent",
        components: {
            'header-component': Header,
            'footer-component': Footer
        },
        props: ['appkey'],
        data: () => ({
            loading: true,
            clinic: []
        }),
        created() {
            this.initialize()
        },
        methods: {
            async initialize() {
                await axios
                    .get('/api/web/appointment/clinic/' + this.appkey)
                    .then(response => (
                        this.clinic = response.data
                    ))

                this.loading = false
            }
        }
    }
</script>

<style scoped>
    .is-pre-wrap {
        white-space: pre-wrap;
    }

    @media only screen and (max-width: 768px) {
        .v-content-main-tag {
            padding-top: 56px !important;
        }

        .pa-5 {
            padding: 24px 0 48px 0 !important;
        }
        .v-stepper__content {
            padding: 24px 0 16px;
        }
    }
</style>