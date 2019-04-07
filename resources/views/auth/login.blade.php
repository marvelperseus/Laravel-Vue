@extends('layouts.base')

@section('content')
    <template>
        <v-app id="login">
            <v-content>
                <v-container fluid fill-height>
                    <v-layout align-center justify-center>
                        <v-flex xs12 sm8 md4>
                            <h1 class="text-xs-center"><span class="primary--text">ORTHO</span> MANAGER</h1>
                            <p class="text-xs-center mb-4">Log in to your account.</p>
                            <v-card class="pa-4">
                                <v-card-text>
                                    <v-form
                                            method="POST"
                                            action="{{ route('login') }}"
                                    >
                                        @csrf
                                        <v-text-field
                                                id="email"
                                                prepend-icon="person"
                                                name="email"
                                                label="E-mail"
                                                type="email"
                                                autofocus
                                                value="{{ old('email') }}"
                                                @if ($errors->has('email'))
                                                error-messages="{{ $errors->first('email') }}"
                                                @endif
                                        >
                                        </v-text-field>
                                        <v-text-field
                                                id="password"
                                                prepend-icon="lock"
                                                name="password"
                                                label="Password"
                                                type="password"
                                                @if ($errors->has('password'))
                                                error-messages="{{ $errors->first('password') }}"
                                                @endif
                                        >
                                        </v-text-field>
                                        <v-card-actions class="justify-center mt-4">
                                            <v-btn type="submit" color="accent">GO</v-btn>
                                        </v-card-actions>
                                    </v-form>

                                    @if (Route::has('password.request'))
                                        <v-card-actions class="justify-center mt-3">
                                            <v-btn
                                                    href="{{ route('password.request') }}"
                                                    flat
                                                    color="secondary"
                                            >
                                                <v-icon class="mr-1">warning</v-icon>パスワードをお忘れですか?
                                            </v-btn>
                                        </v-card-actions>
                                    @endif

                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-content>
        </v-app>
    </template>
@endsection
