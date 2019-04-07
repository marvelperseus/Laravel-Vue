@extends('layouts.base')

@section('content')
    <template>
        <v-app id="welcome">
            <v-content>
                <v-container fluid fill-height>
                    <transition>
                        <v-layout
                                justify-center
                                align-center
                                column
                        >
                            <h1 class="text-xs-center">ORTHO MANAGER</h1>
                            <v-icon x-large color="primary" class="mb-4">fiber_smart_record</v-icon>
                            <p class="text-xs-center">POWERED BY VALUE LINK INC.</p>
                        </v-layout>
                    </transition>
                </v-container>
            </v-content>
        </v-app>
    </template>
@endsection
