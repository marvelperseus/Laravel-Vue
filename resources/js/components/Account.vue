<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md6>
            <v-card>
                <v-toolbar flat class="justify-center align-center">
                    <v-toolbar-title class="grey--text">アカウント情報</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-list two-line>

                        <v-list-tile>
                            <v-list-tile-avatar><v-icon>location_city</v-icon></v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-sub-title>医院ID</v-list-tile-sub-title>
                                <v-list-tile-title>{{ account.name }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>

                        <v-divider inset></v-divider>

                        <v-list-tile>
                            <v-list-tile-avatar><v-icon>mail_outline</v-icon></v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-sub-title>メールアドレス</v-list-tile-sub-title>
                                <v-list-tile-title>{{ account.email }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>

                        <v-divider inset></v-divider>

                        <v-list-tile>
                            <v-list-tile-avatar><v-icon>assignment</v-icon></v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-sub-title>プラン</v-list-tile-sub-title>
                                <v-list-tile-title>{{ account.role_name }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>

                    </v-list>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "Account",
        data: () => ({
            account: []
        }),
        created() {
            axios
                .get('/api/account')
                .then(response => (this.account = response.data))
        }
    }
</script>

<style scoped>

</style>